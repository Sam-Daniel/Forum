<?php
// login.php - poster form for å logge inn en bruker
include 'funksjoner.php';
include 'header.php';

$conn = connect();
 
echo '<h3>Logg inn</h3>';
 
// sjekker om bruker er logget inn
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    echo 'Du er allerede logget inn. <a href="signout.php">Logg ut</a>';
}
else
{
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        // poster form hvis den ikke finner svar i $_POST
        echo '<form method="post" action="">
            Username: <input type="text" name="user_name" />
            Password: <input type="password" name="user_pass">
            <input type="submit" value="Sign in" />
         </form>';
    }
    // behandler inndata hvis det allerede er postet
    else
    {
        
        // sjekker om inndata er korrekt før bruker logges inn
        $errors = array();
         
        if(!isset($_POST['user_name']))
        {
            $errors[] = 'The username field must not be empty.';
        }
         
        if(!isset($_POST['user_pass']))
        {
            $errors[] = 'The password field must not be empty.';
        }
         
        // sjekker om noen feil er postet
        if(!empty($errors))
        {
            echo 'Oops, du fylte ikke inn riktig!';
            echo '<ul>';
            foreach($errors as $key => $value) 
            {
                echo '<li>' . $value . '</li>'; 
            }
            echo '</ul>';
        }
        else
        {
            // lagres hvis fylt inn korrekt
            $pass = sha1(mysqli_real_escape_string($conn, $_POST['user_pass']));
            $name = mysqli_real_escape_string($conn, $_POST['user_name']);

            $sql = "CALL getBruker('$name', '$pass')";
                         
            $result = $conn->query($sql);
            if(!$result)
            {
                echo 'Noe gikk galt, prøv igjen.';
                echo mysqli_error($conn);
            }
            else
            {
                // spørringen har hendt, kan enten ha feil eller riktig login
                if(mysqli_num_rows($result) == 0) {
                    echo 'Passord og brukernavn stemte ikke, prøv igjen.';
                }
                else {
                 
                    // setter sesjonsvariabler
                    $_SESSION['signed_in'] = true;
                     
                    $row = mysqli_fetch_assoc($result);
                    while($row)
                    {
                        $_SESSION['user_id']    = $row['bid'];
                        $_SESSION['user_name']  = $row['bNavn'];
                        $_SESSION['user_level'] = $row['ulevel'];
                        $_SESSION['stylesheet'] = getStyle();
                        $row = mysqli_fetch_assoc($result);
                    }
                    home();
                }
            }
        }
    }
}
 
include 'footer.php';
?>