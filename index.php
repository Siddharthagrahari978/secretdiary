<?php

session_start();

//print_r($_COOKIE);
//print_r($_SESSION);
if(isset($_GET["logout"])){
  unset($_SESSION['id']);
  setcookie("id","",time() - 60*60);
  $_COOKIE['id']="";
}

if(isset($_SESSION['id']) OR isset($_COOKIE['id'])){
  header("Location: location.php");
}


$alert="";


if($_POST){
  
  include("connection.php");
  
  $alert='<div class="alert alert-danger" style="margin-top:10px;margin-bottom:10px;" role="alert"><strong>There were error(s) in your form:</strong><br>';
  
  if($_POST['email'] != "" && $_POST['password'] != ""){
    
    $alert="";
    
    if($_POST["signUp"]=="1"){

      $query="SELECT * FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_POST['email'])."';";

      $result=mysqli_query($link, $query);
      if(mysqli_num_rows($result) > 0){

        $alert='<div class="alert alert-warning" role="alert">This Email have already been taken.</div>';

      } else {

        $query="INSERT INTO `users`(`email`,`password`) VALUES('".mysqli_real_escape_string($link, $_POST['email'])."','".mysqli_real_escape_string($link, $_POST['password'])."')";
          
        if(!mysqli_query($link, $query)){
          $alert='<div class="alert alert-info" role="alert">Couldn\'t sign you up, Please try again later...</div>';
        } else {
          //To find the current ID
          $row=mysqli_fetch_array(mysqli_query($link, "SELECT `id` FROM `users` where email = '".mysqli_real_escape_string($link, $_POST['email'])."'"));
            
          $currentId=$row['id'];

          $query="UPDATE `users` SET `password` = '".md5(md5($currentId).mysqli_real_escape_string($link, $_POST['password']))."' WHERE id = '".$currentId."'";
            
          mysqli_query($link, $query);

          $_SESSION['id'] = $currentId;

          if(isset($_POST['checkBox'])){
            setcookie("id",$currentId,time() + 60*60*24);
          }

          header("Location: location.php");
        }



      }
    
    
    }
    
    else if($_POST["signUp"]=="0"){
        
      $query="SELECT * FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_POST['email'])."';";
      
      $result  = mysqli_query($link, $query);
      
      
      
      if(mysqli_num_rows($result) > 0){
          
        $row = mysqli_fetch_array($result);
        
        $hashedPassword = md5(md5($row['id']).$_POST['password']);
        
        if($hashedPassword  == $row['password']){
          
          $_SESSION['id']=$row['id'];
          
          if(isset($_POST['checkBox'])){
            setcookie("id",$row['id'],time() + 60*60*24);
          }

          header("Location: location.php");       
          
          
        } else{$alert='<div class="alert alert-danger" role="alert">This Email/Password combination doesn\'t exists.</div>';}
        
      }else{$alert='<div class="alert alert-danger" role="alert">This Email doesn\'t exists.</div>';}
      
      
    }
    
    
  }
  else if($_POST['email'] == "" && $_POST['password'] != ""){
    $alert.="An email is required.<br></div>";
  }
  else if($_POST['email'] != "" && $_POST['password'] == ""){
    $alert.="A password is required.<br></div>";
  }
  else{
    $alert.="An email is required.<br>";
    $alert.="A password is required.<br></div>";
  }
}



?>



<?php include("header.php");?>
  


  <body>
    
    <div class="container col-lg-5" id="homePageContainer">
      
      <h1>Secret Diary</h1>
      <strong>Store your thoughts permanently and securely.<br></strong>
      
      
      <?php echo $alert;?>
       <br>
      <form method="post" id="signUpForm">
        <div class="form-group">
          <label for="signUpEmail">Interested? Sign up now.</label>
          <input type="email" class="form-control" id="signUpEmail" placeholder="Your Email" name="email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="signUpPassword" placeholder="Create Password" name="password">
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="checkBox" name="checkBox">
          <label class="form-check-label" for="checkBox">Stay logged in</label>
        </div>
        <input type="hidden" value="1" id="signUp" name="signUp">
        <button type="submit" class="btn btn-primary" id="submit">Sign Up!</button>
        <br>
         
        <br>
        <a type="button" class="btn btn-link signSwitch">Sign in</a>
      </form>
      
      
      
      
      <form method="post" id="logInForm">
        <div class="form-group">
          <label for="signInEmail1">Log in using your email and password.</label>
          <input type="email" class="form-control" id="signInEmail1" placeholder="Your Email" name="email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="signInPassword" placeholder="Your Password" name="password">
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="checkBox1" name="checkBox">
          <label class="form-check-label" for="checkBox1">Stay logged in</label>
        </div>
        <input type="hidden" value="0" id="signUp" name="signUp">
        <button type="submit" class="btn btn-primary">Login</button>
        <br>
         
        <br>
        <a type="button" class="btn btn-link signSwitch">Sign Up</a>
      </form>
      
    </div>
  
<?php include("footer.php"); ?>
</body>