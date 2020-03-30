<!DOCTYPE html>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="stylesheet1.css">
    <title>ESCABOT LOGIN</title>
    <div class = "header">
    <h1>LOGIN TO YOUR ESCABOT ACCOUNT</h1>
    <script>
    function clear() {
var modal = document.getElementById('id01');
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
}
function validate() {
         if( document.myForm.name.value == "" ) {
            alert( "Please provide your Email!" );
            document.myForm.email.focus() ;
            return false;
         }
       }
</script>
</div>
  </head>
  <body onload="clear()">
    <h2>Login Form</h2>
    <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;"><span>Login</span></button>
    <button onclick='window.location.href = "register.php"' style="width:auto;"><span>Register</span></button>
    <div id="id01" class="modal">
      <form class="modal-content animate" action"<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="container">
          <label for="email"><b>EMAIL</b></label>
          <input type="text" placeholder="Enter Username(EMAIL)" name="email" required>
          <label for="psw"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" required>
          <button type="submit">Login</button>
          <label>
            <input type="checkbox" checked="checked" name="remember"> Remember me
          </label>
        </div>

          <div class="container" style="background-color:#f1f1f1">
            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn"><span>Cancel</span></button>
            <span class="psw">Forgot <a href="#">password?</a></span>
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
        if(isset($_POST['email'])&&isset($_POST['psw'])){
          $email = $_POST['email'];
          $pass = md5($_POST['psw']);
          $query = array('email' => $email, 'password' => $pass);
          if($userdata->count($query) >= 1){
            $document = $userdata -> findOne($query);
            $fname = $document['name']['firstname'];
            $nick = $document['nick'];
            $email = $document['email'];
            $_SESSION['fname'] = $fname;
            $_SESSION['nick'] = $nick;
            $_SESSION['email'] = $email;
            if(isset($_POST['remember'])){
              setcookie('fname', $fname, time() + (86400 * 30), "/"); // 86400 = 1 day
              setcookie('nick', $nick, time() + (86400 * 30), "/"); // 86400 = 1 day
              setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            header("Location: escachat.php");
          }
          else{
            echo "<br><h3>WRONG EMAIL OR PASS</h3>";
          }
        }
      ?>
  </body>
</html>
