<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

header("location: ../index.php");
exit();
?>
<!DOCTYPE html>
<html>
<body>



</body>
</html>