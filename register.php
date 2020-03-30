<!DOCTYPE html>
<html>
  <head>
    <script>
      function validateForm() {
          if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.myForm.email.value))
            {
              return (true)
            }
            else{
              alert("You have entered an invalid email address!")
              return (false)
            }
        }
    </script>
    <h1>REGISTER WITH ESCABOT</h1>
    <title>EscaBot Register</title>
    <link rel="stylesheet" type="text/css" href="stylesheet1.css">
    <script>
    function clear() {
    var modal = document.getElementById('id01');
    window.onclick = function(event) {
    if (event.target == modal) {
    modal.style.display = "none";
    }
    }
    }
    </script>
  </head>
  <body onload = 'clear()'>
    <h2> Register Form</h2>
    <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;"><span>Sign Up</span></button>

    <div id="id01" class="modal">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
      <form name = "myForm" class="modal-content", onsubmit="return validateForm()" ,action"<?php echo $_SERVER['PHP_SELF']?>" method='post'>
        <div class="container">
          <h1>Register</h1>
          <p>Please fill in this form to create an account.</p>
          <hr>
          <label for="email"><b>Email</b></label>
          <input type="text" placeholder="Enter Email" name="email" required>
          <label for="fname"><b>First Name</b></label>
          <input type="text" placeholder="Enter First Name" name="fname" required>
          <label for="lname"><b>Last Name</b></label>
          <input type="text" placeholder="Enter Last Name" name="lname">
          <label for="nick"><b>Nickname</b></label>
          <input type="text" placeholder="Enter NickName" name="nick" required>
          <label for="psw"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" required>

          <label for="psw-repeat"><b>Repeat Password</b></label>
          <input type="password" placeholder="Repeat Password" name="psw-repeat" required>


          <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

          <div class="clearfix">
            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn"><span>Cancel</span></button>
            <button type="submit" class="signupbtn"><span>Sign Up</span></button>
          </div>
        </div>
      </form>
    </div>
    <?php
    /*
      @Author:Neeraj Deshpande
    */
    require 'vendor/autoload.php';
    session_start();
    $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $db = $client -> escabot;
    $userdata = $db -> userdata;
    if(isset($_POST['email']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['psw']) && isset($_POST['psw-repeat'])){
      if($_POST['psw'] == $_POST['psw-repeat']){
        $document = array("name" => (object)array("email" => $_POST['email'], "firstname" => $_POST['fname'], "lastname" => $_POST['lname']), "nick" => $_POST['nick'], "password" => md5($_POST['psw']));
        if($userdata->insertOne( $document )==1){
          header("Location: login.php");
        }
      }
    }
     ?>
  </body>
