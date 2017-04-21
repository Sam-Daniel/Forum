<?php
// userbar.php - logge inn, registrer, viser bruker

	if (!empty($_SESSION['signed_in'])) {
    	echo '<li class="userbar-tekst"><p>Logget inn som ' . $_SESSION['user_name'] . '</p></li>';
        echo '<li class="userbar"><a href="signout.php">Logg ut</a></li>';	
    }
    else {
        echo ' <li class="userbar"><a href="register.php">Registrer deg</a></li> <li class="userbar"><a href="login.php">Logg inn</a></li>';
    }
?>