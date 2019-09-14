<?php
		
	function db_connection() {
		
		//Credenciais da base de dados
		$host = "db.tecnico.ulisboa.pt";
		$user = "ist193883";
		$pass = "kfuy9068";
		$dsn = "mysql:host=$host;dbname=$user";

		//Ligação à base de dados
		try {
			$connection = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch(PDOException $exception) {
			echo("<p>Error: ");
			echo($exception->getMessage());
			echo("</p>");
			exit();
		}
		return $connection;
	}
	
	function verify_query_prepare_statement($connection, $query, $query_array) {
		$sth = $connection->prepare($query);
		if ($sth == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
		$sth->execute($query_array);
		return $sth;
	}

	function verify_query($connection, $query) {
		$result = $connection->query($query);
		if ($result == FALSE) {
			$info = $connection->errorInfo();
			echo("<p>Error: {$info[2]}</p>");
			exit();
		}
		return $result;
	}
	
	function verify_non_existing_client($connection, $client_VAT) {		
		$result = verify_query_prepare_statement($connection, "SELECT * from client where VAT = ?", array($client_VAT));
		if ($result->rowCount() == 0) {
			return true;
		} else {
			return false;
		}
	}
?>