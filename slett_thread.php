<?php
// slett_thread.php - sletter en hel tråd
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h3>Slett tråd</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // hvis en manuelt går til denne siden får man en error
    echo 'Tråder kan bare slettes fra forumet.';
}

else
{

    if (!isset($_SESSION['signed_in'])) {
        echo 'Sesjonsfeil. Prøv igjen.';
    }
    else {
        
        // sjekker om bruker er admin
        if($_SESSION['user_level'] < 1) {
            echo 'Du må være administrator for å slette en tråd.';
        }
        else {
            $sql = "CALL slettThread('" . mysqli_real_escape_string($conn, $_GET['id']) . "')";

            $result = $conn->query($sql);
            if(!$result) {
                echo 'Noe gikk galt, prøv igjen. ';

            }
            else {
                echo 'Tråden er slettet.';
            }
        }
    }

}
 
include 'footer.php';
?>