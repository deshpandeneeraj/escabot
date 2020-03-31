<html>
  <head>
    <style>
      .chatcont1{
        overflow-y:scroll;
      }
      .container1 {
        border: 2px solid #AAAAAA;
        background-color: rgba(0,0,0,0.8);
        border-radius: 5px;
        padding-bottom: 10px%;
        padding-right: 1px;
        margin: 0 0 10px 20%;
        width: 75%;
        font-size: 24px;
        color:white;
        text-align: center;
      }
      .darker {
        background-color: rgba(255,255,255,0.2);
        margin: 10px 20% 10px 0;
      }
      .container1::after {
        content: "";
        clear: both;
        display: table;
      }
      .time-right {
        float: right;
        color: #aaa;
        font-size: large;
      }
      .time-left {
        float: left;
        color: #999;
        font-size: large;
      }
    </style>
    <?php
      /*
        @Author:Neeraj Deshpande
      */
      //Initialization
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
          echo "<script>parent.location='http://localhost/escabot/login.php;</script>" ;
        }
        if(isset($_FILES['fileToUpload'])) {

            $myFile = $_FILES['fileToUpload'];
            $fileTemp = $myFile["tmp_name"];
            if($fileTemp!=''){

            $bucket = $db->selectGridFSBucket();
            $file = fopen($fileTemp, 'rb');
            $bucket->uploadFromStream($email.$myFile["name"], $file);
          }
        }

      //New Message
      if(isset($_POST['usermsg'])){
        $message = $_POST['usermsg'];
        if($message!=""){
          $a = $userchat -> count(['type'=>'sent','sender'=>$email]);
          $mid = $a+1;
          $userchat -> insertOne(['type'=>'sent', 'mid' => $mid, 'sender'=>$email, 'message'=>$message, 'time'=>date("H:i:s"), 'date'=>date("d-m-y"), 'replied'=>false]);
        }
      }


    ?>
  </head>
  <body>
    <?php
    //Displaying Messages
      $document_sent = $userchat->find(['type'=>'sent','sender'=>$email,'replied'=>true]);
      foreach($document_sent as $uc)
      {
        $mid = $uc['mid'];
        $bc = $userchat->findOne(['mid'=>$mid, 'type'=>'recieved']);
        echo '<div class="container1">
        <p>'.$uc['message'].'</p>
        <span class="time-right">'.$uc['time'].'</span>
        </div>';
        if($bc)
        {
          echo '<div class="container1 darker">
          <p>'.$bc['message'].'</p>
          <span class="time-left">'.$bc['time'].'</span>
          </div>';
        }
      }
    ?>
  </body>
</hmtl>
