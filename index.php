<?php
session_start();
include "./includes/dbh.php";
$user = $_SESSION['user'];
echo $user;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/67e995782d.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./style.css">
    <style media="screen">
    </style>
    <title></title>
    <style media="screen">
      html {
        scroll-behavior: smooth;
      }
    </style>
  </head>
  <?php
    $desert = include './icons/desert.php';
   ?>



    <?php

    // gegevens 24 uur


    $gegevens = [['datum', 'temperatuur']]; // Een array in een array
    $startdatum = '';
    // Laatste 20 gegevens ophalen
    include "./includes/dbh.php";
    $sql = "SELECT * FROM `moistureSensor` LIMIT 20";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
      // De resultaten toevoegen aan de bestaande array ($gegevens)
      // Kies misschien wel voor de kolom waarde in je db een andere naam
      // De waarde datum bestaat uit datum en tijd, we splitsen deze met explode
      // Dit geeft ons een array terug met als eerste element de datum en tweede
      // argument de tijd. We gaan enkel de tijd tonen in onze grafiek.
      $datumArray = explode(" ", $row['datum']);
      $datum = $datumArray[0];
      $tijd = $datumArray[1];
      // We kunnen wel de startdatum en eindatum van de getoonde metingen tonen
      // Hiervoor kijken we eerst of de eindatum verschillend is van de startdatum
      // Als de startdatum nog geen waarde heeft, dan geven we hem de waarde van de datum
      if (!$startdatum) {
        $startdatum = $datum;
      }
      // Als de startdatum niet gelijk is aan de datum, dan hebben we een nieuwe einddatum
      if ($startdatum !== $datum) {
        $einddatum = $datum;
      }
      $temperatuur = floatval($row['waarde']);
      array_push($gegevens, [$tijd, $temperatuur]);
      }
      // PHP array omvormen naar js array
      $gegevens = json_encode($gegevens);

      // Gegevens week

      $gegevens2 = [['datum', 'temperatuur']]; // Een array in een array
      $startdatum2 = '';
      // Laatste 20 gegevens ophalen
      include "./includes/dbh.php";
      $sql = "SELECT * FROM `archiveMoistureSensor` LIMIT 20";
      $result = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_array($result)) {
        // De resultaten toevoegen aan de bestaande array ($gegevens)
        // Kies misschien wel voor de kolom waarde in je db een andere naam
        // De waarde datum bestaat uit datum en tijd, we splitsen deze met explode
        // Dit geeft ons een array terug met als eerste element de datum en tweede
        // argument de tijd. We gaan enkel de tijd tonen in onze grafiek.
        $datumArray2 = explode(" ", $row['datum']);
        $datum2 = $datumArray2[0];
        $tijd2 = $datumArray2[1];
        // We kunnen wel de startdatum en eindatum van de getoonde metingen tonen
        // Hiervoor kijken we eerst of de eindatum verschillend is van de startdatum
        // Als de startdatum nog geen waarde heeft, dan geven we hem de waarde van de datum
        if (!$startdatum2) {
          $startdatum2 = $datum2;
        }
        // Als de startdatum niet gelijk is aan de datum, dan hebben we een nieuwe einddatum
        if ($startdatum2 !== $datum2) {
          $einddatum2 = $datum2;
        }
        $temperatuur2 = floatval($row['waarde']);
        array_push($gegevens2, [$tijd2, $temperatuur2]);
        }
        // PHP array omvormen naar js array
        $gegevens2 = json_encode($gegevens2);
     ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <!-- <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data1 = google.visualization.arrayToDataTable(<?php echo $gegevens ?>);
        var options1 = {
          curveType: 'function',
          backgroundColor: '#f9f9f9',
        };

        var chart2 = new google.visualization.LineChart(document.getElementById('curve_chart2'));

        chart2.draw(data1, options1);
      }
    </script> -->

  <body>
    <div class="container1">
      <div class="overlay"></div>
      <div class="innercontainer">
        <h1 style="font-size: 120px">Agritower</h1>
        <h1>Toren <select><option>#1</option><option>#2</option><option>#3</option></select></h1>
        <p>Welkom op het de webpagina van de AgriTower. Klik op de nummer hiervboven om een andere toren te selecteren.</p>
        <form id="loginForm"action="" method="POST">
          <?php
            if ($user) {
              ?>
              <button type="button" name="button" onclick="logout()">logout</button>
              <?php
            } else {
              ?>
              <input type="text" placeholder="Username" name="username">
              <input type="password" placeholder="Password" name="password">
              <button type="submit">Login</button>
              <?php
            }
           ?>
        </form>
      </div>
    </div>
    <div class="container2">
      <a href="#kleurenDiv">
        <div class="navigationButton first">
          <i class="far fa-lightbulb"></i>
          <p>Kleuren</p>
        </div>
      </a>

    <a href="#vochtigheidDiv">
      <div class="navigationButton last">
        <i class="fas fa-tint"></i>
        <p>Vochtigheid</p>
      </div>
    </a><a href="#temperatuurDiv">
      <div class="navigationButton">
        <i class="fas fa-temperature-low"></i>
        <p>Temperatuur</p>
      </div></a><div class="navigationButton last">
        <i class="fas fa-film"></i>
        <p>Video</p>
      </div>
    </div>
    <div class="container3" id="kleurenDiv">
      <h2>Kleuren</h2>
      <p>Bekijk hieronder de laatste wijzigingen van de led-statussen.</p>
      <div class="kleurenContainer">
          <?php
        $sql = "SELECT * FROM `outputs` WHERE `name` = 'LED1'";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        $status = '';
        while($row = mysqli_fetch_array($result)) {
          if ($row['status'] == '0') {
            $status = '';
          } else {
            $status = 'checked';
          }
        }
          ?>
          <div class="kleurenTitel titleDarkRed">Donker rood
                    <?php if ($user): ?>
                      <label class="switch">
            <input onchange="changeLightStatus('LED1')" id="lightStatusLED1"  name="lightStatusLED1" type="checkbox" <?php echo $status ?>>
            <span class="slider darkRed round"></span></label>
                    <?php endif; ?>
          </div>
        <table>
          <thead>
            <tr>
              <th>Tijd</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php

                $sql = "SELECT * FROM `archiveOutputStatus` WHERE `output` = 'LED1'";
                mysqli_set_charset($conn, "utf8");
                $result = mysqli_query($conn, $sql);
                $numberOfRows = mysqli_num_rows($result);
                // $numberOfRows = mysqli_num_rows($result);
                // echo $numberOfRows;
                $x = 0;
                $div = 1;
                while ($row = mysqli_fetch_array($result)) {
                  $waarde = boolval($row['waarde']);
                  if ($waarde) {
                    $showIcon = '<i class="fas fa-sun"></i>';
                  } else {
                    $showIcon = '<i class="fas fa-moon"></i>';
                  }
                  $div = floor($x / 10) + 1;
                  if ($div == 1) {
                    $displaynone = 'table-row';
                  } else {
                    $displaynone ='none';
                  }
                  ?>
                  <tr style="display:<?php echo $displaynone ?>" class="kleurdonkerrood-<?php echo $div ?>">
                    <td><?php echo $row['datum'] ?></td>
                    <td class="kleurenIcon"><?php echo $showIcon ?></td>
                  </tr>
                  <?php
                  $x += 1;
                }
             ?>
          </tr>
          </tbody>
        </table>
        <div class="kleurenNav">
          <button type="button" name="button" onclick="kleurendonkerrood('Left')"><i class="fas fa-chevron-left"></i></button>
          <input type="text" name="" id="kleurendonkerrood" value="1">
          <p>  / <?php echo $div ?></p>
          <button type="button" name="button" onclick="kleurendonkerrood('Right')"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
      <div class="kleurenContainer">
          <?php
        $sql = "SELECT * FROM `outputs` WHERE `name` = 'LED2'";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        $status = '';
        while($row = mysqli_fetch_array($result)) {
          if ($row['status'] == '0') {
            $status = '';
          } else {
            $status = 'checked';
          }
        }
          ?>
          <div class="kleurenTitel titleDarkRed">Rood
                    <?php if ($user): ?>
                  <label class="switch">
            <input onchange="changeLightStatus('LED2')" id="lightStatusLED2"  name="lightStatusLED2" type="checkbox" <?php echo $status ?>>
            <span class="slider darkRed round"></span></label>
                    <?php endif; ?>
          </div>
        <table>
          <thead>
            <tr>
              <th>Tijd</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php

                $sql = "SELECT * FROM `archiveOutputStatus` WHERE `output` = 'LED2'";
                mysqli_set_charset($conn, "utf8");
                $result = mysqli_query($conn, $sql);
                $numberOfRows = mysqli_num_rows($result);
                $x = 0;
                $div = 1;
                while ($row = mysqli_fetch_array($result)) {
                  $waarde = boolval($row['waarde']);
                  if ($waarde) {
                    $showIcon = '<i class="fas fa-sun"></i>';
                  } else {
                    $showIcon = '<i class="fas fa-moon"></i>';
                  }
                  $div = floor($x / 10) + 1;
                  if ($div == 1) {
                    $displaynone = 'table-row';
                  } else {
                    $displaynone ='none';
                  }
                  ?>
                  <tr style="display:<?php echo $displaynone ?>" class="kleurrood-<?php echo $div ?>">
                    <td><?php echo $row['datum'] ?></td>
                    <td class="kleurenIcon"><?php echo $showIcon ?></td>
                  </tr>
                  <?php
                  $x += 1;
                }
             ?>
          </tr>
          </tbody>
        </table>
        <div class="kleurenNav">
          <button type="button" name="button" onclick="kleurenrood('Left')"><i class="fas fa-chevron-left"></i></button>
          <input type="text" name="" id="kleurenrood" value="1">
          <p>  / <?php echo $div ?></p>
          <button type="button" name="button" onclick="kleurenrood('Right')"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
      <div class="kleurenContainer">
          <?php
        $sql = "SELECT * FROM `outputs` WHERE `name` = 'LED3'";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        $status = '';
        while($row = mysqli_fetch_array($result)) {
          if ($row['status'] == '0') {
            $status = '';
          } else {
            $status = 'checked';
          }
        }
          ?>
          <div class="kleurenTitel titleBlue">Blauw
              <?php if ($user): ?>
            <label class="switch">
            <input onchange="changeLightStatus('LED3')" id="lightStatusLED3"  name="lightStatusLED3" type="checkbox" <?php echo $status ?>>
            <span class="slider Blue round"></span>  </label>
                    <?php endif; ?>
        </div>
        <table>
          <thead>
            <tr>
              <th>Tijd</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php

                $sql = "SELECT * FROM `archiveOutputStatus` WHERE `output` = 'LED3'";
                mysqli_set_charset($conn, "utf8");
                $result = mysqli_query($conn, $sql);
                $numberOfRows = mysqli_num_rows($result);
                $x = 0;
                $div = 1;
                while ($row = mysqli_fetch_array($result)) {
                  $waarde = boolval($row['waarde']);
                  if ($waarde) {
                    $showIcon = '<i class="fas fa-sun"></i>';
                  } else {
                    $showIcon = '<i class="fas fa-moon"></i>';
                  }
                  $div = floor($x / 10) + 1;
                  if ($div == 1) {
                    $displaynone = 'table-row';
                  } else {
                    $displaynone ='none';
                  }
                  ?>
                  <tr style="display:<?php echo $displaynone ?>" class="kleurblauw-<?php echo $div ?>">
                    <td><?php echo $row['datum'] ?></td>
                    <td class="kleurenIcon"><?php echo $showIcon ?></td>
                  </tr>
                  <?php
                  $x += 1;
                }
             ?>
          </tr>
          </tbody>
        </table>
        <div class="kleurenNav">
          <button type="button" name="button" onclick="kleurenBlauw('Left')"><i class="fas fa-chevron-left"></i></button>
          <input type="text" name="" id="kleurenBlauw" value="1">
          <p>  / <?php echo $div ?></p>
          <button type="button" name="button" onclick="kleurenBlauw('Right')"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
      <div class="kleurenContainer">
          <?php
        $sql = "SELECT * FROM `outputs` WHERE `name` = 'LED4'";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        $status = '';
        while($row = mysqli_fetch_array($result)) {
          if ($row['status'] == '0') {
            $status = '';
          } else {
            $status = 'checked';
          }
        }
          ?>
          <div class="kleurenTitel titleWhite">Wit
                <?php if ($user): ?>
            <label class="switch">
            <input onchange="changeLightStatus('LED4')" id="lightStatusLED4"  name="lightStatusLED4" type="checkbox" <?php echo $status ?>>
            <span class="slider white round"></span></label>
                    <?php endif; ?>
        </div>
        <table>
          <thead>
            <tr>
              <th>Tijd</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php

                $sql = "SELECT * FROM `archiveOutputStatus` WHERE `output` = 'LED4'";
                mysqli_set_charset($conn, "utf8");
                $result = mysqli_query($conn, $sql);
                $numberOfRows = mysqli_num_rows($result);
                $x = 0;
                $div = 1;
                while ($row = mysqli_fetch_array($result)) {
                  $waarde = boolval($row['waarde']);
                  if ($waarde) {
                    $showIcon = '<i class="fas fa-sun"></i>';
                  } else {
                    $showIcon = '<i class="fas fa-moon"></i>';
                  }
                  $div = floor($x / 10) + 1;
                  if ($div == 1) {
                    $displaynone = 'table-row';
                  } else {
                    $displaynone ='none';
                  }
                  ?>
                  <tr style="display:<?php echo $displaynone ?>" class="kleurwit-<?php echo $div ?>">
                    <td><?php echo $row['datum'] ?></td>
                    <td class="kleurenIcon"><?php echo $showIcon ?></td>
                  </tr>
                  <?php
                  $x += 1;
                }
             ?>
          </tr>
          </tbody>
        </table>
        <div class="kleurenNav">
          <button type="button" name="button" onclick="kleurenWit('Left')"><i class="fas fa-chevron-left"></i></button>
          <input type="text" name="" id="kleurenWit" value="1">
          <p>  / <?php echo $div ?></p>
          <button type="button" name="button" onclick="kleurenWit('Right')"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </div>
    <div class="container4" id="vochtigheidDiv">

      <h2>Vochtigheid</h2>
      <p>Hieronder kan u een overzicht van de vochtigheid raadplegen</p>

            <?php if ($user): ?>
              <?php

              $sql = "SELECT * FROM `outputs` WHERE `name` = 'POMP'";
              mysqli_set_charset($conn, "utf8");
              $result = mysqli_query($conn, $sql);
              $status = '';
              while($row = mysqli_fetch_array($result)) {
                if ($row['status'] == '0') {
                  $status = '';
                } else {
                  $status = 'checked';
                }
              }
               ?>
              <p>Pomp status: <label class="switch">
        <input type="checkbox" onchange="changeLightStatus('POMP')" id="lightStatusPOMP"  name="lightStatusPOMP" <?php echo $status ?>>
        <span class="slider round"></span>
          </label></p>
                    <?php endif; ?>

      <div class="toggleSwitch">
        <button type="button" name="button" id="vochtigheidUurButton" class="active" onclick="vochtigheid('uur'); drawChart('uur')">24 uur</button>
        <button type="button" name="button" id="vochtigheidWeekButton" class="" onclick="vochtigheid('week'); drawChart('week')">week</button>
      </div>

      <div class="vochtigheidContainer" id="vochtigheidUur">
          <div class="vochtigheidTableContainer">
            <table>
              <thead>
                <tr>
                  <th>Tijd</th>
                  <th>Waarde</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                <?php
                    $sql = "SELECT * FROM `moistureSensor` ORDER BY `datum` ASC LIMIT 12";
                    $result = mysqli_query($conn, $sql);
                    $numberOfRows = mysqli_num_rows($result);
                    $x = 0;
                    $div = 1;
                    while ($row = mysqli_fetch_array($result)) {
                      $waarde = $row['waarde'];
                      $div = floor($x / 10) + 1;
                      if ($waarde < 300) {
                        $showIcon = '<i class="far fa-frown"></i>';
                      } elseif ($waarde > 600 ) {
                        $showIcon = '<i class="far fa-grin-beam-sweat"></i>';
                      } else {
                        $showIcon = '<i class="far fa-smile"></i>';
                      }
                      // $showIcon
                      ?>
                      <tr style="display:<?php echo $displaynone ?>" class="moisture-<?php echo $div ?>">
                        <td><?php echo $row['datum'] ?></td>
                        <td class="kleurenIcon"><?php echo $showIcon ?></td>
                      </tr>
                      <?php
                      $x += 1;
                    }
                 ?>
              </tr>
              </tbody>
            </table>
          </div>

      </div>


      <div class="vochtigheidContainer" id="vochtigheidWeek">

          <div class="vochtigheidTableContainer">
            <table>
              <thead>
                <tr>
                  <th>Tijd</th>
                  <th>Waarde</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                <?php
                    $sql = "SELECT * FROM `archiveMoistureSensor` ORDER BY `datum` ASC LIMIT 12";
                    $result = mysqli_query($conn, $sql);
                    $numberOfRows = mysqli_num_rows($result);
                    $x = 0;
                    $div = 1;
                    while ($row = mysqli_fetch_array($result)) {
                      $waarde = $row['waarde'];
                      $div = floor($x / 10) + 1;
                      if ($waarde < 300) {
                        $showIcon = '<i class="far fa-frown"></i>';
                      } elseif ($waarde > 600 ) {
                        $showIcon = '<i class="far fa-grin-beam-sweat"></i>';
                      } else {
                        $showIcon = '<i class="far fa-smile"></i>';
                      }
                      // $showIcon
                      ?>
                      <tr style="display:<?php echo $displaynone ?>" class="moisture-<?php echo $div ?>">
                        <td><?php echo $row['datum'] ?></td>
                        <td class="kleurenIcon"><?php echo $showIcon ?></td>
                      </tr>
                      <?php
                      $x += 1;
                    }
                 ?>
              </tr>
              </tbody>
            </table>
          </div>

          <!-- <div class="chart">
            <div id="curve_chart2" style="width: 600px; height: 400px"></div>
          </div> -->
      </div>
      <div class="chart">
        <div id="curve_chart1" style="width: 600px; height: 400px"></div>
      </div>
        <p style="margin: 1px ">Te weinig water <i class="fas fa-long-arrow-alt-right"></i>  <i class="far fa-frown"></i></p>
        <p style="margin: 1px ">Water niveau is prima <i class="fas fa-long-arrow-alt-right"></i>  <i class="far fa-smile"></i></p>
        <p style="margin: 1px ">Te veel water <i class="fas fa-long-arrow-alt-right"></i>  <i class="far fa-grin-beam-sweat"></i></p>
    </div>
    <div class="container5" id="temperatuurDiv" >
      <h2>Temperatuur</h2>
      <p>Bekijk hieronder de waardes van de Temperaturen</p>
      <div class="temperatureTableContainer">
        <table>
          <thead>
            <tr>
              <th>Tijd</th>
              <th>Waarde</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php
                $sql = "SELECT * FROM `temperatureSensor` ORDER BY `datum` ASC LIMIT 12";
                $result = mysqli_query($conn, $sql);
                $numberOfRows = mysqli_num_rows($result);
                $x = 0;
                $div = 1;
                while ($row = mysqli_fetch_array($result)) {
                  $waarde = round($row['waarde'], 2);

                  // $showIcon
                  ?>
                  <tr style="display:<?php echo $displaynone ?>" class="moisture">
                    <td><?php echo $row['datum'] ?></td>
                    <td class="kleurenIcon"><?php echo $waarde ?></td>
                  </tr>
                  <?php
                  $x += 1;
                }
             ?>
          </tr>
          </tbody>
        </table>
      </div>



      </div>


    <footer>
        <img src="/img/logo-provil1.png" style="; position:absolute; left:10px; bottom:5px; width: 100px;">
    <p> &#169; De AgriTower</p>
  </footer>
  </body>

  <script type="text/javascript">

$( document ).ready(function() {
    drawChart('uur')
});

function drawChart(data) {

  var gegevens = {}
  if (data ==='week') {
    gegevens = <?php echo $gegevens2; ?>
  } else  {
    gegevens = <?php echo $gegevens; ?>
  }
  console.log(gegevens)
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data1 = google.visualization.arrayToDataTable(gegevens);
    var options1 = {
      curveType: 'function',
      backgroundColor: '#f9f9f9',
    };

    var chart1 = new google.visualization.LineChart(document.getElementById('curve_chart1'));

    chart1.draw(data1, options1);
  }
}


    function kleurenRood(action){
      var huidigeDiv = parseInt($('#kleurenRood').val())
      console.log(huidigeDiv)
      if (action === "Left") {
        huidigeDiv -= 1;
        huidigeDiv = huidigeDiv < 1 ? 1 : huidigeDiv;
      } else if (action === "Right") {
        huidigeDiv += 1;
        huidigeDiv = huidigeDiv > 10 ? 10 : huidigeDiv;
      }
      console.log(huidigeDiv)

      for (var i = 0; i < 12; i++) {
        $('.kleurrood-'+i).css({'display':'none'})
      }
      $('.kleurrood-'+huidigeDiv).css({'display':'table-row'})
      $('#kleurenRood').val(huidigeDiv)
    }

    function kleurendonkerrood(action){
      var huidigeDiv = parseInt($('#kleurendonkerrood').val())
      console.log(huidigeDiv)
      if (action === "Left") {
        huidigeDiv -= 1;
        huidigeDiv = huidigeDiv < 1 ? 1 : huidigeDiv;
      } else if (action === "Right") {
        huidigeDiv += 1;
        huidigeDiv = huidigeDiv > 10 ? 10 : huidigeDiv;
      }
      console.log(huidigeDiv)

      for (var i = 0; i < 12; i++) {
        $('.kleurdonkerrood-'+i).css({'display':'none'})
      }
      $('.kleurdonkerrood-'+huidigeDiv).css({'display':'table-row'})
      $('#kleurendonkerroodRood').val(huidigeDiv)
    }

    function kleurenBlauw(action){
      var huidigeDiv = parseInt($('#kleurenBlauw').val())
      console.log(huidigeDiv)
      if (action === "Left") {
        huidigeDiv -= 1;
        huidigeDiv = huidigeDiv < 1 ? 1 : huidigeDiv;
      } else if (action === "Right") {
        huidigeDiv += 1;
        huidigeDiv = huidigeDiv > 10 ? 10 : huidigeDiv;
      }
      console.log(huidigeDiv)

      for (var i = 0; i < 12; i++) {
        $('.kleurblauw-'+i).css({'display':'none'})
      }
      $('.kleurblauw-'+huidigeDiv).css({'display':'table-row'})
      $('#kleurenBlauw').val(huidigeDiv)
    }


    function kleurenWit(action){
      var huidigeDiv = parseInt($('#kleurenWit').val())
      console.log(huidigeDiv)
      if (action === "Left") {
        huidigeDiv -= 1;
        huidigeDiv = huidigeDiv < 1 ? 1 : huidigeDiv;
      } else if (action === "Right") {
        huidigeDiv += 1;
        huidigeDiv = huidigeDiv > 10 ? 10 : huidigeDiv;
      }
      console.log(huidigeDiv)

      for (var i = 0; i < 12; i++) {
        $('.kleurwit-'+i).css({'display':'none'})
      }
      $('.kleurwit-'+huidigeDiv).css({'display':'table-row'})
      $('#kleurenWit').val(huidigeDiv)
    }


    function vochtigheid(tijd){
      console.log(tijd)
      if (tijd === 'uur') {
        $('#vochtigheidWeek').css({'display':'none'})
        $('#vochtigheidUur').css({'display':'inline-block'})
        $('#vochtigheidWeekButton').css({'opacity':'0.3'})
        $('#vochtigheidUurButton').css({'opacity':'1'})
      } else {
        $('#vochtigheidWeek').css({'display':'inline-block'})
        $('#vochtigheidUur').css({'display':'none'})
        $('#vochtigheidWeekButton').css({'opacity':'1'})
        $('#vochtigheidUurButton').css({'opacity':'0.3'})
      }
    }
    function logout(){
      $.ajax({
        url: '/includes/logout.php',
        success: function(html){
          window.location.href="/";
        }
      })
    }

    $('#loginForm').on('submit',function(event){
      event.preventDefault();
      $.ajax({
        url: '/includes/login.php',
        method : 'POST',
        data: new FormData(this),
        contentType: false,
        dataType: 'json',
        processData: false,
        success: function(data){

          if (data[1] === 'no') {
            $('#loginInformation').html(data[0]);
            $('#loginInformation').css({'dislplay':'block'});
          } else if (data[1] === 'yes') {
            location.reload();
          }
        }
      })
    })

    function changeLightStatus(light){
      const lightStatus = $('#lightStatus'+light).is(":checked");
      $.ajax({
         url:"./includes/update.php",
         method:"POST",
         data: {name: light, status: lightStatus},
         success:function(data)
         {
           // alert(data)
         }
       })
    }
  </script>
</html>
