<?php

  $username = "sidgupta";   	                  // Use your username
  $password = "Iamaverygoodboy1998";	    	              // and your password
  $database = "oracle.cise.ufl.edu/orcl";   // and the connect string to connect to your database
 
  $query = "INSERT INTO test_proj values('Parth','Gupta')";
   echo 'Congratulations! You are connected to oracle!';
  $c = oci_connect($username, $password, $database);
  if (!$c) {
    $m = oci_error();
    trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
  }
  $s = oci_parse($c, $query);
  if (!$s) {
      $m = oci_error($c);
      trigger_error('Could not parse statement: '. $m['message'], E_USER_ERROR);
  }
  $r = oci_execute($s);
  if (!$r) {
      $m = oci_error($s);
      trigger_error('Could not execute statement: '. $m['message'], E_USER_ERROR);
  }

?>

______________________________________________________________________________________________________________________________________________

<!DOCTYPE html>
<html lang="en">
<head>
<title>Bootstrap Example</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="2.jpg" alt="Los Angeles" style="width:1700px;height:400px;">
    </div>

    <div class="item">
      <img src="5.jpg" alt="Chicago" style="width:1700px;height:400px;">
    </div>

    <div class="item">
      <img src="1.jpg" alt="New York" style="width:1700px;height:400px;">
    </div>
  </div>

 
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<div class="container">
  <h1></h1>
</div>

<div class="container">
  <div class="jumbotron">
    <h1>DATABASE HEADING</h1>
    <p>Database for population,age,states,life expectacy,growth and much more.</p>
  </div>
</div>

<div class="container">
  <form action="/action_page.php">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
    </div>
    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div>
  </form>
</div>

<div class="container">
  <a href="home.php"  button type="button" class="btn btn-primary" id="home" >Primary</button>
</div>

</body>
</html>




	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  