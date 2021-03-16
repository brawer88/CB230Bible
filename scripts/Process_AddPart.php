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
ob_start();
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
  
  $sqlInsertPart = $conn->prepare("INSERT INTO TParts (strPartDesc)
                                  VALUES( ? )");
  // bind parameters
  $sqlInsertPart->bind_param("s", $strPartDesc);

  // get variables
  $strPartDesc =  htmlspecialchars($_POST["txtAddPart"]);
  
      
   // insert part into database
  if( ! $sqlInsertPart->execute() ) 
  {
    $MYSQL_CODE_DUPLICATE_KEY = 1062;
    if( $sqlInsertPart->errno === $MYSQL_CODE_DUPLICATE_KEY ) 
    {
      echo "<script>alert(\"Bible chapter already exists.\");
              window.history.go(-2);
            </script>";
      exit();
    }
  }
  
 
 /*
    -------------------------------------------------------------------------------------
    Process image and verse (only one for now)
 */
 
 
 
  $sqlInsertWithoutImage = $conn->prepare("INSERT INTO TVerses (intPartID, strVerseDesc)
                                  VALUES( ?,?)");
  // bind parameters
  $sqlInsertWithoutImage->bind_param("is", $intPartID, $strVerseDesc);
  
  $sqlInsertWithImage = $conn->prepare("INSERT INTO TVerses (intPartID, strVerseDesc, intImageID)
                                  VALUES( ?,?,? )");
  // bind parameters
  $sqlInsertWithImage->bind_param("isi", $intPartID, $strVerseDesc, $intImageID);
  
  // get variables
  $strVerseDesc =  htmlspecialchars($_POST["txtAddVerse"]);
  // have to add image before verse, fk constraint
  $sqlImageIDSelect = "SELECT MAX(intImageID) AS intImageID FROM TImages;";
  $intImageID = 1;
  
  
  // get part id for rest of script to use
  $sqlPartIDSelect = "SELECT MAX(intPartID) AS intPartID FROM TParts;";
  $intPartID = null;
  
  // get part id
  if($result = mysqli_query($conn, $sqlPartIDSelect)){
    while ($row = mysqli_fetch_array($result))
    {
     $intPartID = $row['intPartID'];
    }
  
    // Free result set
    mysqli_free_result($result);
  }
  
  // save image, get image id
  if( isset( $_FILES["image"] ) && !empty( $_FILES["image"]["name"] ) )  
  {
    
    // Get image name
    $image = $_FILES["image"]["name"];
    // image file directory
    $target = "../images/" . basename($_FILES["image"]["name"]);

    $sqlImage = "INSERT INTO TImages (strImagePath) VALUES ('" . $target . "')";
    // execute query
   if(! $result = mysqli_query($conn, $sqlImage))
    {
      echo "<script>alert(\"Error: Insert into TImages failed." . $target . "\");</script>";
      exit();
    }
      


    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) 
    {
      $msg = "Image uploaded successfully";
    }
    else
    {
      echo "<script>alert(\"Image upload failed.\");</script>";
      exit();
    }
    
    // get imageID
    if($result = mysqli_query($conn, $sqlImageIDSelect))
    {
      while ($row = mysqli_fetch_array($result))
      {
       $intImageID = $row["intImageID"];
      }
      if ( is_null($intImageID) == 1 )
      {
       $intImageID = 1;
      }
      // Free result set
      mysqli_free_result($result);
    }
      
     // insert verse into database
    if( ! $sqlInsertWithImage->execute() ) 
    {
      echo "Execute failed: (" . $sqlInsertWithImage->errno . ") " . $sqlInsertWithImage->error;
      echo "<script>alert(\"Error: " . $sqlInsertWithImage->error . "\");
              window.history.go(-2);
            </script>";exit();
    }
    else
    {
      echo "<script>alert(\"Bible chapter '" . $strPartDesc . "' has been written with picture.\");
              window.history.go(-2);
            </script>";
      exit();
    }
  }
  else
  {
    // insert verse into database
    if( ! $sqlInsertWithoutImage->execute() ) 
    {
      echo "Execute failed: (" . $sqlInsertWithoutImage->errno . ") " . $sqlInsertWithoutImage->error;
      exit();
    }
    else
    {
      echo "<script>alert(\"Bible chapter '" . $strPartDesc . "' has been written.\");
              window.history.go(-2);
            </script>";
            
      // redirect back to home
      
      exit();
    }
  }
  
  // close prepared statement
  $sqlInsertPart->close();
  // close prepared statement
  $sqlInsertWithoutImage->close();
  $sqlInsertWithImage->close();


  

      
  // close connection    
  $conn->close();
  ob_end_flush();
  

  ?>
  
    </body>
    <script>
// unset form data
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
</html>