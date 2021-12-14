<?php
include "config.php";

function zemiKorInformacii($idBaza, $podatok ){
    global $conn;
    $sql = "SELECT * FROM korisnik WHERE $idBaza ='$podatok'";
    $result = mysqli_query($conn, $sql);
    return $result;
}
