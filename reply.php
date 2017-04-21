<?php
// reply.php - tar inndata som sendes fra topic.php når en bruker skal skrive poste i en tråd

include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    // hvis $_POSt er tom og filen kalles direkte
    echo 'Denne siden kan ikke nåes direkte.';
}

else {

    // sjekker om logget inn er satt
    if(!isset($_SESSION['signed_in'])) {
        echo 'Fant ikke innloggingsesjonen, prøv igjen.';
    }
    else {

        // sjekker om logget inn
        if($_SESSION['signed_in'] != 1) {
            echo 'Du må være logget inn.';
        }
        else {

            // renser og sender inn-data til funksjon for å insert reply
            $ptext = $_POST['reply-content'];
            $tid = mysqli_real_escape_string($conn, $_GET['id']);
            $bid = $_SESSION['user_id'];

            $sql = "CALL insertReply('$ptext', $tid, $bid)";
                             
            $result = $conn->query($sql);
                             
            if(!$result) {
                echo 'Posten din ble ikke lagret. Prøv igjen.';
            }
            else {
                // hvis ingen feil
                echo 'Posten din er lagret <a href="topic.php?id=' . mysqli_real_escape_string($conn, $_GET['id']) . '">her</a>';
            }
        }
    }
}
 
include 'footer.php';
?>