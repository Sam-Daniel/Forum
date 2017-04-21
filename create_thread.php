<?php
// create_topic.php - sender inn form for å lage en ny tråd
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h2>Lag ny tråd</h2>';

if (!isset($_SESSION['signed_in'])) {
    echo 'Sesjonsfeil. Prøv igjen.';
}
else {
        // sjeker om bruker er logget inn
    if($_SESSION['signed_in'] != 1) {
        echo 'Du må være logget inn.';
    }
    else {
        
        // sjekker om et svar er postet, hvis ikke skrives form
        if($_SERVER['REQUEST_METHOD'] != 'POST')
        {   
            // kaller på funksjon for å lage dropdown for kategorier
            $sql = "CALL getAlleKategorier()";
             
            $result = $conn->query($sql);
             
            if(!$result)
            {
                echo 'En feil har oppstått, prøv igjen.' . mysqli_error($conn);
            }
            else
            {
                if(mysqli_num_rows($result) == 0) {

                    echo 'Fant ingen kategorier i databasen.';
                }
                else {
             
                    // skriver form med JS funksjon for å sjekke at input er gyldi
                    echo '<form name="threadForm" method="post" onsubmit="return threadSjekk();">
                        Tittel: <input type="text" name="topic_subject" /><br>
                        Kategori:'; 
                     
                    echo '<select name="topic_cat">';
                        while($row = mysqli_fetch_assoc($result))
                        {
                            echo '<option value="' . $row['kid'] . '">' . $row['kNavn'] . '</option>';
                        }
                    echo '</select><br><br>'; 
                         
                    echo 'Melding: <br><textarea name="post_content" /></textarea><br>
                        <input type="submit" value="Create topic" />
                     </form>';
                }
            }
        }
        else
        {
            // starter transaksjonen for å sette inn en tråd + post
            $sql  = "BEGIN WORK;";
            $result = $conn->query($sql);
             
            if(!$result)
            {
                // noe gikk galt med svar
                echo 'En feil har oppstått, prøv igjen.' . mysqli_error($conn);
            }
            else
            {
         
                // prøver første del av innsettingen
                $sql = "INSERT INTO 
                            thread(tTittel,
                                   tDato,
                                   kid,
                                   bid)
                       VALUES('" . mysqli_real_escape_string($conn, $_POST['topic_subject']) . "',
                                   NOW(),
                                   " . mysqli_real_escape_string($conn, $_POST['topic_cat']) . ",
                                   " . $_SESSION['user_id'] . "
                                   )";
                          
                $result = $conn->query($sql);
                if(!$result)
                {
                    // noe gikk galt, feilmelding og rollback
                    echo 'En feil skjedde under innsetting av data, prøv igjen.' . mysqli_error($conn);
                    $sql = "ROLLBACK;";
                    $result = $conn->query($sql);;
                }
                else
                {
                    // første query gikk, prøver andre del av innsettinen
                    $topicid = mysqli_insert_id($conn);
                     
                    $sql = "INSERT INTO
                                post(pTekst,
                                      pDato,
                                      tid,
                                      bid)
                            VALUES
                                ('" . mysqli_real_escape_string($conn, $_POST['post_content']) . "',
                                      NOW(),
                                      " . $topicid . ",
                                      " . $_SESSION['user_id'] . "
                                )";
                    $result = $conn->query($sql);
                     
                    if(!$result)
                    {
                        // noe gikk galt, error og rollback
                        echo 'En feil skjedde under innsetting av data, prøv igjen.' . mysqli_error($conn);
                        $sql = "ROLLBACK;";
                        $result = $conn->query($sql);
                    }
                    else
                    {
                        // alt gikk bra, commit og tråden er laget
                        $sql = "COMMIT;";
                        $result = $conn->query($sql);
                         
                        echo 'Tråden din er lagret. Du finner den <a href="topic.php?id='. $topicid . '">her</a>.';
                    }
                }
            }
        }
    }
}
 
include 'footer.php';
?>