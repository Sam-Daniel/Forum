<?php
// topic.php - viser alle postene inne i en tråd
include 'funksjoner.php';
include 'header.php';

$conn = connect();

// vil ikke vise tråd uten id
if (!isset($_GET['id'])) {
    echo "Denne siden kan ikke nåes direkte.";
}
else {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // får info om tråden
    $sql = "SELECT
                tid,
                tTittel
    FROM
                thread
    WHERE
                tid = $id";
     
    $result = $conn->query($sql);
     
    if(!$result)
    {
        echo 'Kunne ikke vise tråden. Prøv igjen. ' . mysqli_error($conn);
    }
    else
    {
        if(mysqli_num_rows($result) == 0) {
            echo 'Fant ikke tråden.';
        }
        else
        {
            // lager header for forumtråden
            $row = mysqli_fetch_assoc($result);
            echo '<table border="1">
                <tr>
                    <th colspan="2">' . $row['tTittel'] . '</th>
                </tr>';


            // lager alle postene i tråden
            $sql = "CALL getPostsInThread($id)";

            $result = $conn->query($sql);

            // hvis ingen svar
            if(!$result) {
                echo 'Kunne ikke vise noen svar. ' . mysqli_error($conn);
            }        
            else {

                // hvis svar er 0
                if(mysqli_num_rows($result) == 0) {
                    echo 'Det er ingen svar i tråden.';
                }
                else {

                    // hvis admin så vis adminverktøy
                    if (isset($_SESSION['user_level']) && $_SESSION['user_level'] > 0) {
                        echo '<form class="adminForm" method="post" action="slett_thread.php?id=' . $id . '">
                            <input type="submit" value="Slett tråd" />
                        </form>';
                    }


                    // skriver alle postene
                    while($row = mysqli_fetch_assoc($result)) {
                        echo    '<tr>
                                    <td class="rightpart">
                                        <p>' . $row['bnavn'] . '</p><p>Postet: ' . $row['pDato'] . '</p>
                                    </td>
                                    <td class="leftpart">
                                        <p>' . $row['pTekst'] . '</p>';

                        // hvis admin eller current bruker er postskribent
                        if (isset($_SESSION['user_level']) && isset($_SESSION['user_id'])) {
                            if($_SESSION['user_level'] > 0 || $row['bid'] == $_SESSION['user_id']) {

                                echo '<form class="adminForm" method="post" action="slett_post.php">
                                    <input type="hidden" name="id" value="' . $row['pid'] .'"/>
                                    <input type="hidden" name="bid" value="' . $row['bid'] .'"/>
                                    <input type="submit" value="Slett post" />
                                </form>';

                                echo '<form class="adminForm" method="post" action="rediger_post.php">
                                    <input type="hidden" name="id" value="' . $row['pid'] .'"/>
                                    <input type="hidden" name="bid" value="' . $row['bid'] .'"/>
                                    <input type="submit" value="Rediger post" />
                                </form>';
                            }
                        }   

                        echo         '</td>
                                </tr>';
                    }
                }
            }

            echo '</table>';

            // form for å legge inn et svar
            echo '<h3>Svar: </h3>
                <form name="replyForm" method="post" onsubmit="return replySjekk();" action="reply.php?id=' . $id . '">
                    <textarea name="reply-content"></textarea>
                    <input type="submit" value="Submit reply" />
                </form>';
        }
    }
}
include 'footer.php';

?>