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
    <title>Die Selector</title>
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
    <meta name="viewport" content="width=device-width, initial-scale=.6">
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

      <form name="frmDieSelection" method="get" action="DieSelector.php" >
        <fieldset>
          <legend><big>Selector Tool</big></legend>
          <label for="selmtlThickness">Material Thickness:</label>
          <div id="radType">
            <input type="radio" name="type" value="1" CHECKED>Carbon Steel</input>
            <input type="radio" name="type" value="2">Stainless Steel</input>
          </div>
          <div id="selmtlThickness">
              <select name="selmtlThickness">
                  <option value=".25">.25"</option>
                  <option value=".1793">7 GA</option>
                  <option value=".1644">8 GA</option>
                  <option value=".1495">9 GA</option>
                  <option value=".1345">10 GA</option>
                  <option value=".1196">11 GA</option>
                  <option value=".1046">12 GA</option>
                  <option value=".0747">14 GA</option>
                  <option value=".0598">16 GA</option>
                  <option value=".0478">18 GA</option>
                  <option value=".0359">20 GA</option>
                  <option value=".0299">22 GA</option>
                  <option value=".0239">24 GA</option>
                  <option value=".0179">26 GA</option>
                  <option value=".0149">28 GA</option>
              </select>
          </div>
          <script>
          $('#radType input:radio').change(function() {
            var selectedVal = $("#radType input:radio:checked").val();
            if(1 == selectedVal){
                var carbonSteelList = '<select name="selmtlThickness"><option value=".25">.25"</option><option value=".1793">7 GA</option><option value=".1644">8 GA</option><option value=".1495">9 GA</option><option value=".1345">10 GA</option><option value=".1196">11 GA</option><option value=".1046">12 GA</option><option value=".0747">14 GA</option><option value=".0598">16 GA</option><option value=".0478">18 GA</option><option value=".0359">20 GA</option><option value=".0299">22 GA</option><option value=".0239">24 GA</option><option value=".0179">26 GA</option><option value=".0149">28 GA</option></select>';
                $('select').remove();
                $('#selmtlThickness').append(carbonSteelList);
            }else if(2 == selectedVal){
                var stainlessSteelList = '<select name="selmtlThickness"><option value=".25">.25"</option><option value=".17187">8 GA</option><option value=".15625">9 GA</option><option value=".14062">10 GA</option><option value=".125">11 GA</option><option value=".10937">12 GA</option><option value=".07812">14 GA</option><option value=".0625">16 GA</option><option value=".05">18 GA</option><option value=".0375">20 GA</option><option value=".03125">22 GA</option><option value=".025">24 GA</option><option value=".01875">26 GA</option><option value=".01562">28 GA</option><option value=".0125">30 GA</option></select>';
                $('select').remove();
                $('#selmtlThickness').append(stainlessSteelList);

            }
          });
          </script>
          <br><br>
        </fieldset>
        <input type="submit" class='btn btn-primary'>
        <input type="reset" class='btn btn-primary'>
        <input type="button" class='btn btn-primary' onclick="history.go(-1);" value="Back">
      </form>
      <div>
        <br>
        <div id="parent">
            <div id="child"></div>
            <?php
              $intMinimal = 6;
              $intNominal = 7;
              $intMaximum = 8;
              
              $dblMaterialThickness = $_GET["selmtlThickness"];
              
              // calculated die widths
              $dblMinimumDieWidth = 0;
              $dblNominalDieWidth = 0;
              $dblMaximumDieWidth = 0;
              
              // closest tooling index
              $intIndexMinimum = 0;
              $intIndexNominal = 0;
              $intIndexMaximum = 0;
              
              
              // array holding mm values for tooling
              $aMmWidths = array( 8, 10, 12, 16, 24, 30, 40, 50, 80 );
              // parrallel array holding possible ev numbers
              $aPossibleSerialNumbers = array( "EV002", "EV003", "EV023", "EV005/EV024", "EV026", "EV028", "EV029", "EV-W80" );
              // parrallel array holding imperial 
              $aImperialWidths = array( .315, .3937, .4724, .6299, .9449, 1.5748, 1.9685, 3.15 );
              // parrallel array holding minimum flange lengths
              $adblMinimumFlangeLengths = array();
              for ($intIndex = 0; $intIndex < count($aImperialWidths); $intIndex += 1)
              {
                $adblMinimumFlangeLengths[$intIndex] = $aImperialWidths[$intIndex] / 2;
              }
              
              // function to find nearest decimal number to get die
              function getClosest($searchValue, $arr) 
              {
                 $closestValue = null;
                 $intClosestIndex = 0;
                 for ( $intIndex = 0; $intIndex < count($arr); $intIndex += 1 ) 
                 {
                   $currentValue = $arr[$intIndex];
                    if ($closestValue === null || abs($searchValue - $closestValue) > abs($currentValue - $searchValue)) 
                    {
                       $closestValue = $currentValue;
                       $intClosestIndex = $intIndex;
                    }
                 }
                 return $intClosestIndex;
              }
              
              // was data entered?
              if( is_null($dblMaterialThickness) != 1)
              {
                if( $dblMaterialThickness > 0.5 )
                {
                  // up our calculations to account for greater radius
                  $intMinimal = 8;
                  $intNominal = 9;
                  $intMaximum = 10;
                  
                }
                
                // calculate the three die widths
                // calculated die widths
                $dblMinimumDieWidth = $dblMaterialThickness * $intMinimal;
                $dblNominalDieWidth = $dblMaterialThickness * $intNominal;
                $dblMaximumDieWidth = $dblMaterialThickness * $intMaximum;
                
                // closest tooling index
                $intIndexMinimum = getClosest( $dblMinimumDieWidth, $aImperialWidths );
                $intIndexNominal = getClosest( $dblNominalDieWidth, $aImperialWidths );
                $intIndexMaximum = getClosest( $dblMaximumDieWidth, $aImperialWidths );
                
                echo "<fieldset>";
              }
              else
              {
                echo "<fieldset hidden>";
              }
            ?>
            <table>
            <legend><?php echo "Calculated Data for " . $dblMaterialThickness; ?><legend>
              <tr>
                <td>/\_0_/\</td>
                <th scope="col">Needed Width</th>
                <th scope="col">Closest Width</th>
                <th scope="col">W- number</th>
                <th scope="col">Serial Number</th>
                <th scope="col">Min. Flange</th>
              </tr>
              <tr>
                <th scope="row">Minimum</th>
                <td><?php echo $dblMinimumDieWidth; ?></td>
                <?php if ( $aImperialWidths[$intIndexMinimum] < $dblMinimumDieWidth )
                      {
                        echo "<td style='color: red;'>" . $aImperialWidths[$intIndexMinimum];
                      }
                      else
                      {
                        echo "<td style='color: black;'>" . $aImperialWidths[$intIndexMinimum];
                      }
                       ?></td>
                <td><?php echo "W" . $aMmWidths[$intIndexMinimum]; ?></td>
                <td><?php echo $aPossibleSerialNumbers[$intIndexMinimum]; ?></td>
                <td><?php echo $adblMinimumFlangeLengths[$intIndexMinimum]; ?></td>
              </tr>
              <tr>
                <th scope="row">Nominal</th>
                <td><?php echo $dblNominalDieWidth; ?></td>
                <?php if ( $aImperialWidths[$intIndexNominal] < $dblNominalDieWidth )
                      {
                        echo "<td style='color: red;'>" . $aImperialWidths[$intIndexNominal];
                      }
                      else
                      {
                        echo "<td style='color: black;'>" . $aImperialWidths[$intIndexNominal];
                      }
                       ?></td>
                <td><?php echo "W" . $aMmWidths[$intIndexNominal]; ?></td>
                <td><?php echo $aPossibleSerialNumbers[$intIndexNominal]; ?></td>
                <td><?php echo $adblMinimumFlangeLengths[$intIndexNominal]; ?></td>
              </tr>
              <tr>
                <th scope="row">Maximum</th>
                <td><?php echo $dblMaximumDieWidth; ?></td>
                <?php if ( $aImperialWidths[$intIndexMaximum] < $dblMaximumDieWidth )
                      {
                        echo "<td style='color: red;'>" . $aImperialWidths[$intIndexMaximum];
                      }
                      else
                      {
                        echo "<td style='color: black;'>" . $aImperialWidths[$intIndexMaximum];
                      }
                       ?></</td>
                <td><?php echo "W" . $aMmWidths[$intIndexMaximum]; ?></td>
                <td><?php echo $aPossibleSerialNumbers[$intIndexMaximum]; ?></td>
                <td><?php echo $adblMinimumFlangeLengths[$intIndexMaximum]; ?></td>
              </tr>
            </table>
        </fieldset>
        </div>
      </div>
      <p>Helpful reading: <a target="_blank" class='btn btn-info' href="https://www.fabricatingandmetalworking.com/2017/07/how-to-select-a-v-die-opening-for-press-brake-bending/">here</a></p>
      <p> <big>&#9757;</big> Please take care and pay attention to the "needed width" column and compare it to the "closest width" column. Too far below or above could stress tools.</p>
      <ul>
        <li>This is a reference guide only. If you aren't sure, stick with the "nominal" row. Red colored text indicates potential for tool damage.</li>
        <li>The calculation used 6-8 rule, up to 1/2", where it goes 8-10 times the material thickness.</li>
        <li>The minimum flange length is half of the tool width - make sure the metal stays on the tool shoulders the duration of the bend, or the part could be misformed.</li>
        <li>The tools in the calculation come from the Trumpf catalog - we may not have some or have ones not included here.</li>
      </ul>
      
      <img src="../images/CarbonSteelChart.png" width="300" height="500">
      <img src="../images/ssChart.png" width="300" height="500">
      
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
</script>

</html>