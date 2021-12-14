<?php
include "header.php";

$alert = '<script> alert("обиди се повторно")</script>';
$kor_id = $_SESSION['korisnik-id'];
$br=1;
// КРЕИРАЈ РЕЦЕПТ
if (isset($_POST['novRecept'])) {
    $ime = $_POST['imeRecept'];
    $kategorija = $_POST['kategorija'];
    $vremeP = $_POST['vremePodgotovka'];
    $brP = $_POST['brojPorcii'];
    $tags = $_POST['tags'];
    $cekori = $_POST['cekor'];
    $kolicini = $_POST['kolicina'];
    $brs = $_POST['br'];
    
    for ($i = 1; $i <= $brs; $i++) {
        $sostojki[$i - 1] = $_POST['sostojka' . $i . ''];
    
    }

    $targetDir = "uploadfile/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);



    $sql = "INSERT INTO recept (korisnik_id, ime, kategorija, vremeZaPodgotovka, brojPorcii, slika) 
    values ('$kor_id','$ime','$kategorija','$vremeP','$brP','$fileName')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $br = 1;
        $sql = "SELECT LAST_INSERT_ID() as id";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_array();
        $key = $row['id'];
        foreach ($cekori as $kluc => $c) {
            if ($c != "") {
                $sql = "INSERT INTO recept_cekori (broj,opis,recept_id) values('$br','$c','$key')";
                mysqli_query($conn, $sql);
                $br++;
            }
        }
        foreach ($sostojki as $kluc => $c) {
            $sql = "INSERT INTO recept_sostojki (recept_id, sostojka_id, kolicina) values('$key','$c[0]','$kolicini[$kluc]')";
            mysqli_query($conn, $sql);
        }
        foreach ($tags as $kluc => $t) {
            echo $t;
            $sql = "INSERT INTO recept_tags (recept_id,ime) values('$key','$t')";
            mysqli_query($conn, $sql);
        }
        $sql = "CALL presmetaj_nutri('$key')";
        mysqli_query($conn, $sql);


         header("Location: recept_detali.php?id=$key");
    } else echo $alert;
}





// zemi gi site sostojki od tabela sostojki
function zemiSostojki()
{
    global $conn;
    $sql = "SELECT * FROM sostojki";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<option  disabled selected>избери</option>';
        while ($sostojka = $result->fetch_array()) {
            echo '<option value=\'' . $sostojka['id'] . '\'>' . $sostojka['ime'] . ' </option>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nov_recept.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css">
</head>

<body>
    <section class="container">

        <h1>Нов рецепт</h1>
        <!-- КРЕИРАЊЕ НА РЕЦЕПТ -->
        <form action="nov_recept.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <input type="text" name="imeRecept" placeholder="Име на рецепт" required>
            </div>
            <div class="section">
                <div class="input-group group1">
                    <h5>Kатегорија: </h5>
                    <select name="kategorija" id="" class="select-css" required>
                        <option value="" disabled selected>избери</option>
                        <option value="предјадење">предјадење</option>
                        <option value="главно јадење">главно јадење</option>
                        <option value="салати">салати</option>
                        <option value="десерт">десерт</option>
                    </select>
                </div>

                <div class="input-group group1">
                    <h5>Време на подготовка (во минути): </h5>
                    <input type="number" name="vremePodgotovka" placeholder="пр. 5, 10, 20, 60..." min="1">
                </div>

                <div class="input-group group1">
                    <h5>Прикачи слика од рецептот: </h5>
                    <input type="file" class="custom-file-input" accept="image/*" name="file" id="slika" required>
                </div>
                <div class="input-group group1">
                    <h5>Број на порции: </h5>
                    <input type="number" name="brojPorcii" placeholder="пр. 1, 2, .." min="1">
                </div>
            </div>
            <!-- ЧЕКОРИ -->
            <div class="section">
                <h5>Чекори:</h5>
                <div class="input-group" id="cekori">
                    <input type="text" name="cekor[]" placeholder="внеси чекор" required>
                    <!-- <input type="text" name="cekor[]" placeholder="додај чекор">    -->
                </div>

                <button type="button" class="btn" id="dodajCekor"><i class="fa fa-plus"></i> додај чекор</button>
            </div>
            <!-- СОСТОЈКИ   -->
            <div class="section">
                <h5>Состојки:</h5>
                <div class="input-group nmb" id="sostojki">

                    <select name="sostojka1[]" id="" class="select-css" required>
                        <?php
                        zemiSostojki();
                        ?>

                    </select>
                    <input name="kolicina[]" type="number" min="1" required><span>гр</span>
                    <input type="hidden" name="br" value="1" id="br">
                </div>
                <button type="button" class="btn" id="dodajSostojka"><i class="fa fa-plus"></i> додај состојка</button>

            </div>

            <div class="section">
                <h5>Тагови: </h5>
                <div id="tags" class="input-group">

                </div>
                <button type="button" class="btn" id="dodajT"><i class="fa fa-plus"></i> додај таг</button>
            </div>


            <div class="input-group">
                <input type="submit" class="btn2" name="novRecept" value="Креирај нов рецепт">
            </div>
        </form>
    </section>

    <script charset="utf-8">
        //dodaj cekor
        const dodajC = document.getElementById("dodajCekor");
        const cekori = document.getElementById('cekori');
        
        dodajC.addEventListener("click", function() {

            let input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('name', 'cekor[]');
            input.setAttribute('placeholder', 'внеси чекор');
            cekori.appendChild(input);
        });

        //dodaj sostojka
        const dodajS = document.getElementById('dodajSostojka');
        const sostojka = document.getElementById('sostojki');
        const br = document.getElementById('br');
        let broj=1;
        let s="";
        dodajS.addEventListener('click', function() {
            broj++;
            s = "sostojka"+broj+"[]";
            
            br.setAttribute('value', broj);
            let select = document.createElement('select');
            select.setAttribute('name', s);
            select.setAttribute('class', 'select-css');
            select.innerHTML = "<?php zemiSostojki() ?>";
            sostojka.appendChild(select);
            let input = document.createElement('input');
            input.setAttribute('name', 'kolicina[]');
            input.setAttribute('type', 'number');
            input.setAttribute('min', '1');
            let text = document.createElement('span');
            text.innerHTML = "гр";
            sostojka.appendChild(input);
            sostojka.appendChild(text);
        })
        //dodaj T
        const dodajT = document.getElementById('dodajT');
        const tags = document.getElementById('tags');
        dodajT.addEventListener('click', function() {
            let input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('name', 'tags[]');
            input.setAttribute('placeholder', 'внеси таг');
            tags.appendChild(input);
        })
    </script>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>