<?php
// slett_kategori.php - 
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h2>Slett kategori</h2>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // hvis en manuelt går til denne siden får man en error
    echo 'Kategorier kan bare slettes fra tråden de er skrevet i.';
}

else
{

    if (!isset($_SESSION['signed_in'])) {
        echo 'Sesjonsfeil. Prøv igjen.';
    }
    else {
        
        // sjekker om bruker er admin
        if($_SESSION['user_level'] < 1) {
            echo 'Du må være administrator for å slette en kategori.';
        }
        else {
            $sql = "CALL slettKategori('" . mysqli_real_escape_string($conn, $_GET['id']) . "')";;

            $result = $conn->query($sql);
            if(!$result) {
                echo 'Noe gikk galt, prøv igjen. ';

            }
            else {
               echo 'Kategorien er slettet er slettet.';
            }
        }
    }

}
 
include 'footer.php';
?>