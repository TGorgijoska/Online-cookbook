<?php
include "config.php";

error_reporting(0);

if (isset($_SESSION['username'])) {
    header("Location: home.php");
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM korisnik WHERE email='$email' AND lozinka='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['korisnik-id'] = $row['id'];
        $_SESSION['username'] = $row['korisnickoIme'];

        $_SESSION['email'] = $row['email'];
        header("Location: profil.php");
    } else {
        echo "<script>alert('Грешка е-маил или лозинка.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Najava</title>
    <link rel="stylesheet" href="css/najava.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="container">
        <form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Логирај се</p>
            <div class="input-group">
                <input type="email" placeholder="Е-маил" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Лозинка" name="password" value="" required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Логирај се</button>
            </div>
            <p class="login-register-text">Немаш профил? <a href="registracija.php">Регистрирај се овде</a>.</p>
        </form>
    </div>


</body>

</html>