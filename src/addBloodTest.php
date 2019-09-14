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
            $Animal_name = $_REQUEST['name_animal'];
            $VAT_owner = $_REQUEST['VAT_dono'];
            $VAT_client = $_REQUEST['client_VAT'];            
            $VAT_assistant = $_REQUEST['VAT_assistant'];
            $Data = $_REQUEST['Data_consult'];            
            $GlobulosB = $_REQUEST['GlobulosB'];
            $Neutrofilos = $_REQUEST['Neutrofilos'];
            $Linfocitos = $_REQUEST['Linfocitos'];
            $Monocitos = $_REQUEST['Monocitos'];
            $check = $_REQUEST["assistant"];

            //Procura id do último procedimento (caso exista, soma um valor, caso contrário, fica zero)
            $result = verify_query($connection, "SELECT max(num) as num_proc 
                                                FROM procedures 
                                                WHERE name='$Animal_name' AND VAT_owner='$VAT_owner' AND date_timestamp='$Data'");

            //Obtém o único registo
            $num = $result->fetch();   
            
            //Verifica se já existe algum procedimento associado a uma consulta
            if (is_null ($num['num_proc'])){
                $num = 0;
            }else {
                $num = $num['num_proc'] + 1;
            }

            //As inserções são efectuadas dentro de uma única transação
            $connection->beginTransaction();
           
            //Insere o novo procedimento
            $result1 = $connection->exec("insert into procedures value ('$Animal_name', '$VAT_owner', '$Data', $num, 'Análise de sangue')");
            $result2 = $connection->exec("insert into test_procedure value ('$Animal_name', '$VAT_owner', '$Data', $num, 'Análise de sangue')");
            $result3 = 1;

            //Verifica se o procedimento teve um assistente
            if ($check == 'YES'){
               
                $result3 = $connection->exec("insert into performed value ('$Animal_name', '$VAT_owner', '$Data', $num, '$VAT_assistant')");
            
            }

            //Insere o resultado dos indicadores
            $result4 = $connection->exec("insert into produced_indicator values 
				('$Animal_name', '$VAT_owner', '$Data', $num, 'Glóbulos brancos', $GlobulosB),
                ('$Animal_name', '$VAT_owner', '$Data', $num, 'Número de neutrófilos', $Neutrofilos),
				('$Animal_name', '$VAT_owner', '$Data', $num, 'Número de linfócitos', $Linfocitos),
				('$Animal_name', '$VAT_owner', '$Data', $num, 'Número de monócitos', $Monocitos)");												          
            
            //Verifica se todas as inserções foram efectuadas com sucesso
            if ($result1 and $result2 and $result3 and $result4) {
                $connection->commit();
            } else {
                $connection->rollBack();
            }
            
            $connection = null;

            //Volta à pagina das consultas
            header("Location: SearchConsult.php?client_VAT=$VAT_client&animal_name=$Animal_name&owner_VAT=$VAT_owner");
        ?>
    </body>
</html>