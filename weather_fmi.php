<!DOCTYPE html>

<?php

/**
 *
 * Näyttää Suomen Ilmatieteen laitoksen säätiedot RRD Grafiikalla
 *
 * PHP Version 7.2.24
 * RRDtool 1.7.0
 *
 * @category FMI Weather
 * @package  fmiweb
 * @author   Jorma Hytönen <jorma.hytonen@datatuki.net>
 * @license  &copy Datatuki.net
 * @link     https://datatuki.net/server/weather/weather_fmi.php
 * @source   ./Projektit/fmiweather/weather_fmi.php
  */

?>

<html lang="fi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="660"/>
    <title>FMI Säätiedot | Windrose</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="RRD Weather Graph and JpGraph Windrose by Datatuki.fi, Leppävirta, Finland">
    <meta name="keywords" content="Weather,RRDtool,Windrose,JpGraph,HTML,CSS,XML,JavaScript">
    <meta name="author" content="Jorma Hytonen,Datatuki.fi,Datatuki.net">

    <!-- icons -->
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    <link href="favicon.ico" rel="icon" type="image/x-icon" />

    <link href="assets/css/jquery.fancybox.min.css" rel="stylesheet" />
    <script src="assets/js/jquery-1.12.4.min.js"></script>
    <script src="assets/js/jquery.fancybox-fi.min.js"></script>

    <!-- Override CSS file - add your own CSS rules -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="assets/css/fmi_style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/img_style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/fancyimg.css" rel="stylesheet" type="text/css">

    <script type="text/javascript">
        $(document).ready(function() {
            setTimer();

            $(".iframe").fancybox({
                type: 'iframe'
            });
        });

    </script>

</head>

<!--
    Mitä etsit? Parempaa koodia vai uusia ideoita? Ota rohkeasti yhteyttä. Yhteystiedot sivun lopussa
    http://ilmatieteenlaitos.fi/suomen-havainnot?station=101398
-->

<body>

<button onclick="topFunction()" id="backBtn" title="Go to top">
    <img src="assets/images/top-button.png" height="45"/>
</button>

<script>
    //Get the button
    var backButton = document.getElementById("backBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        backButton.style.display = "block";
    } else {
        backButton.style.display = "none";
    }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

<script>
    var IntID = setTimer();

    function setTimer() {
        return setInterval(refreshPage, 60000);
    }

    function stopTimer() {
        clearInterval(IntID);
    }

    function refreshPage() {
        var date = new Date();
        var min = date.getMinutes();
        if( min == 5 || min == 25 || min == 45 ) {
            IntID = setTimer();
            // Force reload the page from the server by setting the forceGet parameter to true
            $('#msg').html("Päivitetään sivua");
            location.reload(true);
        }
        else if( min == 4 || min == 24 || min == 44 ) {
            $('#msg').html("Etsitään uusia mittaustietoja");
        }
        else {
            $('#msg').html("Odotetaan mittausta");
        }
    }
</script>

<div id="weatherData">

<!-- Here is where the server sent data will appear -->

    <div class="header">
        <div class="container">
            <h3>Varkaus Kosulanniemi [FMISID=101421]</h3>
            Tiedot kerätään Ilmatieteen laitoksen avoimen datan verkkopalvelun kautta.<br />
            Tiedonkeruun ohjelmointi:&nbsp;<span style="color: #cccc00">Jorma Hytönen, Datatuki</apan>
        </div>
    </div>

    <div class="nav-bar">
        <div class="container">
            <ul class="nav">
                <li><a href="weather_fmi.php##daily">Päivä</a></li>
                <li><a href="weather_fmi.php##weekly">Viikko</a></li>
                <li><a href="weather_fmi.php##monthly">Kuukausi</a></li>
                <li><a href="weather_fmi.php##yearly">Vuosi</a></li>
                <li><a style="font-size: 12px" href="#D1">Dokumentit</a></li>
                <li><a style="font-size: 12px" href="#F1">Tekijänoikeudet</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="main">
                <div class="sid">Varkaus Kosulanniemi [FMISID=101421]</div>
                <br />

                <div id="dte">
                    Tiedot ladataan Ilmatieteenlaitoksen palvelusta 10 min välein<br />
                    Tulokset ladataan palvelimelle 20 min välein
                    <br /><br />
                    <?php
                    /* There I want the Server datetime */
                    if( !defined('TIMEZONE') ) {
                        define('TIMEZONE', 'Europe/Helsinki');
                        date_default_timezone_set(TIMEZONE);
                    }
                    $realPath = getcwd();
                    $filename = $realPath . "/data/temperature-day.png";
                    if (file_exists($filename)) {
                        echo "Viimeksi päivitetty: " . date("d.m.Y H:i", filemtime($filename));
                    }
                    ?>
                </div>

                <div id="upd">
                    <a style="color:navy;" href="https://ilmatieteenlaitos.fi/suomen-havainnot?station=101421" target="_blank">
                    Varkauden havainnot Ilmatieteenlaitokselta (FMI).</a>
                </div>
                <br />

                <div id="msg">-!-</div>
                <div id="info"> </diV>

                <!-- Responsive images -->

                <p>&nbsp;</p>
                <a name="#daily"> </a>
                <div class="graphtitle">Päivän mittaukset</div>

                <!-- ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== -->
                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/temperature-day.png" alt="Temperature">
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/humid-day.png" alt="Humidity">
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/windy-day.png" alt="Wind"><br />
                        <div class="desc">Tuulen suunta&nbsp;<a data-fancybox="gallery" href="data/windrose.png" /> kompassi </a>&nbsp;(Windrose)
                        </div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/press-day.png" alt="Pressure"><br />
                        <div class="desc">&nbsp;</div>
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/rainy-day.png" alt="Wind">
                        <div class="desc">&nbsp;</div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/weather-day.png" alt="Weather">
                        <div class="desc"><a href="wawacodes.html" class="iframe">Näytä WaWa Koodit</a></div>
                    </div>
                </div>

                <!--  END Daily -->

                <p>&nbsp;</p>
                <a name="#weekly"> </a>
                <div class="graphtitle">Viikon mittaukset</div>

                <!-- ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== -->
                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/temperature-week.png" alt="Temperature">
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/humid-week.png" alt="Humidity">
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/windy-week.png" alt="Wind"><br />
                        <div class="desc">Tuulen suunta&nbsp;<a data-fancybox="gallery" href="data/windrose.png" /> kompassi </a>&nbsp;(Windrose)
                        </div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/press-week.png" alt="Pressure">
                        <div class="desc">&nbsp;</div>
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/rainy-week.png" alt="Wind">
                        <div class="desc">&nbsp;</div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/weather-week.png" alt="Weather">
                        <div class="desc"><a href="wawacodes.html" class="iframe">Näytä WaWa Koodit</a></div>
                    </div>
                </div>

                <!-- END Weekly -->

                <p>&nbsp;</p>
                <a name="#monthly"> </a>
                <div class="graphtitle">Kuukauden mittaukset</div>

                <!-- ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== -->
                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/temperature-month.png" alt="Temperature">
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/humid-month.png" alt="Humidity">
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/windy-month.png" alt="Wind"><br />
                        <div class="desc">Tuulen suunta&nbsp;<a data-fancybox="gallery" href="data/windrose.png" /> kompassi </a>&nbsp;(Windrose)
                        </div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/press-month.png" alt="Pressure">
                        <div class="desc">&nbsp;</div>
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/rainy-month.png" alt="Wind">
                        <div class="desc">&nbsp;</div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/weather-month.png" alt="Weather">
                        <div class="desc"><a href="wawacodes.html" class="iframe">Näytä WaWa Koodit</a></div>
                    </div>
                </div>

                <!--  END Monthly -->

                <p>&nbsp;</p>
                <a name="#yearly"> </a>
                <div class="graphtitle">Vuoden mittaukset</div>

                <!-- ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== ==== -->
                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/temperature-year.png" alt="Temperature">
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/humid-year.png" alt="Humidity">
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/windy-year.png" alt="Wind"><br />
                        <div class="desc">Tuulen suunta&nbsp;<a data-fancybox="gallery" href="data/windrose.png" /> kompassi </a>&nbsp;(Windrose)
                        </div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/press-year.png" alt="Pressure">
                        <div class="desc">&nbsp;</div>
                    </div>
                </div>

                <div class="layout">
                    <div class="layout__item layout__item--figure">
                        <img src="data/rainy-year.png" alt="Wind">
                        <div class="desc">&nbsp;</div>
                    </div>
                    <div class="layout__item layout__item--figure">
                        <img src="data/weather-year.png" alt="Weather">
                        <div class="desc"><a href="wawacodes.html" class="iframe">Näytä WaWa Koodit</a></div>
                    </div>
                </div>

                <!--  END Yearly -->

                <p>&nbsp;</p>
                <div id="D1">
                    <h2>Ilmatieteen laitoksen avoin data ja lähdekoodi</h2>
                    Suuri osa Ilmatieteen laitoksen tietoaineistoista on saatavilla avoimena datana. <br />
                    Tietoaineistot on avattu koneluettavassa, digitaalisessa muodossa.<br />
                    Myös laitoksessa tuotettujen lähdekoodien avaaminen on aloitettu.<br />
                    Ilmatieteen laitoksen avoimen datan verkkopalvelun kautta voi hakea, <br />
                    katsella ja ladata laitoksen tuottamia tietoaineistoja koneluettavassa muodossa maksutta.<br /><br />
                    Lisää Ilmatieteen laitoksen <a href="https://www.ilmatieteenlaitos.fi/avoin-data" target="_blank">avoimesta datasta</a>&nbsp;<font size="-1">(Avautuu uuteen ikkunaan)</font><br />
                    FMI Open Data prosessin kulku löytyy <a href="https://datatuki.net/fmidata/index.html" target="_blank">Datatuki.net</a> palvelimelta.
                    <br /><br />
                    <b>The system (28.4.2021)</b><br />
                    &nbsp;&nbsp;Operating system: Linux<br />
                    &nbsp;&nbsp;Kernel: 5.4.0-72-generic<br />
                    &nbsp;&nbsp;Machine: x86_64 (Ubuntu 20.04.4 LTS Server)<br />
                    &nbsp;&nbsp;Server software: Apache/2.4.41<br />
                    &nbsp;&nbsp;Data collection: Python 3.8.5<br />
                    &nbsp;&nbsp;Graph: RRDtool 1.7.2 <br />
                </div>

            </div>  <!-- END main  -->
        </div>  <!-- END container  -->
    </div>  <!-- END content  -->

    <div class="footer" id="F1">
        Tekijänoikeudet&nbsp;&copy;&nbsp;
        Ilmatieteen laitos&nbsp;<a href="https://ilmatieteenlaitos.fi/avoin-data" target="_blank">Avoin Data</a>&nbsp; | &nbsp;
        Ohjelmointi&nbsp;<a href="http://datatuki.net" target="_blank">Datatuki.net</a>&nbsp; | &nbsp;
        Grafiikka RRDtool 1.7.0 &copy;&nbsp;<a href="https://oss.oetiker.ch/rrdtool/doc/rrdgraph.en.html" target="_blank">Tobi Oetker</a><br />
        Taustakuva&nbsp;<a href ="rawpixel.com">Textured Background</a>&nbsp; | &nbsp; Layout&nbsp;<a href="https://markus.oberlehner.net/blog/creating-a-responsive-alternating-two-column-layout-with-flexbox" target="_blank">Responsive Layout with Flexbox</a>
    </div>

    <!-- Here is where the server sent data will END -->
</div>

</body>
</html>
