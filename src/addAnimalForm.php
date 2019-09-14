<html>

	<head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">	
	</head>
	
	<body>	
	
		<form action="addAnimal.php" method="post">

			<h3>Inserir novo animal</h3>
				
			<?php
			
				//Importa funções genéricas
				include 'functions.php';
				
				//Ligação à base de dados
				$connection = db_connection();
				
				//Recebe parâmetros da página anterior
				$client_VAT = $_REQUEST['client_VAT'];
				$animal_name = $_REQUEST['animal_name'];
				
				//Campos do VAT do cliente e do nome do animal não podem ser alterados
				echo("<p> VAT do dono: $client_VAT <input type=\"hidden\" name=\"client_VAT\" value='$client_VAT'/> </p>");
				echo("<p> Nome do animal: $animal_name <input type=\"hidden\" name=\"animal_name\" value='$animal_name'/> </p>");
				echo("<p> Espécie: <select name=\"species_name\">");
				
				//Procura espécies armazenadas na BD
				$result = verify_query($connection, "SELECT name FROM species");
				foreach($result as $row) {
					$species_name = $row['name'];
					echo("<option value=\"$species_name\">$species_name</option>");
				}
				echo("</select> </p>");
				
				//Coloca por default o ano actual
				$current_year = date("Y");
				echo("<p> Ano de Nascimento: <input type=\"number\" max=\"$current_year\" value=\"$current_year\" required=\"required\" name=\"birth_year\"/> </p>");
						
				$connection = null;
			?>
			
			<p> Cor: <input type="text" required="required" name="colour"/></p>
			<p> Género: <select name="gender"> 
							<option value="Masculino"> Masculino </option>
							<option value="Feminino"> Feminino </option>
						</select> </p>
			<p><input type="submit" value="Inserir animal"/></p>
			
		</form>		
	</body>
</html>