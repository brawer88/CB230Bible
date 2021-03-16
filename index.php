<?php
  ini_set('session.cookie_lifetime', 24 * 60 * 60 * 120);
  ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
  session_start();
  if( isset($_SESSION["blnLoggedIn"]))
  {
    // we don't need to be here
    header("location: PartPortal.php");
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
    <title>Are you allowed?</title>
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
</head>

<body>
  <?php
    
    

    function clean_input($data) 
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
       
       
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (! empty($_POST["txtAnswer"])) {
        $txtAnswer = $_POST['txtAnswer'];
        /* clean */
        $txtAnswer = clean_input( $txtAnswer );
        
        if ( strcmp( $txtAnswer, "StockMFG" ) === 0 )
        {
          $_SESSION["blnLoggedIn"] = "true";
          $_SESSION["blnAdmin"] = "false";
          echo "<script>window.location.href = 'https://brawer.dev/BrakeBible/PartPortal.php';</script>";
          exit();
        }
        else if( strcmp( $txtAnswer, "MFGWizard" ) === 0 )
        {
          $_SESSION["blnLoggedIn"] = "true";
          $_SESSION["blnAdmin"] = "true";
          echo "<script>window.location.href = 'https://brawer.dev/BrakeBible/PartPortal.php';</script>";
          exit();
        }
        else
        {
          echo "<h3 style='color: red; align: center;'>Then, you shall not pass.</h3>";
        }
      }
    }

  ?>
  <br>
  <br>
  <form class="form-style-div" name="frmValidation" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  
    <table>
      <legend><h4 style='text-align: center;'>Ask supervisor for password.</h4></legend>
      <tr>
        <td><input type="text" name="txtAnswer" id="txtAnswer" placeholder="What is the passphrase?"></td>
      </tr>
      <tr>
        <td><input type="submit" class='btn btn-primary'></td>
      </tr>
    </table>
  </form>
  
  
  
</body>


</html>