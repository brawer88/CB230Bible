<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
  session_start();
    /* are we logged in? */
    if ( empty( $_SESSION["blnLoggedIn"] ) )
    {
       // we shouldn't to be here
        header("location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en-us"> 

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JV97B3S8G1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-JV97B3S8G1');
    </script>
    <title>Part Portal</title>
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="../styles/forms.css" rel="stylesheet" type="text/css">
    <link href="../styles/global.css" rel="stylesheet" type="text/css">
    <link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#829CD0">
    <meta name="msapplication-TileImage" content="images/ms-icon-144x144.png">
    <meta name="theme-color" content="#829CD0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="canonical" href="https://brawer.dev/BrakeBible/PartPortal.php"/>
</head>

<body>
<!-- Simulate a smartphone / tablet -->
  <div class="mobile-container">
  
  <?php
    
    //Connect to MySQL
    $servername = "162.241.194.53";
    $username = "brawer29_brawer";
    $password = "Grand10us";
    $dbname = "brawer29_dbStockMFG";
    
    //Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    //Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
      
      $sqlPartSelect = "SELECT * FROM TParts";
    
  ?>
  
  
    <div class="topnav" id="myTopnav">
        <a href="PartPortal.php" >Home</a>
        <!-- Navigation links (hidden by default) -->
        <div id="myLinks">
          <a href="tools/mmConversion.php" >MM Conversion</a>
          <a href="tools/DieSelector.php" >Die Selector</a>
          <a href="tools/StepBendCalculator.php" >Step Bend Calculator</a>
          <a href="mailto:brawer@brawer.dev">Contact Developer</a>
          <a href="scripts/logout.php">Log Out</a> 
          <?php 
            if ( $_SESSION["blnAdmin"] === "true" )
            {
              echo "<a href=\"AddPart.php\" >Add Part</a>";
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
      <script>
        function SetData(){
           var select = document.getElementById('selParts');
           var intPartID = select.options[select.selectedIndex].value;
           document.frmPartSelect.action = "PartDisplay.php?id="+ intPartID ;
           frmPartSelect.submit();
        }
      </script>
      
      <form name="frmPartSelect" method="post" action="PartDisplay.php?id=" onsubmit="SetData()" >
        <fieldset>
          <legend style='text-align: center;'><big>Select Part:</big></legend>
        <!--Get parts list from db-->
        <label for="selParts"></label>
        <?php
          // populate select box
          if($result = mysqli_query($conn, $sqlPartSelect)){
              echo "<select name ='selParts' style='height:40px;' id='selParts'>";
              echo "<option disabled selected value style='display: none;'>Select Part</option>";
              while ($row = mysqli_fetch_array($result))
              {
                echo "<option value =" . $row["intPartID"];

                echo ">" . $row["strPartDesc"] . "</option>";
              }
              echo "</select>";
              // Free result set
              mysqli_free_result($result);
            }
        ?>
        </fieldset>
        <input type="submit" name="btnView" id="btnView" value="View" class='btn btn-primary' disabled>
       
      </form>
    </div>
     <button class='btn btn-warning' id="add-button">Install Brake Bible</button>
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
var mySelect = document.getElementById('selParts');
 mySelect.onchange = (event) => {
     document.getElementById('btnView').disabled = false;
 }


// reload page when shown
window.addEventListener("pageshow", () => {
  document.getElementById('selParts').selectedIndex = 0;
});



</script>
<?php
  // close connection    
    $conn->close();
?>
<script src="PWAEventListeners.js"></script>
</html>