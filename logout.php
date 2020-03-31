<?php
session_start();
if(isset($_SESSION['files'])){
  $files = $_SESSION['files'];
  foreach($files as $file){
    unlink("files/".$file);
  }
}
session_destroy();
header("Location:http://localhost/escabot/login.php");
?>
