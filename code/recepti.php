<?php
include "header.php";
$id = $_SESSION['korisnik-id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/recepti.css">
</head>

<body>
    <div class="input-group">
        <form action="" method="POST">
            <div class="input-group">
                <input type="text" class="input" name="input" placeholder="Пребарај" required>
                <input type="submit" class="btn" name="prebaraj" value="пребарај">
            </div>
        </form>
        <button class="btn" onclick="otstrani()" id="otstani">oтстрани пребарување</button>
    </div>
    <hr>
    <section class="wrapper">
        <ul class="column__list" id="myUL">


            <!-- РЕЦЕПТИ  -->

            <?php

            if (isset($_POST['prebaraj']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                $input = $_POST['input'];
                $sql = "SELECT *   FROM recept_search 
                WHERE NOT korisnik_id='$id' AND ( ime LIKE '%$input%' OR kategorija LIKE '%$input%' OR tag_ime LIKE '%$input%') ";
            } else {
                $sql = "SELECT * FROM recept WHERE NOT korisnik_id='$id' ORDER BY ime ASC";
            }



            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {

                    echo
                    '<a href="recept_detali.php?id=' . $row['id'] . '" title="погледни повеќе"> 
                    <li class="column__item" id="item">
                        <div>
                            <img src="uploadfile/'. $row['slika'] .'" class="recept_slika">        
                        </div>                      
                        <div class="column__title--wrapper ">                            
                            <h1><i class="bx bx-dish"></i> ' . $row['ime'] . ' </h1> 
                        </div>
                        <hr>
                        
                        <div class="detali">
                            <p>  <i class="fa fa-angle-right"></i> ' . $row['kategorija'] . '</p>
                        </div>                    
                        <div class="detali">
                            <p>  <i class="fa fa-angle-right"></i> број на порции: <b>' . $row['brojPorcii'] . '</b></p>  
                        </div>                       
                    </li>
                    </a>';
                }
            }
            ?>

        </ul>
    </section>
    <script>
        function otstrani() {
            location.reload();
            return false;
        }
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>