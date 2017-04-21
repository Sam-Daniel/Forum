<?php
// rediger_post_send - sender inn data fra rediger_post.php 
// noen få poster vil ikke kunne bli redigert. SQL sier "1062 duplicate entry", men jeg vet ikke hvorfor
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h3>Rediger post</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // hvis en manuelt går til denne siden får man en error
    echo 'Denne siden kan ikke nåes direkte.';
}

else
{
    // sjekker om logget inn
    if (!isset($_SESSION['signed_in']) && !isset($_SESSION['user_id'])) {
        echo 'Sesjonsfeil. Prøv igjen.';
    }
    else {

            // setter og renser inndata
            $ptext = mysqli_real_escape_string($conn, $_POST['post_text']);
            $pid = mysqli_real_escape_string($conn, $_POST['post_id']);
        
            $sql = "CALL updatePost('$ptext', $pid)";

            $result = $conn->query($sql);

            if(!$result) {
                echo 'Noe gikk galt, prøv igjen. ' . mysqli_error($conn);
                echo $sql;
            }
            else {

                // hvis sql gir svar er den nye posten oppdatert 
                $threadid = mysqli_real_escape_string($conn, $_POST['thread_id']);
                echo "Posten din er oppdatert. Du kan finne den <a href=\"topic.php?id=$threadid\">her</a>";
                    
            }

    }

}
 
include 'footer.php';
?>