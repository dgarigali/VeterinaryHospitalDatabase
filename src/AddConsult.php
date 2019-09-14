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
            $name = $_REQUEST["animal"];
            $VAT = $_REQUEST["owner"];
            $date = $_REQUEST["date"];
            $client = $_REQUEST["client"];
            $VAT_vet = $_REQUEST["VAT_vet"];
            $weight = $_REQUEST["weight"];
            $S = $_REQUEST["s"];
            $O = $_REQUEST["o"];
            $A = $_REQUEST["a"];
            $P = $_REQUEST["p"];
            $check = $_REQUEST["diagnostic"];
            $code = $_REQUEST["d_code"];

            if (empty($code)){ 
            
                 //Insere consulta na BD
                 verify_query_prepare_statement($connection, 
                     "INSERT INTO consult VALUE ('$name', '$VAT', '$date', ?, ?, ?, ?, '$client', '$VAT_vet', $weight)",
                    array($S, $O, $A, $P));
                
            } else{
				
                //Insere diagnóstico na BD
                verify_query($connection, 
					"INSERT INTO consult_diagnosis VALUE ('$code', '$name', '$VAT','$date')");
            }

			//Coloca formulário caso tenha indicado que deseja inserir diagnóstico, caso contrário, volta à pagina das consultas
            if ( $check == 'YES'){

                echo("<h2>Insira um diagnóstico</h2>");
                echo("<form action=\"AddConsult.php\" method=\"post\">");
	            echo("<input type=\"hidden\" name=\"animal\" value='$name' />");
	            echo("<input type=\"hidden\" name=\"owner\" value='$VAT' />");
                echo("<input type=\"hidden\" name=\"client\" value='$client' />");
                echo("<input type=\"hidden\" name=\"date\" value='$date' />");
				
				//Procura código dos diagnósticos na BD que não estejam ainda atribuídos à consulta
				$result = verify_query($connection, "SELECT name, code FROM diagnosis_code WHERE code not in (
														SELECT code from consult_diagnosis where name = '$name' 
															and VAT_owner = '$VAT' and date_timestamp = '$date')");
				echo("<p> Diagnósticos: <select name=\"d_code\">");
				foreach($result as $row) {
					$diagnosis_code = $row['code'];
					$diagnosis_name = $row['name'];
					echo("<option value=\"$diagnosis_code\">$diagnosis_name ($diagnosis_code)</option>");
				}
				echo("</select> </p>");
				
				//Resto do formulário
                echo ("<p> Deseja acrescentar mais um diagnóstico? <input type=\"checkbox\" name=\"diagnostic\" value=\"YES\"> </p>");
	            echo("<p> <input type=\"submit\" value=\"Inserir diagnóstico\" /> </p>");
	            echo("</form>");

            }
            else{
                
                //Mostra que a consulta foi criada ao voltar à pagina da pesquisa das consultas
                header("Location: SearchConsult.php?client_VAT=$client&animal_name=$name&owner_VAT=$VAT");
            }
            $connection = null;
		?>
	</body>
</html>