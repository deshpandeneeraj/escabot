<html>
  <head>
    <link rel="stylesheet" type="text/css" href="stylesheet1.css">

    <title>ESCABOT</title>
    <h1>Chat with ESCABOT</h1>
    <?php
    /*
      @Author:Neeraj Deshpande
    */
    session_start();
    require 'vendor/autoload.php';
    $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $db = $client -> escabot;
    $userdata = $db -> userdata;
    $userchat = $db -> userchat;
    if(isset($_SESSION['fname']) && isset($_SESSION['nick']) && isset($_SESSION['email']) ){
        $fname = $_SESSION['fname'];
        $nick = $_SESSION['nick'];
        $email = $_SESSION['email'];

    }
    else{
      //FailCase
      header("Location:login.php");
    }
    ?>
  </head>
  <body>
    <div class='chatcont'>
      <iframe src = 'chat.php' name="chatdiv", id = "chatframe"></iframe>
      <form action = "chat.php", target="chatdiv" method = 'POST' enctype="multipart/form-data">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input type="submit"  id="submitmsg" name="submit" value="Send" />
        <input type="reset" value="Refresh Messages" onclick="document.getElementById('chatframe').contentWindow.location.reload(true);">
        <br>
        <label for="fileToUpload">Upload a file instead</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload"  accept="image/x-png,image/gif,image/jpeg">
      </form>
  </div>
  <a href ="logout.php" >Logout</a>
  <a href ="escaimages.php" style="bottom:70%" >ESCAIMAGES</a>


  </body>
</html>
