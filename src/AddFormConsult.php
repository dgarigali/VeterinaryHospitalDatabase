<html>

	<head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">	
	</head>
	
	<body>	
	
		<form action="AddConsult.php" method="post">

			<h3>Inserir nova Consulta</h3>
				
			<?php
			
				//Importa funções genéricas
				include 'functions.php';
				
				//Ligação à base de dados
				$connection = db_connection();
				
				//Recebe parâmetros da página anterior
				$name = $_REQUEST["animal"];
                $VAT = $_REQUEST["owner"];
                $client = $_REQUEST["client"];
				
                //Campos do VAT do cliente, VAT do dono e do nome do animal não podem ser alterados
				echo("<p> Nome do animal: $name <input type=\"hidden\" name=\"animal\" value='$name' /> </p>");
                echo("<p> VAT do dono: $VAT <input type=\"hidden\" name=\"owner\" value='$VAT' /> </p>");
                echo("<p> VAT do cliente: $client <input type=\"hidden\" name=\"client\" value='$client' /> </p>");
                
				//Coloca data e hora actual
				$current_time = date("Y-m-d H:i:s");
				echo("<p> Data e hora: $current_time <input type=\"hidden\" name=\"date\" value='$current_time' /> </p>");
				
				//Procura VAT dos veterinários na BD
				echo("<p> Veterinário: <select name=\"VAT_vet\">");
				$result = verify_query($connection, "SELECT p.VAT, p.name FROM veterinary v, person p where p.VAT = v.VAT");
				foreach($result as $row) {
					$vet_VAT = $row['VAT'];
					$vet_name = $row['name'];
					echo("<option value=\"$vet_VAT\">$vet_name ($vet_VAT)</option>");
				}
				echo("</select> </p>");
				
				$connection = null;
			?>
		
			<p> Peso: <input type="number" min="0.01" step="0.01" value="0.01" required="required" name="weight"/> </p>
			<p> Subjectivo(S): <textarea rows="4" cols="50" name="s" required> N.A. </textarea> </p>
			<p> Objectivo(O): <textarea rows="4" cols="50" name="o" required> N.A. </textarea> </p>
			<p> Avaliação(A): <textarea rows="4" cols="50" name="a" required> N.A. </textarea> </p>
			<p> Plano(P): <textarea rows="4" cols="50" name="p" required> N.A. </textarea> </p>
            <p> Acrescentar diagnóstico? <input type="checkbox" name="diagnostic" value="YES"> </p>
			<p> <input type="submit" value="Inserir Consulta"/> </p>
			
		</form>		
	</body>
</html>