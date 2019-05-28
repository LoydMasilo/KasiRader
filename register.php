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
      
      $query = mysqli_query($conn,"SELECT * FROM users WHERE password='$password' AND name = '$username'");
    
      $rows = mysqli_num_rows($query);
      
    if($rows == 1)
    {
        //print("we are in");
        //header("Location : chart-chartjs.html");
        header("location:dashboard/dashboard.html"); 
      
    }

    else
    {
        $error = "Username of password is invalid";
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
                    
                    <h2 style = "color:#ffffff"><b>KR MONITORING SYSTEM</b></h2>
                
                </div>
            </div>
        </nav>
        <!--/.Navbar-->

        <!--Intro Section-->
        <section id="home" class="view intro-1 hm-black-strong">
            <div class="full-bg-img flex-center">
              
                 <div class="login-page">
                  <div class="form">
                   

                    <form class="login-form" action="logi.php" method="post">
                      <input type="text" placeholder="username" id="name"  name="name" required/>
                      <input type="password" placeholder="password" id="password" name="password" required/>
                      <button type="submit" name="submit" id="submit">login</button>
                      <p class="message">Not registered? <a href="#">Create an account</a></p>
                    </form>
                  </div>
                </div>
                <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                <script  src="js/index.js"></script>
           
        </section>

    </header>
</body>
</html>