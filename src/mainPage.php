<html>

	<head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">
	</head>

	<body>
	
		<form action="searchAnimal.php" method="post">
		
			<h3> Inserir informação do cliente e do animal </h3>
			<p> VAT do cliente: <input type="text" required="required" name="client_VAT"/> </p>
			<p> Nome do animal: <input type="text" required="required" name="animal_name"/> </p>
			<p> Nome do dono do animal (opcional): <input type="text" name="owner_name"/> </p>
			<p> <input type="submit" value="Procurar"/> </p>
			
		</form>

	</body>
</html>