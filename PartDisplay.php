<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
  session_start();
    /* are we logged in? */
    if ( ! isset( $_SESSION["blnLoggedIn"] ) )
    {
       // we shouldn't to be here
        header("location: index.php");
        exit();
    }
    

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

     // get part id
    $intPartID = $_GET["id"];
    
    // select from db
    $sqlSelectVerses = "SELECT TV.intVerseID, TV.strVerseDesc, TV.intImageID, TI.strImagePath FROM TVerses AS TV LEFT JOIN TImages AS TI ON TV.intImageID = TI.intImageID  WHERE TV.intPartID = " . $intPartID . " ORDER BY TV.intVerseID";
    $sqlPartSelect = "SELECT strPartDesc FROM TParts WHERE intPartID = " . $intPartID . " ORDER BY strPartDesc";
    // get part name
    if($result = mysqli_query($conn, $sqlPartSelect)){
      while ($row = mysqli_fetch_array($result))
      {
       $strPartName = $row['strPartDesc'];
      }
    
      // Free result set
      mysqli_free_result($result);
    }
  ?>
  
<!DOCTYPE HTML>
<html lang="en-us"> 

  <head>
    <title>Part Display</title>
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <link rel="canonical" href="https://brawer.dev/BrakeBible/PartPortal.php"/>
    <style>
      .btn .btn-primary
      {
          background: #1abc9c;
      }
    </style>
  </head>
  <body>
    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">
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

        <form name="frmVerse" method="post" action="AddVerse.php">
        <fieldset>
        
          <?php
          echo "<legend style='text-align: center;'><big >" . $strPartName . ":</big></legend>";
          
          echo "<ol>";
            echo "<input name='intPartID' type='text' hidden value='" . $intPartID . "' >";
            // display verses
            if($result = mysqli_query($conn, $sqlSelectVerses)){
              while ($row = mysqli_fetch_array($result))
              {
                if( $_SESSION["blnAdmin"] === "true" )
                {
                  echo "<li>" . $row["strVerseDesc"] . "<br><a class='btn btn-primary' href='EditVerse.php?id=" . $row["intVerseID"] . "'>&#9998;</a> </li>";
                }
                else
                {
                  echo "<li>" . $row["strVerseDesc"] . "</li>";
                }
                if ( is_null($row["intImageID"]) != 1 )
                {
                  $strImagePath = substr($row['strImagePath'], 3);
                  echo "<img src='" . $strImagePath . "' class='img-rounded img-responsive' width='250' height='350'>";
                }
              }
              // Free result set
              mysqli_free_result($result);
              
              if ( $_SESSION["blnAdmin"] === "true" )
              {
                echo "<br><br><div style='margin: auto'><button type='submit' name='btnAdd' id='btnAdd' class='btn btn-primary'>
                      <span class='glyphicon glyphicon-plus-sign' ></span>
                    </button> </div><br>";
                echo  "<a class='btn btn-primary' href='EditPart.php?id=" . $intPartID . "'>Edit Part</a>";
              }
              
            }
            
          ?>
          
          </ol>
          <br>
          <br>
          
           <input type="button" class='btn btn-primary' onclick="history.go(-1);" value="Back">
        </fieldset>
        </form>
      </div>
        
<?php
  // close connection    
    $conn->close();
?>

<script>
// unset form data
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
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
    <!-- End smartphone / tablet look -->
    </div>
    
  </body>
</html>