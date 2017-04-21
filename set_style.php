<?php 
// set_style.php - script for at JS skal kunne gjøre et ajax call og sette riktig css fil

include 'funksjoner.php';

$conn = connect();

$url = "styles-dark.css";


if(isset($_POST['style'])) {
	if(mysqli_real_escape_string($conn, $_POST['style']) == "light") {
		$url = "styles-light.css";
	}
}

session_start();
$_SESSION['stylesheet'] = $url;
?>