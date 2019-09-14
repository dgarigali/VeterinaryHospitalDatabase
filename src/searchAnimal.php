<html>

	<head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">	
	</head>
	
	<body>	
				
		<?php
		
			//Importa funções genéricas
			include 'functions.php';
		
			function print_animals($content) {
				global $client_VAT;
				echo("<h3> Animais encontrados </h3>");
				echo("<table border=\"1\" cellpadding=\"2\" cellspacing=\"5\">\n");
				echo("<thead> <tr> <th>Nome do animal</th> <th>VAT do dono</th> <th>Nome do dono</th> <th>Espécie</th> <th>Cor</th> <th>Género </th> <th> Idade </th> <th> Ver consultas</th> </tr> </thead>");
				foreach($content as $row) {
					echo("<tr> \n");
					echo("<td> <center> {$row['animal_name']} </center> </td> \n");
					echo("<td> <center> {$row['owner_VAT']} </center> </td> \n");
					echo("<td> <center> {$row['owner_name']} </center> </td> \n");
					echo("<td> <center> {$row['species_name']} </center> </td> \n");
					echo("<td> <center> {$row['colour']} </center> </td> \n");
					echo("<td> <center> {$row['gender']} </center> </td> \n");
					echo("<td> <center> {$row['age']} </center> </td> \n");
					echo("<td> <center> <a href=\"SearchConsult.php?animal_name={$row['animal_name']}&owner_VAT={$row['owner_VAT']}&client_VAT=$client_VAT\"> Consultas </a>  </center>  </td> \n");
					echo("</tr>\n");
				}
				echo("</table>\n");
			}
			
			function add_button($button_state) {
				global $client_VAT, $animal_name;
				echo("<form action=\"addAnimalForm.php\" method=\"post\">");
				echo("<input type=\"hidden\" name=\"client_VAT\" value='$client_VAT' />");
				echo("<input type=\"hidden\" name=\"animal_name\" value='$animal_name' />");
				echo("<p> <input type=\"submit\" value=\"Adicionar animal\" $button_state /> </p>");
				echo("</form>");	
			}
			
			function owner_query($flag) {
				global $connection, $animal_name, $owner_name, $client_VAT;
				$result = verify_query_prepare_statement($connection, 
					"SELECT distinct a.name as animal_name, p.name as owner_name, species_name, colour, gender, age, a.VAT as owner_VAT
					FROM animal a, person p
					WHERE a.name = ? and (p.name like ? or '$flag') and a.VAT = ?
						and a.VAT = p.VAT", array($animal_name, "%$owner_name%", $client_VAT));					
				if ($result->rowCount() > 0) {
					return true;
				} else {
					return false;
				}
			}
				
			//Recebe parâmetros da página anterior
			$client_VAT = $_REQUEST['client_VAT'];
			$animal_name = $_REQUEST['animal_name'];
			$owner_name = $_REQUEST['owner_name'];
			
			//Flags
			$client_non_existing_flag = false;
			$client_owner_flag = false;
			$global_query_flag = false;
		
			//Ligação à base de dados
			$connection = db_connection();
			
			//Verifica se cliente existe
			$client_non_existing_flag = verify_non_existing_client($connection, $client_VAT);
			if ($client_non_existing_flag) {
				echo("<p> Aviso: Cliente não existe na base de dados! Não poderá adicionar novo animal nem nova consulta! </p>");
			} else {
				
				//Verifica se cliente é dono do animal	
				$result = verify_query_prepare_statement($connection, 
					"SELECT distinct a.name as animal_name, p.name as owner_name, species_name, colour, gender, age, a.VAT as owner_VAT
					FROM animal a, person p
					WHERE a.name = ? and p.name like ? and a.VAT = ?
						and a.VAT = p.VAT", array($animal_name, "%$owner_name%", $client_VAT));					
				if ($result->rowCount() > 0) {
					print_animals($result); //Imprime logo o animal
					$client_owner_flag = true;
				} else {
					
					//Verifica se o cliente é dono de animal com mesmo nome (já não pode adicionar outro animal)
					$result = verify_query_prepare_statement($connection, "SELECT * FROM animal WHERE name = ? and VAT = ?", array($animal_name, $client_VAT));	
					if ($result->rowCount() > 0) {
						echo("<p> Aviso: Cliente já é dono de animal com esse nome. Não poderá adicionar novo animal! </p>");
						$client_owner_flag = true;
					}
					
					//Verifica se cliente já esteve nalguma consulta do animal
					$result = verify_query_prepare_statement($connection, 
						"SELECT distinct a.name as animal_name, p.name as owner_name, species_name, colour, gender, age, a.VAT as owner_VAT
						FROM animal a, person p, consult c 
						WHERE a.name = ? and p.name like ? and c.VAT_client = ?
							and a.VAT = p.VAT
							and a.name = c.name and a.VAT = c.VAT_owner", array($animal_name, "%$owner_name%", $client_VAT));
					if ($result->rowCount() > 0) {
						print_animals($result); //Imprime logo os animais
						
					} else {
						$global_query_flag = true;
					}
				}
			}
			
			//Procura animal apenas pelo seu nome e pelo nome do dono
			if ($client_non_existing_flag or $global_query_flag) {	
				$result = verify_query_prepare_statement($connection, 
					"SELECT distinct a.name as animal_name, p.name as owner_name, species_name, colour, gender, age, a.VAT as owner_VAT
					FROM animal a, person p
					WHERE a.name = ? and p.name like ?
						and a.VAT = p.VAT", array($animal_name, "%$owner_name%"));
				if ($result->rowCount() == 0) {
					echo("<p> Não existe animal na base de dados com as informações inseridas. </p>");
				} else {
					print_animals($result);	
				}
			}
				
			//Botão para adicionar animal (desativado caso cliente já seja dono do animal ou cliente não exista na BD)
			if ($client_non_existing_flag or $client_owner_flag) {
				add_button("disabled");
			} else {
				add_button("");
			}
			$connection = null;	
			
		?>
		<a href="mainPage.php"> Página Principal </a>
	</body>
</html>