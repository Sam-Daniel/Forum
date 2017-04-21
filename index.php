<?php
// index.php - startside som inneholder en liste over alle kategorier
include 'funksjoner.php';
include 'header.php';

$conn = connect();

// kaller funksjon som gir tilbake alle kategoriene
$sql = "CALL getIndexKategorier()";

$result = $conn->query($sql);

if(!$result) {
	echo 'Kunne ikke vise noen kategorier, prøv igjen. ' . mysqli_error($conn); 
}
else {

	if(mysqli_num_rows($result) == 0) {
		echo 'Ingen kategorier definert.';
	}
	else
    {
        // lager table for å sette inn kategoriene
        echo '<table>
              <tr>
                <th>Kategori</th>
                <th>Nyeste tråd</th>
              </tr>'; 
             
        while($row = mysqli_fetch_assoc($result))
        {              
            $kid = $row['kid'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo '<h3><a href="category.php?id=' . $kid . '">' . $row['kNavn'] . '</a></h3>' . $row['kBeskrivelse'];
                echo '</td>';
                echo '<td class="rightpart">';

                // kaller funksjon for å hente ut nyeste tråd innen hver kategori
                nyesteThread($kid);

                echo '</td>';
            echo '</tr>';

        }
        echo '</table>';
    }
}
	
include 'footer.php';

?>

