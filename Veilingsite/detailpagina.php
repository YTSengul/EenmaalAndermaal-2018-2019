<?php
include_once "components/connect.php";
include_once "components/meta.php";
?>

<body>
    <?php include_once "components/header.php"; ?>
    <div class="grid-container">
        <div class="grid-x grid-margin-x detailpagina">
            <div class="cell">
                <h2>Hier komt de titel van de veiling</h2>
            </div>
            <div class="cell large-7 productdetails">
                <img class='detailfoto' src="img/gouden_schoen.jpg" alt="Foto van een product">
                <div class="spaceBetween">
                    <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
                    <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
                    <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
                    <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
                    <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
                </div>
            </div>
            <div class="cell large-5 detail-biedingen">
                <div class="spaceBetween">
                    <h3>Doe een bod</h3>
                    <h3>00:00</h3>
                </div>
                <hr>
                <div>
                    <p>Hier kunt u bieden. Denk goed na over uw bod. Eenmaal geboden kunt u uw bod niet meer intrekken en
                        bent u verplicht te betalen als u het product wint.</p>
                </div>
                <div>
                    <form class="spaceBetween">
                        <input type="number" placeholder="Vul bedrag in...">
                        <input class="button" type="submit" value="Bieden">
                    </form>
                </div>
                <div class="detail-bedragen">
                    <div class="spaceBetween">
                        <h4>&euro; 0,-</h4>
                        <h5>Username1</h5>
                    </div>
                    <hr>
                    <div class="spaceBetween">
                        <h4>&euro; 0,-</h4>
                        <h5>Username2</h5>
                    </div>
                    <hr>
                    <div class="spaceBetween">
                        <h4>&euro; 0,-</h4>
                        <h5>Username3</h5>
                    </div>
                    <hr>
                    <div class="spaceBetween">
                        <h4>&euro; 0,-</h4>
                        <h5>Username4</h5>
                    </div>
                    <hr>
                    <div class="spaceBetween">
                        <h4>&euro; 0,-</h4>
                        <h5>Username5</h5>
                    </div>
                    <hr>
                </div>
                <div class="detail-aantal">
                    <h4>Aantal biedingen: 0</h4>
                </div>
            </div>
            <div class="cell detailpagina-omschrijving">
                <ul class="tabs" data-tabs id="example-tabs">
                    <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Omschrijving</a></li>
                    <li class="tabs-title"><a href="#panel2">Feedback</a></li>
                    <!--tabs-title zorgt voor de styling-->
                </ul>
                <hr>
                <div class="tabs-content" data-tabs-content="example-tabs">
                    <div class="tabs-panel is-active" id="panel1">
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                            Aenean
                            massa.
                            Cum
                            sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam
                            felis,
                            ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede
                            justo,
                            fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a,
                            venenatis
                            vitae,
                            justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus
                            elementum
                            semper
                            nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae,
                            eleifend
                            ac,
                            enim.
                            Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut
                            metus
                            varius
                            laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper
                            ultricies
                            nisi. Nam eget dui.
                        </p>
                    </div>
                </div>
                <div class="tabs-panel" id="panel2">
                    <p>Yes, sir. I think those new droids are going to work out fine. In fact, I, uh, was also thinking
                        about
                        our agreement about my staying on another season. And if these new droids do work out, I want to
                        transmit my application to the Academy this year. You mean the next semester before harvest? Sure,
                        there're more than enough droids. Harvest is when I need you the most. Only one more season. This
                        year
                        we'll make enough on the harvest so I'll be able to hire some more hands. And then you can go to the
                        Academy next year. You must understand I need you here, Luke. But it's a whole 'nother year. Look,
                        it's
                        only one more season. Yeah, that's what you said last year when Biggs and Tank left. Where are you
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
        </div>
    </div>
</body>

</html>
