<html>

	<head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">	
	</head>
	
	<body>	
				
		<?php
		
			//Importa funções genéricas
			include 'functions.php';
			
			//Ligação à base de dados
			$connection = db_connection();
			
			//Recebe parâmetros da página anterior
			$client_VAT = $_REQUEST['client_VAT'];
			$animal_name = $_REQUEST['animal_name'];
			$species_name = $_REQUEST['species_name'];
			$colour = $_REQUEST['colour'];
			$gender = $_REQUEST['gender'];
			$birth_year = $_REQUEST['birth_year'];		
			
			//Insere animal na BD
			verify_query_prepare_statement($connection, 
				"INSERT INTO animal VALUE (?, ?, '$species_name', ?, '$gender', $birth_year, NULL)",
				array($animal_name, $client_VAT, $colour));
			$connection = null;
			
			//Mostra que o animal foi criado ao voltar à pagina da pesquisa dos animais
			header("Location: searchAnimal.php?client_VAT=$client_VAT&animal_name=$animal_name&owner_name=");
		?>
	</body>
</html>