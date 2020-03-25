<!DOCTYPE html>
<html>
  <head>
    <title>ESCA Images
    </title>
    <link rel="stylesheet" type="text/css" href="stylesheet1.css">
<h1>YOUR IMAGES</h1>
  </head>
  <body>
    <?php
    if(isset($_SESSION['fname']) && isset($_SESSION['nick']) && isset($_SESSION['email']) ){
        $fname = $_SESSION['fname'];
        $nick = $_SESSION['nick'];
        $email = $_SESSION['email'];

      }
      else{
        //FailCase
        header("Location:http://localhost/escabot/login.php");
      }
      require 'vendor/autoload.php';
      $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
      $db = $client -> escabot;
      $files = $db -> fs.files;
      $bucket = $db->selectGridFSBucket();

      $stream = $bucket->openDownloadStreamByName('my-file.txt', ['revision' => 0]);
      $listId = [];
      $listName = [];

      $cursor = $files -> find();
      foreach($files as $one){
        $name = $one['filename'];
        $id = $one['_id'];
        //Checking files for user
        if (strpos($name, $email) !== false){
          array_push($listId,$id);
          array_push($listName,$id);

        }
      }
      //Downloading Files
      foreach($listId as $key=>$file){
        $name = $listName[$key];
        $stream = $bucket->openDownloadStream($file);
        $output = fopen('files/'.$name, 'wb');
        $bucket->downloadToStream($file, $ouput);
      }
      echo '<h2>Welcome, '.$nick;
      foreach($fileName as $file){
        echo ' <div class="gallery">
            <a target="_blank" href="img_mountains.jpg">
              <img src="files/'.$file.'" alt="files/'.$file.'" width="600" height="400">
            </a>
            <div class="desc">Add a description of the image here</div>
          </div>'
      }
    ?>


  </body>
</html>
