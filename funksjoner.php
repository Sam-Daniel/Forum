<?php
// funksjoner.php - inneholder alle hjelpefunksjoner i php

	// funksjoner for å koble opp til database
	function connect() {
		$hostname = 'localhost';
		$username = 'root';
		$password = '';
		$database = 'prosjekt6065';

		$conn = mysqli_connect($hostname, $username, $password, $database);
		if(!$conn) {
			die("Kunne ikke koble til: " . mysql_error($conn));
		}
		if (!mysqli_select_db($conn, $database)) {
			die("Fant ikke databsen: " . mysql_error($conn));
		}

		mysqli_set_charset($conn, 'utf8');

		return $conn;
	}

	// funksjoner for å koble fra database
	function disconnect($conn) {
		mysqli_close($conn);
	}

	// funskjon for å logge ut av en sesjon
	function logout() {
		session_unset();
		session_destroy();
	}

	// funksjon for redirect til homepage
	function home() {
		header("Location: index.php");
	}

 	// funksjon som ser gjennom funksjonsvariabler for å finne ut hvilken css-stil som skal brukes
	function getStyle() {

		// default stylesheet
		$stylesheet = "styles-dark.css";

		// hvis sesjonsvariablene er satt går den videre, men settes
		// bare til lys hvis variabelene har en annen verdi enn "";
		if (isset($_SESSION['stylesheet'])) {
			if($_SESSION['stylesheet'] == "styles-light.css") {
				$stylesheet = "styles-light.css";
			}
		}
	
		return $stylesheet;
	}

	// funksjon som returnerer skriver den nyeste tråden innen en kategori
	function nyesteThread($kid) {
		
		$conn = connect();

		$sql = "CALL getNyesteThread($kid)";

		$result = $conn->query($sql);

		if(!$result)
		    echo "Ingen resultat. Prøv igjen.";
		else {

		    if(mysqli_num_rows($result) == 0) {
		        echo "Kategorien har ingen tråder.";
		    }
		    else {
		        while($row = mysqli_fetch_assoc($result)) {


		            echo '<a href="topic.php?id=' . $row['tid'] . '">'
		            . $row['tTittel'] . '</a>
		            <p>' . $row['tDato'] .'</p>';
		        }
		        
		    }
		}
	}
?>