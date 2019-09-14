<html>
    
    <head>
		<title> Hospital Veterinário </title>
		<meta charset="UTF-8">
	</head>
    
    <body>
        
        
        <form action="addBloodTest.php" method="post">
            
            <h3>Inserir resultado: Análise de Sangue</h3>

            <?php
                //Importa funções genéricas
			    include 'functions.php';
			
                //Ligação à base de dados
                $connection = db_connection();
                
                //Recebe parâmetros da página anterior
                $Animal_name = $_REQUEST['animal_name'];
                $VAT_owner = $_REQUEST['VAT_owner'];
                $VAT_client = $_REQUEST['client_VAT'];
                $Data = $_REQUEST['date_timestamp'];

				//Campos do VAT do cliente, do nome do animal e data e hora não podem ser alterados
                echo("<p> Nome do animal: $Animal_name <input type=\"hidden\" name=\"name_animal\" value='$Animal_name' /> </p>");
                echo("<p> VAT do dono: $VAT_owner <input type=\"hidden\" name=\"VAT_dono\" value='$VAT_owner' /> </p>");
                echo("<p> Data da consulta: $Data <input type=\"hidden\" name=\"Data_consult\" value='$Data' /> </p>");
                echo("<input type=\"hidden\" name=\"client_VAT\" value='$VAT_client'/>");
                
                //Procura VAT dos assistentes na BD
				echo("<p> Assistente: <select name=\"VAT_assistant\">");
				$result = verify_query($connection, "SELECT p.VAT, p.name FROM assistant a, person p where p.VAT = a.VAT");
                foreach($result as $row) {
					$assist_VAT = $row['VAT'];
					$assist_name = $row['name'];
					echo("<option value=\"$assist_VAT\">$assist_name ($assist_VAT)</option>");
				}
				echo("</select> </p>");
				
				$connection = null;

            ?>
            <p>Incluir assistente? <input type="checkbox" name="assistant" value="YES"> </p>
            
            <h4>Indicadores: </h4>
            <p>Glóbulos brancos (%):<input type="number" min="0" max="100" step="0.01" value="50" required="required" name="GlobulosB"/> </p>
            <p>Número de neutrófilos (%):<input type="number" min="0" max="100" step="0.01" value="50" required="required" name="Neutrofilos"/></p>
            <p>Número de linfócitos (%):<input type="number" min="0" max="100" step="0.01" value="50" required="required" name="Linfocitos"/> </p>
            <p>Número de monócitos (%):<input type="number" min="0" max="100" step="0.01" value="50" required="required" name="Monocitos"/> </p>
            <p><input type="submit" value="Inserir Análise"></p>
                
        </form>
    </body>
</html>
