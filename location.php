<?php

session_start();
$diaryContent="";
if(array_key_exists('id',$_COOKIE)){
  $_SESSION['id']=$_COOKIE['id'];
}

if(!isset($_SESSION['id'])){
  header("Location: index.php");
}
else{
  
  
  include("connection.php");
  $query="SELECT `diary` FROM `users` WHERE `id` = ".mysqli_real_escape_string($link, $_SESSION['id']).";";
  if($result=mysqli_query($link, $query)){
    $row = mysqli_fetch_array($result);
    $diaryContent=$row['diary'];
  }

}
//print_r($_SESSION);
//print_r($_COOKIE);

?>

<?php include("header.php");?>




  <body>
    
    
    <nav class="navbar navbar-dark bg-dark" id="locationNavBar">
      <a class="navbar-brand">Secret Diary</a>
      <form class="form-inline" id="logout">
        <a class="btn btn-outline-success my-2 my-sm-0" href="index.php?logout=1" >Logout</a>
      </form>
    </nav>    
    
    
    <div class="container-fluid" id="locationPageContainer">
      <textarea class="form-control" id="diary"><?php echo $diaryContent; ?></textarea>
    </div>

    
<?php include("footer.php");?>