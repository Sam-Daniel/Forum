<?php
// create_cat.php - lager en ny kategori ved inndata fra form
include 'funksjoner.php';
include 'header.php';

$conn = connect();

echo '<h2>Lag ny kategori</h2>';

// sjekker om sesjonen eksisterer
if (!isset($_SESSION['signed_in'])) {
    echo 'Sesjonsfeil. Prøv igjen.';
}
else {

    // sjeker om bruker er logget inn
    if($_SESSION['signed_in'] != 1) {
        echo 'Du må være logget inn.';
    }
    else {

        // sjekker om bruker er admin
        if($_SESSION['user_level'] < 1) {
            echo 'Du må være administrator for å lage en kategori.';
        }
        else {

            // sjekker om et svar er postet, hvis ikke skrives form
            if($_SERVER['REQUEST_METHOD'] != 'POST') {
                echo '<form name="kategoriForm" method="post" onsubmit="return kategoriSjekk();">
                    Kategorinavn: <input type="text" name="cat_name" /><br>
                    Kategoribeskrivelse: <br><textarea name="cat_description" /></textarea><br>
                    <input type="submit" value="Lag kategori" />
                 </form>';
            }
            // hvis svar er postet, gå gjennom inndata på form
            else {

                // setter variabler og rensker inndata, kaller på funksjonen for å sette inn kategori
                $navn = "'" . mysqli_real_escape_string($conn, $_POST['cat_name']) . "'";
                $beskrivelse = "'" . mysql_real_escapei_string($conn, $_POST['cat_description']) . "'";
                $sql = "CALL insertKategori($navn, $beskrivelse)";

                $result = $conn->query($sql);
                if(!$result) {
                    echo 'Noe gikk galt. ' . mysqli_error($conn);

                    // feilmelding for duplikat
                    if (mysqli_errno($conn) == 1062) {
                        echo 'Kategorinavnet er allerede i bruk. Prøv igjen.';
                    }
                }
                // alt gikk bra, posten er lagret
                else {
                    echo 'Kategorien er blitt lagret.';
                }
            }
        }   
    }
    
}

include 'footer.php';
?>

