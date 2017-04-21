<?php
// category.php - viser en liste over poster inne i en kategori
include 'funksjoner.php';
include 'header.php';

$conn = connect();

// sjekker om siden blir kalt uten parameter
if (!isset($_GET['id'])) {
    echo "Denne siden kan ikke nåes direkte.";
}
else {

    // rensker parameter og sender det inn for å få tilbake kategorien
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "CALL getKategoriByID($id)";
     
    $result = $conn->query($sql);
     
    if(!$result) {
        echo 'Kunne ikke vise kategorien, prøv igjen..' . mysqli_error($conn);
    }
    else {
       if(mysqli_num_rows($result) == 0) {
            echo 'Fant ingen kategorier.';
        }
        else {

            // skriver kategoridata
            while($row = mysqli_fetch_assoc($result))
            {
                echo '<h2>Poster i ′' . $row['kNavn'] . '′ </h2>';
            }

            // hvis innlogget bruker er admin skrives adminverktøy
            if (isset($_SESSION['user_level']) && $_SESSION['user_level'] > 0) {
                echo '<form class="adminForm" method="post" action="slett_kategori.php?id=' . $id . '">
                    <input type="submit" value="Slett kategori" />
                </form>';
            }
         
            // ny connection
            $conn = connect();

            // kaller på en funksjon for å få alle tråder i en kategori
            $sql = "CALL getThreadsInKategori($id)";
             
            $result = $conn->query($sql);
             
            if(!$result)
            {
                echo 'Kunne ikke vise noen poster, prøv igjen.' . mysqli_error($conn);
            }
            else
            {
                if(mysqli_num_rows($result) == 0) {
                    echo 'Ingen poster i denne kategorien.';
                }
                else
                {
                    // gjør klar table 
                    echo '<table border="1">
                          <tr>
                            <th>Trådnavn</th>
                            <th>Skrevet</th>
                          </tr>'; 
                         
                    // skriver en tråd per rad i table
                    while($row = mysqli_fetch_assoc($result)) {               
                        echo '<tr>';
                            echo '<td class="leftpart">';
                                echo '<h3><a href="topic.php?id=' . $row['tid'] . '">' . $row['tTittel'] . '</a><h3>';
                            echo '</td>';
                            echo '<td class="rightpart">';
                                echo 'Skrevet ' . date('d-m-Y', strtotime($row['tDato'])) . ' av ' . $row['bnavn'] . '<br>';
                            echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
            }
        }
    }
}

include 'footer.php';
?>