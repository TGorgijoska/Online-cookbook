<?php
include "header.php";
$id = $_GET['id'];
$korisnik_id = $_SESSION['korisnik-id'];

if (isset($_POST['izb'])) {
    $sql = "DELETE FROM recept WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: moirecepti.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
    <title></title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/recept_detali.css">
</head>

<body>

    <section class="wrapper">

        <!-- РЕЦЕПТ  -->
        <?php

        $sql = "SELECT * FROM recept WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $recept = $result->fetch_array();
        echo
        '
        <div>
            <img src="uploadfile/' . $recept['slika'] . '" class="recept_slika">
        </div>
        <div class="column__title--wrapper">
            <h1><i class="bx bx-dish"></i> ' . $recept['ime'] . ' </h1>
        </div>
        <hr>
        <div class="detali_list">
        <div class="detali">
            <p> <i class="fa fa-angle-right"></i> ' . $recept['kategorija'] . '</p>
        </div>
        <div class="detali">
            <p> <i class="fa fa-angle-right"></i> број на порции: ' . $recept['brojPorcii'] . '</p>
        </div>
        <div class="detali">
            <p> <i class="fa fa-angle-right"></i> време: ' . $recept['vremeZaPodgotovka'] . 'мин</p>
        </div>
        </div>
        ';
        ?>
        <div class="card_column">
            <div class="card">


                <?php
                //----- CEKORI
                $sqlSostojki = "SELECT * FROM sostojki_na_recept WHERE recept_id='" . $id . "' ";
                $sostojki = mysqli_query($conn, $sqlSostojki);
                if ($sostojki->num_rows > 0) {
                    echo '
                    <div class="cekori_lista" >
                    <ul>';
                    while ($sostojka = $sostojki->fetch_array()) {
                        echo '
                    <li class="cekor">
                        <i class="fa fa-angle-right"></i> ' . $sostojka['ime'] . ' ' . $sostojka['kolicina'] . ' гр.
                    </li>';
                    }
                    echo
                    '</ul>
                    </div>';
                }
                ?>
            </div>
            <div class="card">
                <?php
                //----- CEKORI
                $sqlCekori = "SELECT * FROM recept_cekori WHERE recept_id='" . $id . "' ORDER BY broj ASC";
                $cekori = mysqli_query($conn, $sqlCekori);
                if ($cekori->num_rows > 0) {
                    echo '
                    <div class="cekori_lista" >
                        <ol>';
                    while ($cekor = $cekori->fetch_array()) {
                        echo '
                            <li class="cekor">
                                ' . $cekor['opis'] . '
                            </li>';
                    }
                    echo '
                        </ol>
                    </div>';
                }

                ?>
            </div>
        </div>

        <?php
        $sql = "SELECT * FROM recept_nutri WHERE recept_id ='" . $id . "' ";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_array();
        $kcal = $row['jaglehidrati'] * 4 + $row['proteini'] * 4 + $row['masti'] * 9;
        echo '
        <div class="nutri">
            <p>калории:  ' . $kcal . ' </p>
            <p>/</p>
            <p> јаглехидрати:  ' . $row['jaglehidrati'] . 'гр </p>
            <p>/</p>
            <p> протеини:   ' . $row['proteini'] . 'гр </p>
            <p>/</p>
            <p> масти:   ' . $row['masti'] . 'гр </p>
        </div>
        ';

        $sql = "SELECT * FROM recept_tags WHERE recept_id='" . $id . "'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo '<div class="tags">
            <p> тагови:</p>';
            while ($tags = $result->fetch_array()) {
                echo ' <p> ' . $tags['ime'] . ' </p> ';
            }
            echo '</div>';
        }

        if ($korisnik_id == $recept['korisnik_id']) {
            echo '
            <hr>
            <div class="input-group">
            <form action="" method="POST">
                <div>
                    <input class="btn" type="submit" name="izb" value="избриши рецепт">
                </div>
            </form>
            <button class="btn" id="myButton">измени рецепт</button>
          
        </div>
            ';
        }



        ?>

        <!--   <form action="izmeni_recept.php?id=' . $id . '" method="POST">
                <div>
                    <input class="btn" type="submit" name="izmeni" value="измени рецепт">
                </div>
            </form>  -->

    </section>

</body>
<script>
    document.getElementById("myButton").onclick = function() {
        location.href = "izmeni_recept.php?id=<?php echo $id ?>";
    };
</script>

</html>