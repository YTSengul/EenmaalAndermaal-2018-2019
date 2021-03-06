<?php
include_once "components/connect.php";
include_once 'components/meta.php';
if ($_GET["registratie"] = !null) {
    $new_registered = $_GET["registratie"];
}

$valid = 0;
$invalid = 1;
$login_verification = $valid;

$mail_in_gebruik = false;
if(isset($_GET['mailadres'])){
    $mail_in_gebruik = true;
}

if (isset($_POST["login"])) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $sql_login_check_query = "select * from gebruiker where gebruikersnaam = '$gebruikersnaam'";
    $sql_login_check = $dbh->prepare($sql_login_check_query);
    $sql_login_check->execute();
    $sql_login_data = $sql_login_check->fetchAll(PDO::FETCH_ASSOC);

    if ($sql_login_check->rowCount() > 0) {
        $gehashed_wachtwoord = $sql_login_data[0]['Wachtwoord'];
        if (password_verify($wachtwoord, $gehashed_wachtwoord)) {
            $_SESSION['ingelogde_gebruiker'] = $gebruikersnaam;
            header('location:index.php');
        } else if ($wachtwoord == $sql_login_data[0]['Wachtwoord']){
            $_SESSION['ingelogde_gebruiker'] = $gebruikersnaam;
            header('location:index.php');
        }
        else {
            $login_verification = $invalid;
        }
    } else {
        $login_verification = $invalid;
    }
} ?>

<body>
<?php include_once 'components/header.php'; ?>
<div class="grid-container">


    <div class='grid-container grid-x'>

        <div class="cell large-5 float-center">
            <h5>Inloggen</h5>
            <form action="#" method="POST">
                <label>Gebruikersnaam* </label>
                <input <?php if ($login_verification == $invalid) {
                    echo 'class="is-invalid-input"';
                } ?> type='text' name="gebruikersnaam" placeholder='Gebruikersnaam' value="<?PHP if(isset($gebruikersnaam)){echo $gebruikersnaam;} ?>" >
                <label>Wachtwoord* </label>
                <input <?php if ($login_verification == $invalid) {
                    echo 'class="is-invalid-input"';
                } ?> type='password' name="wachtwoord" placeholder='Wachtwoord'>
                <?php if ($login_verification == $invalid) {
                    echo '<span class="form-error is-visible" id="exemple2Error">Gebruikersnaam/wachtwoord is onjuist.</span>';
                } ?>
                <span>* Deze velden zijn verplicht.</span> <br> <br>
                <input type="submit" value="Login" name="login" class="button expanded float-right">
            </form>

            <?PHP if($mail_in_gebruik) { echo "<p>Er staat al een account geregistreerd op deze emailadres.</p>"; } ?>

        </div>

    </div>

    <?php include "components/scripts.html"; ?>

</div>
</body>
</html>