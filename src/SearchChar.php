<html>

	<head>
			<title> Hospital Veterinário </title>
			<meta charset="UTF-8">
	</head> 
	
	<body>

		<h3> Características da Consulta </h3>

		<?php

			//Importa funções genéricas
			include 'functions.php';
			
			$name = $_REQUEST["animal"];
			$VAT = $_REQUEST["owner"];
			$date = $_REQUEST["date"];
			$client = $_REQUEST["client"];

			//Ligação à base de dados
			$connection = db_connection();
			
			//Procura informações sobre o animal e sobre a consulta
			$result = verify_query($connection, 
				"SELECT a.name as name, species_name, colour, gender, age, s, o, a, p, weight, VAT_client, VAT_vet
				FROM animal a, consult c
				WHERE a.name = '$name' and a.VAT = '$VAT'
					and c.name = '$name' and c.VAT_owner = '$VAT' and c.date_timestamp = '$date'");
			$row = $result->fetch();
			
			//Imprime características do animal
			echo("<p> <h4> Características do animal </h4> </p>");	
			echo("<table border=\"1\">\n");
			echo("<tr> <th> Nome </th> <th> Espécie </th> <th> Cor </th> <th> Género </th> <th> Idade </th> <th> Peso </th> </tr>\n");
			echo("<tr>");
			echo("<td> <center> {$row['name']} </center> </td>");
			echo("<td> <center> {$row['species_name']} </center> </td>");
			echo("<td> <center> {$row['colour']} </center> </td>");
			echo("<td> <center> {$row['gender']} </center> </td>");
			echo("<td> <center> {$row['age']} </center> </td>");
			echo("<td> <center> {$row['weight']} </center> </td>");
			echo("</tr>");
			echo("</table>\n");
				
			//Imprime características da consulta
			echo("<p> <h4> Características da consulta </h4> </p>");
			echo("<table border=\"1\">\n");
			echo("<tr> <th> <center> VAT do Cliente </th> </center> <td> <center> {$row['VAT_client']} </td> </center> </tr>");
			echo("<tr> <th> <center> VAT do Veterinário </th> </center> <td> <center> {$row['VAT_vet']} </td> </center> </tr>");
			echo("<tr> <th> <center> Subjectivo(S) </th> </center> <td> <center> {$row['s']} </td> </center> </tr>");
			echo("<tr> <th> <center> Objectivo(O) </th> </center> <td> <center> {$row['o']} </td> </center> </tr>");
			echo("<tr> <th> <center> Avaliação(A) </th> </center> <td> <center> {$row['a']} </td> </center> </tr>");
			echo("<tr> <th> <center> Plano(P) </th> </center> <td> <center> {$row['p']} </td> </center> </tr>");  
			echo("</table>\n");

			//Imprime diagnósticos
			$result = verify_query($connection, 
				"SELECT dc.code, dc.name 
				FROM diagnosis_code dc, consult_diagnosis cd 
				WHERE cd.name = '$name' and cd.VAT_owner = '$VAT' and cd.date_timestamp = '$date' 
					and cd.code = dc.code");
			echo("<p> <h4> Diagnósticos </h4> </p>");
			if ($result->rowCount() == 0) {
				echo("<p> Não existem diagnósticos associados a esta consulta. </p>");  
			} else {
				echo("<table border=\"1\">\n");
				echo("<tr> <th> Códigos Diagnóstico </th> <th> Nome Diagnóstico </th> </tr>\n");
				foreach($result as $row) {
					echo("<tr>");
					echo("<td> <center> {$row['code']} </td> </center>");
					echo("<td> <center> {$row['name']} </td> </center>");
					echo("</tr>");
				}
				echo("</table>\n");
				
				//Imprime prescripções
				$result = verify_query($connection, 
					"SELECT code, name_med, lab, dosage, regime
					FROM prescription
					WHERE name = '$name' and VAT_owner = '$VAT' and date_timestamp = '$date'");
				echo("<p> <h4> Prescripções </h4> </p>");
				if ($result->rowCount() == 0) {
					echo("<p> Não existem prescripções associadas a esta consulta. </p>");  
				} else {
					echo("<table border=\"1\">\n");
					echo("<tr> <th> Códigos Diagnóstico </th> <th> Medicamento </th>  <th> Laboratório </th>  <th> Dosagem </th>  <th> Regime </th> </tr>\n");
					foreach($result as $row) {
						echo("<tr>");
						echo("<td> <center> {$row['code']} </td> </center>");
						echo("<td> <center> {$row['name_med']} </td> </center>");
						echo("<td> <center> {$row['lab']} </td> </center>");
						echo("<td> <center> {$row['dosage']} </td> </center>");
						echo("<td> <center> {$row['regime']} </td> </center>");
						echo("</tr>");
					}
					echo("</table>\n");
				}
			}
			
			//Procura resultados de ultimo procedimento criado (ajuda a ver se análise de sangue foi criada com a aplicação)
			$result = verify_query($connection, 
				"SELECT indicator_name, value
				FROM produced_indicator
				WHERE name = '$name' and VAT_owner = '$VAT' and date_timestamp = '$date'
					and num in (select max(num) from produced_indicator WHERE name = '$name' and 	VAT_owner = '$VAT' and date_timestamp = '$date')");
			echo("<p> <h4> Resultados do último procedimento </h4> </p>");
			if ($result->rowCount() == 0) {
				echo("<p> Não existem procedimentos associados a esta consulta. </p>");
			} else {
				echo("<table border=\"1\" cellpadding=\"2\" cellspacing=\"5\">\n");
				echo("<thead> <tr> <th>Nome do indicador</th> <th> Valor</th> </tr> </thead>");
				foreach($result as $row) {
					echo("<tr> \n");
					echo("<td> <center> {$row['indicator_name']} </center> </td> \n");
					echo("<td> <center> {$row['value']} </center> </td> \n");
					echo("</tr>\n");
				}
				echo("</table>\n");		
			}			
			
			$connection = null;
		?>

	</body>
</html>