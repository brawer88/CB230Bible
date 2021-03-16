<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
  session_start();
    /* are we logged in? */
    if ( ! isset( $_SESSION["blnLoggedIn"] ) )
    {
       // we shouldn't to be here
        header("location: ../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en-us"> 

<head>
    <title>Step Bend Calculator</title>
    <script>
      	if ('serviceWorker' in navigator) {
          window.addEventListener('load', function() {
            navigator.serviceWorker.register('/BrakeBible/service-worker.js').then(function(registration) {
              // Registration was successful
              console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
              // registration failed :(
              console.log('ServiceWorker registration failed: ', err);
            });
          });
        }
        
     
    </script>
    <meta name="mobile-web-app-capable" content="yes"/> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=.75">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="../../styles/forms.css" rel="stylesheet" type="text/css">
    <link href="../../styles/global.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="57x57" href="../images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
    <link rel="manifest" href="../manifest.json">
    <meta name="msapplication-TileColor" content="#829CD0">
    <meta name="msapplication-TileImage" content="../images/ms-icon-144x144.png">
    <meta name="theme-color" content="#829CD0">
    <link rel="canonical" href="https://brawer.dev/BrakeBible/PartPortal.php"/>
    <style>
      table {
        border-spacing: 5px;
        margin:auto;
      }
      .mobile-container {
        min-width: 425px;
      }
      table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      th, td {
        text-align: center;
      }

    </style>
</head>

<body>
<!-- Simulate a smartphone / tablet -->
  <div class="mobile-container">
    <div class="topnav" id="myTopnav">
        <a href="../PartPortal.php" >Home</a>
        <!-- Navigation links (hidden by default) -->
        <div id="myLinks">
          <a href="mmConversion.php" >MM Conversion</a>
          <a href="DieSelector.php" >Die Selector</a>
          <a href="StepBendCalculator.php" >Step Bend Calculator</a>
          <a href="mailto:brawer@brawer.dev">Contact Developer</a>
          <a href="../scripts/logout.php">Log Out</a> 
          <?php 
            if ( $_SESSION["blnAdmin"] === "true" )
            {
              echo "<a href=\"../AddPart.php\" >Add Part</a>";
            }
          ?>
        </div>
        <!-- "Hamburger menu" / "Bar icon" to toggle the navigation links -->
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
          <i class="fa fa-bars"></i>
        </a>
    </div> 
    <br>
    <div style="padding:10px, 10px">

      <form name="frmStepBend" method="get" action="StepBendCalculator.php" >
        <fieldset>
          <legend><big>Step Bend Calculator</big></legend>
          <label for="selDie">Chosen Die *: </label>
          <?php
            // parrallel array holding possible ev numbers
            $aPossibleSerialNumbers = array( "EV002", "EV003", "EV023", "EV005/EV024", "EV026", "EV028", "EV029", "EV-W80" );
            
            echo "<select name ='selDie' style='height:40px;' id='selDie' required>";
            echo "<option disabled selected value style='display: none;'>Select Die</option>";
            for( $intIndex = 0; $intIndex < count($aPossibleSerialNumbers); $intIndex += 1 )
            {
              echo "<option value =" . $intIndex . ">";
              echo $aPossibleSerialNumbers[$intIndex] . "</option>";
            }
            echo "</select>";
          ?>
          <label for="dblBendAngle">Bend Angle:</label>
          <input type="number" id="dblBendAngle" name="dblBendAngle" min="0" max="180" step="0.1" required>
          <label for="dblRadiusFlat">Radius Length in Flat:</label>
          <input type="number" id="dblRadiusFlat" name="dblRadiusFlat" min="0" max="180" step="0.00001" required>
          <br><br>
        </fieldset>
        <input type="submit" id="btnCalculate" disabled class='btn btn-primary'>
        <input type="reset" class='btn btn-primary'>
        <input type="button" class='btn btn-primary' onclick="history.go(-1);" value="Back">
      </form>
      <div>
        <br>
        <div id="parent">
            <div id="child"></div>
            <?php
              $dblDeltaValue = 0;
              $dblAnglePerBend = 0;
              $intRepetitions = 0;
              
               // parrallel array holding imperial 
              $aImperialWidths = array( .315, .3937, .4724, .6299, .9449, 1.5748, 1.9685, 3.15 );
              

              
              $dblBendAngle = $_GET["dblBendAngle"];
              $dblDieWidth = $aImperialWidths[$_GET["selDie"]];
              $dblFlat = $_GET["dblRadiusFlat"];
  

              
              // was data entered?
              if( is_null($dblBendAngle) != 1)
              {
                
                
                $dblDeltaValue = round(($dblDieWidth / 2), 4);
                $intRepetitions = round($dblFlat / $dblDeltaValue);
                $dblAnglePerBend = round(($dblBendAngle / $intRepetitions), 2);
                  
                echo "<fieldset>";
              }
              else
              {
                echo "<fieldset hidden>";
              }
            ?>
            <table style='width: 300px;'>
            <legend><?php echo "<h4 style='text-align: center'>Calculated Data</h4>"?><legend>

              <tr>
                <th scope="row">Delta Value</th>
                <td><?php echo "- " . $dblDeltaValue;?></td>
              </tr>
              <tr>
                <th scope="row">Angle Per Bend</th>
                <td><?php echo $dblAnglePerBend;?></td>
              </tr>
              <tr>
                <th scope="row">Repetitions</th>
                <td><?php echo $intRepetitions;?></td>
              </tr>
            </table>
        </fieldset>
        </div>
      </div>
      <br>
      <ul>
        <li><span style="color: red"><big>*</big></span> Used to determine the delta value.</li>
        <li>This is a reference guide only. But should get you close to the correct part. </li>
        <li>"Angle per Bend" is the measured angle. Put in 180 minus the "Angle Per Bend" value as normal.</li>
        <li> Angle corrections apply across all bends, divide needed correction by bend repetition to get a good correction.</li>
        
      </ul>
      <br>
      <figure>
        <figcaption style="font-weight: bold"><big>Step Bend Input Example</big></figcaption>
        <img src="../images/StepBendExample.png" width="400" height="400" style='padding: 2.5em;'>
      </figure>
      
    </div>
  </div>
</body>
<script>
/* Toggle between showing and hiding the navigation menu links when the user clicks on the hamburger menu / bar icon */
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

// change button to enabled when part is selected
var mySelect = document.getElementById('selDie');
 mySelect.onchange = (event) => {
     document.getElementById('btnCalculate').disabled = false;
 }
</script>

</html>