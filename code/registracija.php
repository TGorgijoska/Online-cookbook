<?php
include 'config.php';

error_reporting(0);

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
    $date = date('Y-m-d H:i:s');

    if ($password == $cpassword) {
        $sql = "SELECT * FROM korisnik WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows < 1) {
            $sql = "INSERT INTO korisnik (korisnickoIme, email, lozinka, date_created)
					VALUES ('$username', '$email', '$password', '$date')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                header("Location: najava.php");
                $username = "";
                $email = "";
                $_POST['password'] = "";
                $_POST['cpassword'] = "";
            } else {
                echo "<script>alert('Упс, има некој проблем пробај повторно.')</script>";
            }
        } else {
            echo "<script>alert('Постои профил со избраниот емаил')</script>";
        }
    } else {
        echo "<script>alert('Лозинката не се совпаѓа.')</script>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/najava.css">

    <title>Регистрација</title>
</head>

<body>
    <div class="container">
        <form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Регистрирај се</p>
            <div class="input-group">
                <input type="text" placeholder="Kорисничко име" name="username"  required>
            </div>
            <div class="input-group">
                <input type="email" placeholder="E-маил" name="email" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Лозинка" name="password"  required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Потврди лозинка" name="cpassword"  required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Регистрирај се</button>
            </div>
            <p class="login-register-text">Имаш профил? <a href="najava.php">Логирај се</a>.</p>
        </form>
    </div>
</body>

</html>