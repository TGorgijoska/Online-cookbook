<?php
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="" content="">
    <title></title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <nav class="navbar">
        <div class="navbar__container">

            <div class="logo">
                <img src="images/logohat.png" alt="logo">
                <span>МоиРецепти</span>
            </div>
            <ul class="nav-links">
                <div class="icon cancel-btn">
                    <img src="images/fork.png" alt="fork">
                </div>

                <?php
                if (isset($_SESSION['korisnik-id'])) {
                    echo '<li><a href="moirecepti.php">Мои рецепти</a></li>';
                    echo '<li><a href="./nov_recept.php">Креирај рецепт</a></li>';
                    echo '<li><a href="recepti.php">Рецепти</a></li>';
                    echo '<li><a href="shoping_lista.php">Шопинг листа</a></li> <br>';
                    echo '<li><a href="./profil.php"><button class="header-btn">Профил</button></a></li>';
                } else {
                    echo '<li><a href="./welcome.php">Добредојде</a</li>';
                    echo '<li><a class="cta" href="./najava.php"><button class="header-btn">Најава</button></a></li>';
                }
                ?>
            </ul>
            <div class="icon menu-btn">
                <img src="images/burger.png" alt="">
            </div>

        </div>
    </nav>

    <script>
        const body = document.querySelector("body");
        const navbar = document.querySelector(".navbar");
        const menuBtn = document.querySelector(".menu-btn");
        const cancelBtn = document.querySelector(".cancel-btn");
        menuBtn.onclick = () => {
            navbar.classList.add("show");
            menuBtn.classList.add("hide");
            body.classList.add("disabled");
        }
        cancelBtn.onclick = () => {
            body.classList.remove("disabled");
            navbar.classList.remove("show");
            menuBtn.classList.remove("hide");
        }
        window.onscroll = () => {
            this.scrollY > 20 ? navbar.classList.add("sticky") : navbar.classList.remove("sticky");
        }
    </script>


</body>

</html>