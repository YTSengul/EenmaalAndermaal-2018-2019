<?php
include_once "components/connect.php";
include_once "components/meta.php";

if (isset($_POST['verstuur_bod'])) {

    if (isset($_SESSION['ingelogde_gebruiker'])) {

        $_GET['Voorwerpnummer'] = $_POST['voorwerpnummer_hidden'];
        $bod = $_POST['bod'];

        $check_bod_query = "select * from bod where voorwerp = " . $_GET['Voorwerpnummer'] . " ORDER BY 1 DESC";
        $check_bod = $dbh->prepare($check_bod_query);
        $check_bod->execute();
        $check = $check_bod->fetchAll(PDO::FETCH_NUM);
        if ($check != null) {
            $laatste_bod = $check[0][0];
        }
        $bieding_juist = 0;
        $min_bedrag = 0;
        //echo '<br>';
        //var_dump($check);
        //echo '<br>';
        if ($check != null) {
            if ($laatste_bod < 49.99 & $bod >= $laatste_bod + 0.5) {
                $bieding_juist = 1;
            } else if ($laatste_bod < 499.99 & $laatste_bod >= 50 & $bod >= $laatste_bod + 1) {
                $bieding_juist = 1;
            } else if ($laatste_bod < 999.99 & $laatste_bod >= 500 & $bod >= $laatste_bod + 5) {
                $bieding_juist = 1;
            } else if ($laatste_bod < 4999.99 & $laatste_bod >= 1000 & $bod >= $laatste_bod + 10) {
                $bieding_juist = 1;
            } else if ($laatste_bod >= 5000 & $bod >= $laatste_bod + 50) {
                $bieding_juist = 1;
            }

            if ($laatste_bod < 49.99) {
                $min_bedrag = 0.50;
            } else if ($laatste_bod < 499.99 & $laatste_bod >= 50) {
                $min_bedrag = 1;
            } else if ($laatste_bod < 999.99 & $laatste_bod >= 500) {
                $min_bedrag = 5;
            } else if ($laatste_bod < 4999.99 & $laatste_bod >= 1000) {
                $min_bedrag = 10;
            } else if ($laatste_bod >= 5000) {
                $min_bedrag = 50;
            }
            if ($bieding_juist == 1) {
                $gebruikersnaam = $_SESSION['ingelogde_gebruiker'];
                $verstuur_bod_query = "insert into bod values ($bod," . $_GET['Voorwerpnummer'] . ",'$gebruikersnaam','" . date('Y-m-d H:s:i') . "')";
                $verstuur_bod = $dbh->prepare($verstuur_bod_query);
                $verstuur_bod->execute();
            } else {
                echo "Je moet hoger bieden!! De minimum verhoging is: € $min_bedrag";
            }
        }

        if ($bod <= 1 & $check == null) {
            echo "Je moet hoger bieden!! De beginbedrag is €1!!";
        } else if ($bod > 1 & $check == null) {
            $bieding_juist = 1;
            if ($bieding_juist == 1) {
                $gebruikersnaam = $_SESSION['ingelogde_gebruiker'];
                $verstuur_bod_query = "insert into bod values ($bod," . $_GET['Voorwerpnummer'] . ",'$gebruikersnaam','" . date('Y-m-d H:s:i') . "')";
                $verstuur_bod = $dbh->prepare($verstuur_bod_query);
                $verstuur_bod->execute();
                echo 'aaa';
            } else {
                echo "Je moet hoger bieden!! De minimum verhoging is: € $min_bedrag";
            }
        }


    } else if (!isset($_SESSION['ingelogde_gebruiker'])) {
        header('Location:pre-registreer.php');
    }
}

//Als er geen Voorwerpnummer wordt meegegeven in de header (wat meestal betekent dat de user zelf probeert om via de URL de pagina te bereiken) kan de pagina niet correct geladen worden en wordt de user teruggestuurd naar de homepage.
if (!isset($_GET['Voorwerpnummer'])) {
    header('location: index.php');
}

//Prepared statement voor de productinformatie
$detailsAuction = $dbh->prepare("SELECT Titel, Beschrijving, EindMoment, Startprijs FROM Voorwerp WHERE Voorwerpnummer = ?");
$detailsAuction->execute([$_GET['Voorwerpnummer']]);
$resultAuction = $detailsAuction->fetch();

$title = $resultAuction['Titel'];
$description = $resultAuction['Beschrijving'];
$endTime = $resultAuction['EindMoment'];
$startprijs = $resultAuction['Startprijs'];

//Geeft variabele door aan productomschrijving in iFrame
$_SESSION['beschrijving'] = $description;

//Prepared statement voor de images
$pictureAuction = $dbh->prepare("SELECT Filenaam FROM Bestand WHERE Voorwerp = ?");
$pictureAuction->execute([$_GET['Voorwerpnummer']]);
$pictureAuctionResult = $pictureAuction->fetchAll();

//Prepared statement voor de biedingen
$topFiveBids = $dbh->prepare("SELECT TOP 4 Bodbedrag, Gebruikersnaam FROM Bod WHERE Voorwerp = ? ORDER BY Bodbedrag DESC");
$topFiveBids->execute([$_GET['Voorwerpnummer']]);
$resultTopFiveBids = $topFiveBids->fetchAll();

// Hier wordt de eerste bieding op de veiling uit de database genomen
$eerstebieding_query = "SELECT Bodbedrag, Gebruikersnaam FROM Bod WHERE Voorwerp = ? ORDER BY Bodbedrag ASC";
$eerstebieding_data = $dbh->prepare($eerstebieding_query);
$eerstebieding_data->execute([$_GET['Voorwerpnummer']]);
$eerstebieding = $eerstebieding_data->fetchAll();

//Prepared statement voor het aantal biedingen: Later samenvoegen met statement hierboven?
$amountBidsAuction = $dbh->prepare("SELECT COUNT(Voorwerp) FROM Bod WHERE Voorwerp = ? GROUP BY Voorwerp");
$amountBidsAuction->execute([$_GET['Voorwerpnummer']]);
$resultAmountBidsAuction = $amountBidsAuction->fetch();

$finalAmountBidsAuction = $resultAmountBidsAuction[0];

//Functie die de top 5 biedingen toont met username en bedrag
$first = true;
function echoBedragen($resultTopFiveBids, $eerstebieding, $startprijs)
{
    global $first;
    foreach ($resultTopFiveBids as $bidData) {
        if ($first == true) {
            echo '<div class="spaceBetween"><h4><b>&euro;' . $bidData[0] . '</h4><h5>' . $bidData[1] . '</b></h5></div><hr>';
            $first = false;
        } else {
            echo '<div class="spaceBetween"><h4>&euro;' . $bidData[0] . '</h4><h5>' . $bidData[1] . '</h5></div><hr>';
        }
    }
    if ($eerstebieding != null & sizeof($eerstebieding) > 4) {
        echo '<div class="spaceBetween"><h5>Startprijs: €' . $startprijs . '</h5><h5>Eerste bod: €' . $eerstebieding[0][0] . '</h5></div><hr>';
    }
}

//Functie die de hoofdfoto toont die bij een veiling hoort
function echoMainpicture($pictureAuctionResult)
{
    $mainPictureArray = $pictureAuctionResult[0];
    $mainPicture = $mainPictureArray[0];
    echo "<img class='detailfoto' src='http://iproject4.icasites.nl/pics/$mainPicture' alt='Foto van een product'>";
}

//Functie die de subfoto's toont die bij een veiling horen
function echoSubpictures($pictureAuctionResult)
{
    foreach ($pictureAuctionResult as $picture) {
        echo "<img class='detailsubfoto' src='http://iproject4.icasites.nl/pics/$picture[0]' alt='Subfoto van een product'>";
    }
}

?>

<?PHP

// hier zijn de breadcrumbs in te vinden
function create_Breadcrumbs($RubriekNummer)
{
    global $dbh;
    $vind_hoofdrubriek_query = "select * from Rubriek where RubriekNummer = $RubriekNummer";
    $vind_hoofdrubriek_data = $dbh->prepare($vind_hoofdrubriek_query);
    $vind_hoofdrubriek_data->execute();
    $vind_hoofdrubriek = $vind_hoofdrubriek_data->fetchAll(PDO::FETCH_NUM);
    $hoofdrubriek = $vind_hoofdrubriek[0];
    return $hoofdrubriek;
}

// hier wordt de array van de breadcrumb gemaakt
$breadcrumbs_namen = array();
$breadcrumbs_nummers = array();
function call_Breadcrumbs($filter_rubriek, &$breadcrumbs_namen, &$breadcrumbs_nummers)
{
    $rubriek_voor_hoofdcategorie = NULL;
    if ($filter_rubriek != -1) {
        for ($x = 0; $rubriek_voor_hoofdcategorie[2] != -1; $x++) {
            if ($rubriek_voor_hoofdcategorie != NULL) {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($rubriek_voor_hoofdcategorie[2]);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            } else {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($filter_rubriek);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            }
        }
    }
    sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers);
}

//hier worden de breadcrumbs op rij gezet en geshowed dit wordt gedaan om het van achter naar voor te sorteren

function sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers)
{
    echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=-1'>Hoofdrubrieken</a></li>";
    for ($x = sizeof($breadcrumbs_namen) - 1; $x >= 0; $x--) {
        echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=" . $breadcrumbs_nummers[$x] . "'>$breadcrumbs_namen[$x]</a></li>";
    }
}

$detailsAuction_bc = $dbh->prepare("SELECT rubriekoplaagsteniveau FROM Voorwerpinrubriek WHERE Voorwerp = " . $_GET['Voorwerpnummer']);
$detailsAuction_bc->execute();
$resultAuction_bc = $detailsAuction_bc->fetch();

?>


<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">
    <div class="grid-x grid-margin-x detailpagina">
        <div class="medium-12 large-12 float-center cell">
            <!--- Breadcrumbs -->
            <nav aria-label="You are here:" role="navigation" class="veilingen-breadcrumbs">
                <ul class="breadcrumbs">
                    <?php call_Breadcrumbs($resultAuction_bc[0], $breadcrumbs_namen, $breadcrumbs_nummers); ?>
                    <!---<li>
                        <span class="show-for-sr">Current: </span> Huidige cat.
                    </li>-->
                </ul>
            </nav>

            <!---<select class="float-right veilingen-filter-hoofd ">
                <option>Optie 1</option>
                <option>Optie 2</option>
                <option>Optie 3</option>
                <option>Optie 4</option>
                <option>Optie 5</option>
            </select>-->

        </div>
        <div clas
        <div class="cell">
            <h2><?php echo $title ?></h2>
        </div>
        <div class="cell large-7 productdetails flexColumn">
            <!--Note to self: Inladen foto testen op de server: replacement inladen bij error-->
            <!--Note to self: Nog implementeren dat bij klik op subfoto dat de hoofdfoto wordt-->
            <?php echoMainpicture($pictureAuctionResult) ?>
            <div class="spaceAround marginTopAuto">
                <?php echoSubpictures($pictureAuctionResult) ?>
            </div>
        </div>
        <div class="cell large-5 detail-biedingen">
            <div class="spaceBetween">
                <h3>Doe een bod</h3>
                <h4 class="detail-timer" id="timer"></h4>
            </div>
            <hr>
            <div>
                <p>Hier kunt u bieden. Denk goed na over uw bod. Eenmaal geboden kunt u uw bod niet meer intrekken en
                    bent u verplicht te betalen als u het product wint.</p>
            </div>
            <div>
                <form class="spaceBetween" method="POST">
                    <input type="text" placeholder="Vul bedrag in..." name="bod">
                    <input type="hidden" name="voorwerpnummer_hidden" value="<?PHP echo $_GET['Voorwerpnummer']; ?>">
                    <input class="button" type="submit" value="Bieden" name="verstuur_bod">
                    <!--Note to self: Op mobielschermpjes loopt knop het scherm nog uit-->
                </form>
            </div>
            <div class="detail-bedragen">
                <?php echoBedragen($resultTopFiveBids, $eerstebieding, $startprijs) ?>
            </div>
            <div class="detail-aantal">
                <h4>Aantal
                    biedingen: <?php echo $finalAmountBidsAuction > 0 || $finalAmountBidsAuction !== null ? $finalAmountBidsAuction : "0" ?></h4>
            </div>
        </div>
        <div class="cell detailpagina-omschrijving">
            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Omschrijving</a></li>
                <li class="tabs-title"><a href="#panel2">Feedback</a></li>
            </ul>
            <hr>
            <div class="tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="panel1">
                    <iframe src="components/productomschrijving.php" class="detailpagina_iframe">
                    </iframe>
                    <div class="tabs-panel" id="panel2"> <!--Note: Iemand moet dit nog werkend maken-->
                        <p>Yes, sir. I think those new droids are going to work out fine. In fact, I, uh, was also
                            thinking
                            about
                            our agreement about my staying on another season. And if these new droids do work out, I
                            want to
                            transmit my application to the Academy this year. You mean the next semester before harvest?
                            Sure,
                            there're more than enough droids. Harvest is when I need you the most. Only one more season.
                            This
                            year
                            we'll make enough on the harvest so I'll be able to hire some more hands. And then you can
                            go to the
                            Academy next year. You must understand I need you here, Luke. But it's a whole 'nother year.
                            Look,
                            it's
                            only one more season. Yeah, that's what you said last year when Biggs and Tank left. Where
                            are you
                            going? It looks like I'm going nowhere. I have to finish cleaning those droids.
                        </p>
                    </div>
                </div>
                <?php include "components/scripts.html"; ?>
                <!--    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>-->
                <!--    <script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>-->
                <!--    <script>-->
                <!--        $(document).foundation();-->
                <!--    </script>-->
                <script>
                    var countdownDate = new Date("<?php echo $endTime; ?>").getTime();
                    var interval = setInterval(function () {
                        var now = new Date().getTime();
                        var distance = countdownDate - now;
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        document.getElementById("timer").innerHTML = days + "d " + hours + "h "
                            + minutes + "m " + seconds + "s ";
                        if (distance < 0) {
                            clearInterval(interval);
                            document.getElementById("timer").innerHTML = "Veiling beëindigd";
                        }
                    }, 1000);
                </script>
            </div>
        </div>
</body>

</html>
