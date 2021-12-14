<?php
include "config.php";

if (isset($_POST['izmRecept'])) {
    $ime = $_POST['imeRecept'];
    $kategorija = $_POST['kategorija'];
    $vremeP = $_POST['vremePodgotovka'];
    $brP = $_POST['brojPorcii'];
    $tags = $_POST['tags'];
    $cekori = $_POST['cekor'];
    $kolicini = $_POST['kolicina'];
    $brs = $_POST['br'];
    $id = $_POST['receptID'];
    echo $id;
    for ($i = 1; $i <= $brs; $i++) {
        $sostojki[$i - 1] = $_POST['sostojka' . $i . ''];
    }
    $sql = "UPDATE recept 
            SET ime='$ime', kategorija='$kategorija', vremeZaPodgotovka='$vremeP', brojPorcii='$brP'
            WHERE recept.id='$id'";
    if (mysqli_query($conn, $sql)) {
        $br = 1; 
        // ЧЕКОРИ=================
        $sql="DELETE FROM recept_cekori WHERE recept_id='$id'" ; 
        mysqli_query($conn, $sql); foreach ($cekori as $kluc=> $c) {
        if ($c != "") {
            $sql = "INSERT INTO recept_cekori (broj,opis,recept_id) values('$br','$c','$id')";
            mysqli_query($conn, $sql);
            $br++;
        }
    }
    // СОСТОЈКИ =================
    $sql = "DELETE FROM recept_sostojki WHERE recept_id='$id'";
    mysqli_query($conn, $sql);
    foreach ($sostojki as $kluc => $c) {
        $sql = "INSERT INTO recept_sostojki (recept_id, sostojka_id, kolicina) values('$id','$c[0]','$kolicini[$kluc]')";
        mysqli_query($conn, $sql);
    }
    // ТАГОВИ =================
    $sql = "DELETE FROM recept_tags WHERE recept_id='$id'";
    mysqli_query($conn, $sql);
    foreach ($tags as $kluc => $t) {
        $sql = "INSERT INTO recept_tags (recept_id,ime) values('$id','$t')";
        mysqli_query($conn, $sql);
    }
    // НУТРИ =================
    $sql = "DELETE FROM recept_nutri WHERE recept_id='$id'";
    mysqli_query($conn, $sql);
    $sql = "CALL presmetaj_nutri('$id')";
    mysqli_query($conn, $sql);
    header("Location: recept_detali.php?id=$id");
}
}
