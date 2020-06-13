
<?php 

$username = "sidgupta";                       // Use your username
$password = "Iamaverygoodboy1998";                      // and your password
$database = "oracle.cise.ufl.edu/orcl"; 

$query = "SELECT COUNT_POPULATION_AGE + NN AS TOTAL_TUPLES FROM
(SELECT COUNT(*) AS COUNT_POPULATION_AGE FROM ISWARYA.POPULATION_AGE) ,
(SELECT COUNT_POPULATION + MM AS NN FROM
(SELECT COUNT(*) AS COUNT_POPULATION FROM ISWARYA.POPULATION),
(SELECT COUNT_MIGRATION + LL AS MM FROM
(SELECT COUNT(*) AS COUNT_MIGRATION FROM ISWARYA.MIGRATION),
(SELECT COUNT_GROWTH_RATE + KK AS LL FROM
(SELECT COUNT(*) AS COUNT_GROWTH_RATE FROM ISWARYA.GROWTH_RATE),  
(SELECT COUNT_Female_life_expectancy + JJ AS KK FROM
(SELECT COUNT(*) AS COUNT_Female_life_expectancy FROM ISWARYA.Female_life_expectancy), 
(SELECT COUNT_Male_life_expectancy + II AS JJ FROM
(SELECT COUNT(*) AS COUNT_Male_life_expectancy FROM ISWARYA.Male_life_expectancy),
(SELECT COUNT_LIFE_EXPECTANCY + HH AS II FROM
(SELECT COUNT(*) AS COUNT_LIFE_EXPECTANCY FROM ISWARYA.LIFE_EXPECTANCY),
(SELECT COUNT_Birth_death_rate + GG AS HH FROM
(SELECT COUNT(*) AS COUNT_Birth_death_rate FROM ISWARYA.Birth_death_rate),
(SELECT COUNT_COUNTRY + FF AS GG FROM
(SELECT COUNT(*) AS COUNT_COUNTRY FROM ISWARYA.COUNTRY), 
(SELECT COUNT_AREA + EE AS FF FROM
(SELECT COUNT(*) AS COUNT_AREA FROM iswarya.AREA),
(SELECT COUNT_POPULATION_GENDER + DD AS EE FROM
(SELECT COUNT(*)AS COUNT_POPULATION_GENDER FROM jayetri.POPULATION_GENDER),
(SELECT  COUNT_MORTALITY_MALE + CC AS DD FROM(
SELECT COUNT(*) AS COUNT_MORTALITY_MALE FROM jayetri.MORTALITY_MALE),
(SELECT  COUNT_MORTALITY_FEMALE + BB  AS CC FROM(
SELECT COUNT(*) AS COUNT_MORTALITY_FEMALE FROM jayetri.MORTALITY_FEMALE),
(SELECT  AA +COUNT_MORTALITY AS BB FROM(
SELECT COUNT(*) AS COUNT_MORTALITY FROM jayetri.MORTALITY), 
(SELECT COUNT_FIVE_YEAR_MID_POPULATION + COUNT_FERTILITY AS AA FROM(
SELECT COUNT(*) AS COUNT_FIVE_YEAR_MID_POPULATION FROM jayetri.five_year_mid_population ),
(SELECT COUNT(*) AS COUNT_FERTILITY FROM jayetri.FERTILITY)))))))))))))))


";

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

$chart_data = " ";
while(($row = oci_fetch_array($s, OCI_BOTH))!= false){
  //$data[] = $row;
  //'" < These quotes + Double quotes below on year represent X-Axis > "'
     $chart_data .= $row["TOTAL_TUPLES"];
 // $chart_data .= "{ year: '".$row["YEAR"]."', a: ".$row["AVERAGE_LIFE_EXPECTANCY"].",b: ".$row["AVERAGE_LIFE_EXPECTANCY_FEMALE"].",c: ".$row["AVERAGE_LIFE_EXPECTANCY_MALE"]."}, ";
}


//To remove last comma from $chart_data
//$chart_
//echo $chart_data;data = substr($chart_data, 0, -1);
//echo $chart_data;

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
 
  <style>

  .text1{
        text-align: right;
        }
  .text2{
        text-align: right;
        color: Darkcyan;
         }

  .text3{
         text-align: right;
         padding-top: 8px;
         color: white;
         background-color: blue;
         }

  .img1{
         border: solid 1px black;
         }

  .ddown1{
        padding-top: 20px;
        }

  .ddown2{
        width: 250px;
        height: 35px;
        border: solid 0.5px black;
        font-weight: 600;
        color: DarkCyan;
        font-family: ;
        }                                             
     
    #rectangle{
               height: 100px;
               border-radius: 2px;
               border: solid 0.5px #d4d4d4;
               }
    
  </style>
</head>
<body>

<div class="container">
  <div class="row">
    <ul class="nav nav-tabs ">
  <li><a href="home.php"><h4 id="home">Home<h4></a></li>
  <li><a href="5.php"><h4 id="naturalincrease">Population </h4></a></li>
  <li><a href="6.php"><h4 id="populationdensity">Population Density</h4></a></li>
  <li><a href="7.php"><h4 id="sexratio">Sex Ratio</h4></a></li>
  <li><a href="3.php"><h4 id="mortality">Mortality</h4></a></li>
  <li><a href="2.php"><h4 id="childbearingyears">Childbearing Years</h4></a></li>
  <li><a href="1.php"><h4 id="agedependency">Age Dependency</h4></a></li>
  <li><a href="4.php"><h4 id="lifeexpectancy">Life Expectancy</h4></a></li>
    </ul>
  </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="col-lg-7">
          <h3 class ="text1">HOME</h3>
        </div>
        <div class="col-lg-4 text2">
             <div onclick="myFunction()"><h4>Tuple Count</h4></div>
             <p id="demo"></p>
        </div>
        <div class="col-lg-1">
          <a href="LOGIN PAGE.php"><h4 class="text1" id="loginpage">Log Out</h4></a>
        </div>
      </div>
    </div>
</div>
<div class="container">
	<div class="row">
       <div class="col-lg-12">
       	<br></br>
       	<div class="col-lg-8 img1">
	     <img src="log1.png"  alt="Cinque Terre" width="708" height="400">
        </div>
       <div class="col-lg-4 img2">
       	<h4>The world population reached 7.6 billion as of mid-2017.</h4> 
        <h4>The world has added approximately one billion inhabitants over the last twelve years.</h4>
        <h4>The world’s population is growing by 1.10 percent per year, or approximately an additional 83 million people annually.</h4> 
        <h4>The global population is expected to reach 8.6 billion in 2030, 9.8 billion in 2050 and 11.2 billion in 2100. </h4>
        <h4>50.4 percent of the world’s population is male and 49.6 percent is female. </h4>
        <h4>The median age of the global population, that is, the age at which half the population is older and half is younger, is 30 years. </h4>
       </div>
    </div>
</div>
  




</body>
</html>

<script>
function myFunction() {
  document.getElementById("demo").innerHTML = <?php echo $chart_data; ?>;
}
</script>
<script type="text/javascript">
    document.getElementById("naturalincrease").onclick = function () {
        location.href = "5.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("populationdensity").onclick = function () {
        location.href = "6.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("sexratio").onclick = function () {
        location.href = "7.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("mortality").onclick = function () {
        location.href = "3.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("childbearingyears").onclick = function () {
        location.href = "2.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("agedependency").onclick = function () {
        location.href = "1.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("lifeexpectancy").onclick = function () {
        location.href = "4.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("home").onclick = function () {
        location.href = "home.php";
    };
</script>

<script type="text/javascript">
    document.getElementById("loginpage").onclick = function () {
        location.href = "LOGIN PAGE.php";
    };
</script>

	  
	  