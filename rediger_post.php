<?php
// rediger_post.php - får inn en pid og sjekker om bruker har rettighet til å redigere
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h3>Rediger post</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // hvis en manuelt går til denne siden får man en error
    echo 'Denne siden kan ikke nåes direkte.';
}

else {

    // sjekker om bruker er logget inn
    if (!isset($_SESSION['signed_in'])) {
        echo 'Sesjonsfeil. Prøv igjen.';
    }
    else {
        
        if($_SESSION['user_id'] != mysqli_real_escape_string($conn, $_POST['bid']) || $_SESSION['user_level'] < 1) {
            echo 'Du må være forfatteren av en post eller admin for å slette den.';
        }

        else {
            // renser inndata og kaller på funksjon for å vise poster
            $pid = mysqli_real_escape_string($conn, $_POST['id']);
            $sql = "CALL getPostbyID($pid)";

            $result = $conn->query($sql);
            if(!$result) {
                echo 'Noe gikk galt, prøv igjen. ';

            }
            else {
                if(mysqli_num_rows($result) == 0) {
                    echo "Fant ingen post.";
                }
                else {

                    // hvis en post er gitt tilbake blir den vist i en form. 
                    // Javascript henter ut teksten og setter den inn i en textarea for redigering
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<form name="redigerForm" method="post" action="rediger_post_send.php" onsubmit="return redigerSjekk();">';
                        echo '<input type="hidden" name="post_id" value="' . $row['pid'] . '"/>';
                        echo '<input type="hidden" name="thread_id" value="' . $row['tid'] . '"/>';

                        echo '<textarea id="editArea" name="post_text"></textarea>';
                        echo "<script type=\"text/javascript\">
                            $('#editArea').val('". $row['pTekst'] ."');
                        </script>";
                        echo '<input type="submit" value="Oppdater post" />';
                        echo '</form>';
                    }
                }
            }   
        }
    }

}
 
include 'footer.php';
?>