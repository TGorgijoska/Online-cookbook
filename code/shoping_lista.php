<?php
include "header.php";
$id = $_SESSION['korisnik-id'];
//  ДОДАЈ СОСТОЈКА ВО ЛИСТАТА
if (isset($_POST['dodaj'])) {
    $ime = $_POST['sostojka'];

    $sql = "INSERT INTO shoping_lista (korisnik_id, sostojka) VALUES ('$id','$ime')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo '<script>alert("упс, обиди се повторно")</script>';
    }
}
// ИЗБРИШИ СОСТОЈКА
if(isset($_POST['izbrisiCekor'])){
    $sostojka =  $_POST['s_id'];
    $sql="DELETE FROM shoping_lista WHERE id='$sostojka'";
    mysqli_query($conn,$sql);
}

// ОЗНАЧИ ЗАВРШЕНА
if(isset($_POST['kupeno'])){
    $sostojka=$_POST['s_id'];
    $sql="UPDATE shoping_lista SET kupeno='1' WHERE id='$sostojka'";
    mysqli_query($conn,$sql);
}
// ОЗНАЧИ НЕЗАВРШЕНА
if(isset($_POST['neKupeno'])){
    $sostojka=$_POST['s_id'];
    $sql="UPDATE shoping_lista SET kupeno='0' WHERE id='$sostojka'";
    mysqli_query($conn,$sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shoping_lista.css">
</head>

<body>

    <form action="" method="POST">
        <div class="input-ime">
            <div class="input-group">
                <input type="text" name="sostojka" placeholder="име" required>
            </div>
            <div>
                <input type="submit" name="dodaj" class="btn" value="Додади">
            </div>
        </div>
    </form>

    <hr>

    <div class="koloni">
        <div class="red">
            <!-- ЗА КУПУВАЊЕ -->
            <h1>За купување:</h1>
            <ul>
                <?php
                $sql = "SELECT * FROM shoping_lista WHERE kupeno='0' AND korisnik_id='$id'";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    while ($sostojka = $result->fetch_array()) {
                        echo '
                        <li>
                        <div class="sostojka_lista">
                        <div>' . $sostojka['sostojka'] . '</div>
                        <div>
                            <form action="" method="POST">  
                                <button class="button" name="kupeno" title="купено"><i class="fa fa-check-circle"></i></button>
                                <input type="hidden" name="s_id" value="' . $sostojka['id'] . '">
                                <button class="button" name="izbrisiCekor" title="избриши чекор"><i class="fa fa-trash"></i></button>    
                            </form> 
                        </div>
                        </div>
                        </li>
                        
                        ';
                    }
                }

                ?>
            </ul>
        </div>
        <div class="red">
            <!-- КУПЕНО -->
            <h1>Купено:</h1>
            <ul>
                <?php
                $sql = "SELECT * FROM shoping_lista WHERE kupeno='1' AND korisnik_id='$id'";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    while ($sostojka = $result->fetch_array()) {
                        echo '
                        <li>
                        <div class="sostojka_lista">
                        <div>' . $sostojka['sostojka'] . '</div>
                        <div>
                            <form action="" method="POST">  
                                <button class="button" name="neKupeno" title="не е купено"><i class="fa fa-arrow-left"></i></button>
                                <input type="hidden" name="s_id" value="' . $sostojka['id'] . '">
                                <button class="button" name="izbrisiCekor" title="избриши чекор"><i class="fa fa-trash"></i></button>    
                            </form> 
                        </div>
                        </div>
                        </li>
                        
                        ';
                    }
                }

                ?>
            </ul>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>


</html>