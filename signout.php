<?php
// signout.php - stopper sesjonen, disconencter fra server og direkter til index.php

include 'funksjoner.php';
$conn = connect();

session_start();
session_destroy();
disconnect($conn);
home();

?>