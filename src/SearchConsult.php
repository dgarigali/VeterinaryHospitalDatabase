<html>

	<head>
			<title> Hospital Veterinário </title>
			<meta charset="UTF-8">	
	</head> 

	<body>

		<h3>Consultas de um Animal</h3>

		<?php

			//Importa funções genéricas
			include 'functions.php';

			//Recebe parâmetros da página anterior
			$name = $_REQUEST["animal_name"];
			$owner = $_REQUEST["owner_VAT"];
			$client = $_REQUEST["client_VAT"];
			$date1 = $_REQUEST["date1"];
			$date2 = $_REQUEST["date2"];
			$date2 = date('Y-m-d', strtotime($date2. ' + 1 days')); //inclusive

			function add_button($button_state) {
				global $client, $name, $owner;
				echo("<form action=\"AddFormConsult.php\" method=\"post\">");
				echo("<input type=\"hidden\" name=\"client\" value='$client' />");
				echo("<input type=\"hidden\" name=\"animal\" value='$name' />");
				echo("<input type=\"hidden\" name=\"owner\" value='$owner' />");
				echo("<p> <input type=\"submit\" value=\"Adicionar Consulta\" $button_state /> </p>");
				echo("</form>");	
			}

			//Flags
			$client_non_existing_flag = false;

			//Ligação à base de dados
			$connection = db_connection();

			//Verifica se cliente existe
			$client_non_existing_flag = verify_non_existing_client($connection, $client);
			if ($client_non_existing_flag) {
				echo("<p> Aviso: Cliente não existe na base de dados! Não poderá adicionar nova consulta! </p>");
			}

			//Verifica se é para filtrar por data
			if (empty($date1) or empty($date2)){

				// Procura consultas do animal
				$result = verify_query($connection, 
					"SELECT name, VAT_owner, date_timestamp
					FROM consult
					WHERE name = '$name' and VAT_owner = '$owner'
					ORDER BY date_timestamp");
			
			} else {
				$date1 = date( 'Y-m-d 00:00:00', strtotime($date1));
				$date2 = date( 'Y-m-d 00:00:00', strtotime($date2));

				// Procura consultas num intervalo de datas
				$result = verify_query($connection, 
					"SELECT name, VAT_owner, date_timestamp
					FROM consult
					WHERE name = '$name' and VAT_owner = '$owner'
						and (date_timestamp between '$date1' and '$date2')
					ORDER BY date_timestamp");
			}

			//Verifica se existem consultas. Caso afirmativo, imprime-as numa tabela
			if ($result->rowCount() > 0){
				$i = 1;
				echo("<table border=\"1\" cellpadding=\"2\" cellspacing=\"5\">\n");
				echo("<thead> <tr> <th> Detalhes da consulta </th> <th>Nome do animal</th> <th>VAT do dono</th> <th>Data e hora</th> <th> Adicionar análise de sangue </th> </tr> </thead>");
				foreach($result as $row) {
					if ($i == 1) {
						$first_date = $row['date_timestamp'];
					}
					echo("<tr> \n");
					echo("<td> <center> <a href=\"SearchChar.php?animal={$row['name']}&owner={$row['VAT_owner']}&date={$row['date_timestamp']}&client=$client\"> Consulta $i </a>  </center>  </td> \n");
					echo("<td> <center> {$row['name']} </center> </td> \n");
					echo("<td> <center> {$row['VAT_owner']} </center> </td> \n");
					echo("<td> <center> {$row['date_timestamp']} </center> </td> \n");
					echo("<td> <center> <a href=\"addBloodTestForm.php?animal_name={$row['name']}&VAT_owner={$row['VAT_owner']}&client_VAT=$client&date_timestamp={$row['date_timestamp']}\"> Análise </a>  </center>  </td> \n");
					echo("</tr>\n");
					$i++;
				}
				echo("</table>\n");
				
				//Formulário para filtrar por data
				$first_date = date('Y-m-d', strtotime($first_date));
				$current_date = date("Y-m-d");
				echo("<h4>Inserir intervalo de datas para filtrar consultas:</h4>");
				echo("<form action=\"SearchConsult.php\" method=\"post\">");
				echo("<input type=\"hidden\" name=\"animal_name\" value='$name' />");
				echo("<input type=\"hidden\" name=\"owner_VAT\" value='$owner' />");
				echo("<input type=\"hidden\" name=\"client_VAT\" value='$client' />");
				echo("Data inicial: <input type=\"date\" name=\"date1\" min='$first_date' max='$current_date' value='$first_date' required />");
				echo(" Data final: <input type=\"date\" name=\"date2\" min='$first_date' max='$current_date' value='$current_date' required />  ");
				echo("<input type=\"submit\" value=\"Filtrar\" />");
				echo("</form>");
				
			} else {
				echo("<p> Animal ainda não teve consultas no hospital. </p>");
			}	
			
			//Botão para adicionar consulta (cliente não exista na BD)
			if ($client_non_existing_flag ) {
				add_button("disabled");
			} else {
				add_button("");
			}

			$connection = null;

		?>

		<a href="mainPage.php"> Página Principal </a>

	</body>
</html>