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
?>
<!DOCTYPE html>
<html>
<head>
<link rel="canonical" href="https://brawer.dev/BrakeBible/PartPortal.php"/>
</head>
<body>
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

  // Prepared statements are very useful against SQL injections,
  //   because parameter values, which are transmitted later using a
  //   different protocol, need not be correctly escaped. 
  //   If the original statement template is not derived from external input, 
  //   SQL injection cannot occur.
  
  $sqlUpdateWithoutImage = $conn->prepare("UPDATE TVerses 
                                           SET strVerseDesc = ?
                                           WHERE intVerseID = ?");
  // bind parameters
  $sqlUpdateWithoutImage->bind_param("si", $strVerseDesc, $intVerseID);
  
  
  $sqlDeleteVerse = $conn->prepare("DELETE FROM TVerses 
                                           WHERE intVerseID = ?");
  // bind parameters
  $sqlDeleteVerse->bind_param("i", $intVerseID);
  
  $sqlDeleteImage = $conn->prepare("DELETE FROM TVerses 
                                           WHERE intVerseID = ?");
  // bind parameters
  $sqlDeleteImage->bind_param("i", $intImageID);
  
  $sqlSelectVerse = $conn->prepare("SELECT intImageID FROM TVerses
                                          WHERE intVerseID = ?");
  $sqlSelectVerse->bind_param("i", $intVerseID);
  
  
  // get variables
  $intVerseID = $_POST["intVerseID"];
  $strVerseDesc =  htmlspecialchars($_POST["txtAddVerse"]);
  
  $sqlSelectVerse->execute();
  $result = $sqlSelectVerse->get_result();
  $row = $result->fetch_assoc();
  $intImageID = $row["intImageID"];

  
  if( $_POST["submit"] === "Submit" ) 
  {
    // insert verse into database
    if( ! $sqlUpdateWithoutImage->execute() ) {
      echo "Execute failed: (" . $sqlUpdateWithoutImage->errno . ") " . $sqlUpdateWithoutImage->error;
    }
    else
    {
      echo "Saved";
      echo "<script>alert(\"Verse has been updated.\");
              window.history.go(-2);
            </script>";
    }
  }
  else  
  {
    if( is_null($intImageID) !== 1 )
    {
      // delete image first 
      $sqlDeleteImage->execute();
    }
    
    
    if( $sqlDeleteVerse->execute() )
    {
      echo "<script>alert(\"Verse has been deleted.\");
            window.history.go(-2);
            </script>";
    }
    else
    {
        echo "<script>alert(\"Verse could not be deleted.\");
            window.history.go(-2);
            </script>";
    }  
  }
  

  // close prepared statement
  $sqlUpdateWithoutImage->close();
  $sqlDeleteVerse->close();
  $sqlDeleteImage->close();
  $sqlSelectVerse->close();
      
  // close connection    
  $conn->close();
  
  // redirect back
  
  echo "<script>window.history.go(-2);</script>"

  ?>
  
  </body>
  <script>
// unset form data
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
</html>