<?php

include("connect.php");
   
if(isset($_POST['submit'])){
  if(empty($_POST['name']) || empty($_POST['password'])){
      $error = "Username or Password is invalid";
    }
    else
    {
      //define $user and $password
      $username=$_POST['name'];
      $password=$_POST['password']; 
      $valpas = md5($password);
      
      $query = mysqli_query($conn,"SELECT * FROM users WHERE password= '".stripslashes($valpas)."' AND name = '".stripslashes($username)."'");
    
      $rows = mysqli_num_rows($query);
      
    if($rows == 1)
    {
        //print("we are in");
        //header("Location : chart-chartjs.html");
        header("location:dash.html"); 
      
    }

    else
    {
        $error = "Username of password is invalid";
    }
  } 
}
//sign in form
if(isset($_POST['register']))
{
  
  $username = $_POST['name'];
  $password = $_POST['password'];
  $re_password = isset($_POST['re_password']);

  if(empty($_POST['password']) &&  empty($_POST['name']) && empty($_POST['re_password']))
  {
      $error = "Username or Password is invalid";

    }
      else
      { 

        if($_POST['password'] == $_POST['re_password'])
        {
          
          $query = mysqli_query($conn,"SELECT * FROM users WHERE name = '".stripslashes($username)."'");
          $rows = mysqli_num_rows($query);

          if($rows == 0)
          {
            $valpas = md5($password);

            $query = mysqli_query($conn,"INSERT INTO  users(name , password ) values ('$username','$valpas')");

            

            if($query)
            {

              //print('Succefuly Registered');
              //echo '<div class="alert alert-success">Success! Well done its submitted.</div>';
              echo "<script type='text/javascript'>alert('Succefuly Registered!')</script>";
            }
            else
            {
              echo "<script type='text/javascript'>alert('That user Already Exist!')</script>";
              //echo '<div class="alert alert-success">That user Already Exist</div>';
              //print('That user Already Exist'); 
            }
          
          }
              
        }else
        {
          echo "<script type='text/javascript'>alert('password dont match!')</script>";
          //echo '<script type="text/javascript" class="alert alert-success">password dont match</script>';
          //print('password dont match');
        }
    }
  }





?>
<!DOCTYPE html>
<html lang="en" class="full-height">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>KR MONITORING</title>
    <link rel="stylesheet" href="css/style1.css">

    <!-- Font Awesome -->
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    
    <link href="css/mdb.min.css" rel="stylesheet">

    <!-- Template styles -->
    <style>
        /* TEMPLATE STYLES */
        .flex-center {
            color: #fff;
        }
        .intro-1 {
            background-image:url("img/tool.jpg") ;
            background-size: cover;
            opacity : 0.9;
        }
        .navbar .btn-group .dropdown-menu a:hover {
            color: #000 !important;
        }

        .navbar .btn-group .dropdown-menu a:active {
            color: #fff !important;
        }

    </style>

</head>

<body>

    <header>

        <!--Navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark elegant-color-dark green fixed-top"  >
            <div class="container">
               
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="container text-center">
                      <h2 style = "color:#ffffff" padding-left: 2cm;><b>KR MONITORING SYSTEM</b></h2>
                    </div>
                </div>
            </div>
        </nav>
        <!--/.Navbar-->

        <!--Intro Section-->
        <section id="home" class="view intro-1 hm-black-strong">
            <div class="full-bg-img flex-center">
              
                 <div class="login-page">
                  <div class="form">
                    <form class="register-form" action="logi.php" method="post">
                      <input type="text" placeholder="Name" name="name" id="name" required/>
                      <input type="password" placeholder="Password" name="password" id="password" required/>
                      <input type="password" placeholder="Re_password" name="re_password" id="re_password" required/>
                      <button type="submit" name="register" id="register">create</button>
                      <p class="message">Already registered? <a href="#">Sign In</a></p>
                    </form>

                    <form class="login-form" action="logi.php" method="post">
                      <input type="text" placeholder="Username" id="name"  name="name" required/>
                      <input type="password" placeholder="Password" id="password" name="password" required/>
                      <button type="submit" name="submit" id="submit">login</button>
                      <p class="message">Not registered? <a href="#">Create an account</a></p>
                    </form>
                  </div>
                </div>
                <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                <script  src="js/index.js"></script>
           </div>
        </section>

    </header>
</body>
</html>