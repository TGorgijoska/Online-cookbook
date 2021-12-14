<?php
if (isset($_SESSION['korisnik-id'])) {
    header("Location: profil.php");
}
include "header.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<style>
    .container {
        margin: 30px 50px;
        display: grid;
        justify-items: center;
    }
    .container >img{
        width: 100%;
    }
    .container>h3{
        padding:10px 10px;
    }

    
</style>

<body>
    <div class="container">
        <h3>ДОБРЕДОЈДЕ НА МОИРЕЦЕПТИ</h3>
        <img src="images/wel.png" alt="">
    </div>


</body>
<?php
include "footer.php";
?>

</html>