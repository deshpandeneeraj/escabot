<!DOCTYPE html>
<html>
  <head>
    <title>ESCA Images
    </title>
    <link rel="stylesheet" type="text/css" href="stylesheet2.css">
    <style>
    div.gallery {
      margin: 5px;
      border: 1px solid #ccc;
      float: left;
      width: 180px;
    }

    div.gallery:hover {
      border: 1px solid #777;
    }

    div.gallery img {
      width: 100%;
      height: auto;
    }

    div.desc {
      padding: 15px;
      text-align: center;
    }
    </style>
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
      $files = $db -> {'fs.files'};
      $bucket = $db->selectGridFSBucket();
      $directory = "files/";

      $listId = [];
      $listName = [];

      $cursor = $files -> find();
      foreach($cursor as $one){

        $name = $one['filename'];
        $id = $one['_id'];
        //Checking files for user
        if (strpos($name, $email) !== false){
          $listId[]=$id;
          $listName[]=$name;

        }
      }

      //Downloading Files
      foreach($listId as $key=>$file){
        $name = $listName[$key];
        $stream = $bucket->openDownloadStream($file);
        if (!is_dir($directory)) {
          mkdir($directory);
        }

        $output = fopen($directory.$name, 'w+');
        $stream = $bucket->openDownloadStream($file);
        $contents = stream_get_contents($stream);
        fwrite($output, $contents);

          //$bucket->downloadToStream($file, $output);
      }
      echo '<h2>Welcome, '.$nick;
      foreach($listName as $file){
        $path = $directory.$file;
        echo ' <div class="gallery">
            <a target="_blank" href="'.$path.'">
              <img src="'.$path.'" alt="'.$path.'" width="600" height="400">
            </a>

          </div>';
      }
    ?>
    <a href ="logout.php" >Logout</a>


  </body>
</html>
