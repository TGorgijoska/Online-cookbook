<?php
include "code/config.php";
if (!isset($_SESSION['korisnik-id'])) {
    header("Location: code/welcome.php");
} else header("Location: code/profil.php")
?>