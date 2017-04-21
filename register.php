<?php
// register.php - setter opp et form for at bruker skal kunne registerer seg
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h3>Sign up</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    // skriver form hvis det ikke er postet noe
    echo '<form name="registrerForm" method="post" onsubmit="return registrerSjekk();">
        Username: <input type="text" name="user_name" /><br>
        Password: <input type="password" name="user_pass"><br>
        Password again: <input type="password" name="user_pass_check"><br>
        E-mail: <input type="email" name="user_email"><br>
        <input type="submit" value="Registrer" />
     </form>';
}

// hvis noe er i $_POSt brukes dette
else {

    // sender inndata til en funksjon for å lage ny burker
    $sql = "CALL insertBruker('" . mysqli_real_escape_string($conn, $_POST['user_name']) . "',
    '" . sha1($_POST['user_pass']) . "',
    '" . mysqli_real_escape_string($conn, $_POST['user_email']) . "')"; 

    $result = $conn->query($sql);
    if(!$result) {
        echo 'Noe gikk galt, prøv igjen. ';

        // hvis duplikatinformasjon er sendt inn
        if (mysqli_errno($conn) == 1062) {
            echo 'Brukernavnet eller emailen er allerede i bruk.';
        }
    }

    // hvis sql kommer tilbake er bruker satt inn korrekt
    else {
        echo 'Du har registrert deg! Du kan logge inn <a href="login.php">her</a>.';
    }
}
 
include 'footer.php';
?>