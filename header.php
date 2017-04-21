<?php session_start();

echo '<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta name="description" content="Et lite forum" />
    <meta name="keywords" content="forum" />
    <title>Prosjekt 6065</title>

    <link rel="stylesheet" href="' . getStyle() .'" type="text/css" />

    <script src="jquery-3.1.0.min.js"></script>
    <script src="funksjoner.js"></script> 
</head>

<body>';

// knapper for å forandre css, sender AJAX-kall for å forandre sesjonsvariabler. krever refresh

echo '
<input onclick="setCSS(\'dark\')" type="button" value="Mørkt tema"/>
<input onclick="setCSS(\'light\')" type="button" value="Lyst tema"/>

    <div id="wrapper">
        <h1>My forum</h1>
    <div id="menu">
        <ul>
            <li><a class="item" href="index.php">Hjem</a></li>
            <li><a class="item" href="create_thread.php">Ny tråd</a></li>
            <li><a class="item" href="create_cat.php">Ny kategori</a></li>'; 
            include 'userbar.php';
        echo '</ul>';
echo '</div><!-- menu -->
        <div id="content">';
?>