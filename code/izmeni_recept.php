<?php
include "header.php";
$id = $_GET['id'];
$br = 0;
// recept
$sql = "SELECT * FROM recept WHERE id='$id' ";
$result = mysqli_query($conn, $sql);
$recept = $result->fetch_array();

// ============ FUCNTIONS =================
// pecati tags od tablea recept_tags
function pecatiTags()
{
    global $conn, $id;
    $sql = "SELECT * FROM recept_tags WHERE recept_id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($tag = $result->fetch_array()) {
            echo '<input type="text" name="tags[]" value="' . $tag['ime'] . '" >';
        }
    }
}
// zemi gi site cekori od tabela recept_cekori
function pecatiCekori()
{
    global $conn, $id;
    $sql = "SELECT * FROM recept_cekori WHERE recept_id='$id' ORDER BY broj ASC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($cekor = $result->fetch_array()) {
            echo '<input type="text" name="cekor[]" value="' . $cekor['opis'] . '" >';
        }
    }
}
// zemi gi site sostojki od tabela recept_sostojki
function zemiSostojkiS($s)
{
    global $conn;
    $sql = "SELECT * FROM sostojki";
    $result = mysqli_query($conn, $sql);
    if ($result) {

        while ($sostojka = $result->fetch_array()) {

            if ($s == $sostojka['ime']) {
                echo '<option value=\'' . $sostojka['id'] . '\' selected>' . $sostojka['ime'] . '  </option>';
            } else
                echo '<option value=\'' . $sostojka['id'] . '\'>' . $sostojka['ime'] . ' </option>';
        }
    }
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

        <h1>Измени рецепт</h1>

        <form action="izm_rec.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="receptID" value="<?php echo $id; ?>">
            <div class="input-group">
                <input type="text" name="imeRecept" value="<?php echo $recept['ime']; ?>" required>
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
                    <input type="number" name="vremePodgotovka" value="<?php echo $recept['vremeZaPodgotovka']; ?>" min="1">
                </div>
                <div class="input-group group1">
                    <h5>Број на порции: </h5>
                    <input type="number" name="brojPorcii" value="<?php echo $recept['brojPorcii']; ?>" min="1">
                </div>
            </div>

            <div class="section">
                <h5>Чекори:</h5>
                <div class="input-group" id="cekori">
                    <?php pecatiCekori(); ?>
                    <!-- <input type="text" name="cekor[]" placeholder="додај чекор">    -->
                </div>

                <button type="button" class="btn" id="dodajCekor"><i class="fa fa-plus"></i> додај чекор</button>
            </div>
            <div class="section">
                <h5>Состојки:</h5>
                <div class="input-group nmb" id="sostojki">
                    <?php $sql = "SELECT * FROM sostojki_na_recept WHERE recept_id='$id'";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($sostojka = $result->fetch_array()) {
                            $ime = $sostojka['ime'];
                            $br++;
                            echo '<select name="sostojka' . $br . '[]" id="" class="select-css">';
                            echo '<option value="" selected>избери</option>';
                            zemiSostojkiS($ime);
                            echo '</select>';
                            echo '<input name="kolicina[]" type="number"  min="1" value="' . $sostojka['kolicina'] . '" required><span>гр</span>';
                        }
                    } ?>
                    <input type="hidden" name="br" value="<?php echo $br ?>" id="br">

                </div>
                <button type="button" class="btn" id="dodajSostojka"><i class="fa fa-plus"></i> додај состојка</button>

            </div>

            <div class="section ">
                <h5>Тагови: </h5>
                <div id="tags" class="input-group">
                    <?php pecatiTags(); ?>
                </div>
                <button type="button" class="btn" id="dodajT"><i class="fa fa-plus"></i> додај таг</button>
            </div>


            <div class="input-group">
                <input type="submit" class="btn2" name="izmRecept" value="Измени рецепт">
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
        let broj = <?php echo $br ?>;
        let s = "";
        dodajS.addEventListener('click', function() {
            broj++;
            s = "sostojka" + broj + "[]";

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