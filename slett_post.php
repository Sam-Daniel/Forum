<?php
// slett_post.php - form  for å slette en enkelt post
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h2>Slett post</h2>';
echo mysqli_real_escape_string($conn, $_POST['id']);
echo mysqli_real_escape_string($conn, $_POST['bid']);
echo $_SESSION['user_id'];
echo $_SESSION['user_id'] == $_POST['bid'];
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // hvis en manuelt går til denne siden får man en error
    echo 'Poster kan bare slettes fra tråden de er skrevet i.';
}

else
{
    if (!isset($_SESSION['signed_in']) && !isset($_SESSION['user_id'])) {
        echo 'Sesjonsfeil. Prøv igjen.';
    }
    else {
        
        // sjekker om bruker er admin
        if($_SESSION['user_id'] != mysqli_real_escape_string($conn, $_POST['bid']) || !$_SESSION['user_level'] < 1) {
            echo 'Du må være forfatteren av en post eller admin for å slette den.';
        }
        else {
            $sql = "CALL slettPost('" . mysqli_real_escape_string($conn, $_POST['id']) . "')";;

            $result = $conn->query($sql);
            if(!$result) {
                echo 'Noe gikk galt, prøv igjen. ';

            }
            else {
               echo 'Posten er slettet.';
            }
        }
    }

}
 
include 'footer.php';
?>