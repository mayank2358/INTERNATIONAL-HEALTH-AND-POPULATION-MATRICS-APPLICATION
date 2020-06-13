<?php 

$username = "sidgupta";                       // Use your username
$password = "Iamaverygoodboy1998";                      // and your password
$database = "oracle.cise.ufl.edu/orcl"; 

$query = "SELECT * FROM(
SELECT DISTINCT C.country_name, C.net_migration AS migration_in_2020, C.rate_natural_increase*1000 AS natural_increase_2020, 
                W.net_migration AS migration_in_2000, W.rate_natural_increase*1000 AS NATURAL_INCREASE_2000
FROM(SELECT DISTINCT country_name, rate_natural_increase, net_migration
    FROM ISWARYA.birth_death_rate  NATURAL JOIN Iswarya.Migration 
    WHERE year =2020) C,
    (SELECT DISTINCT country_name, Rate_natural_increase, Net_Migration
    FROM ISWARYA.birth_death_rate  NATURAL JOIN Iswarya.Migration 
    WHERE year =2000) W

    WHERE C.country_name = W.country_name
    AND C.net_migration - W.net_migration > C.rate_natural_increase- W.rate_natural_increase
	AND C.country_name IN

(SELECT DISTINCT X.country_name AS COUNTRIES
        FROM(SELECT DISTINCT P1.country_name, P1.midyear_population/A1.Area AS population_density 
        FROM Iswarya.Population P1, Iswarya.Area A1
        WHERE P1.country_name =A1.country_name
        AND P1.year =2020) X,

        (SELECT DISTINCT P2.country_name,P2.midyear_population/A2.area AS population_density 
        FROM Iswarya.Population P2, Iswarya.Area A2
        WHERE P2.country_name =A2.country_name
        AND P2.year =2000)Y 
        
        WHERE X.country_name = Y.country_name
        AND ROWNUM<=200
        AND X.population_density > Y.population_density
        GROUP BY X.COUNTRY_NAME)
        AND ROWNUM <=20
        ORDER BY C.net_migration- W.net_migration)
 
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
     $chart_data .= "{ country_name:'".$row["COUNTRY_NAME"]."', natural_increase_2020:".$row["NATURAL_INCREASE_2020"].",migration_in_2000:".$row["MIGRATION_IN_2000"]."}, ";
 // $chart_data .= "{ year: '".$row["YEAR"]."', a: ".$row["AVERAGE_LIFE_EXPECTANCY"].",b: ".$row["AVERAGE_LIFE_EXPECTANCY_FEMALE"].",c: ".$row["AVERAGE_LIFE_EXPECTANCY_MALE"]."}, ";
}
//To remove last comma from $chart_data
$chart_data = substr($chart_data, 0, -2);
//echo $chart_data;

?>


<!DOCTYPE html>
<html>
 <head>
  <title>Webslesson Tutorial | How to use Morris.js chart with PHP & Mysql</title>
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
</div>
  <center>
  <br /><br />
  <div class="container" style="width:900px;">
   <!--<h2 align="center">Morris.js chart with PHP & Mysql</h2>-->
   <h1 align="center">Population Growth</h3>   
   <br /><br />
   <div id="chart"></div>
   <div id="chart1"></div>
  </div>
</center>
 </body>
</html>

<script>
Morris.Bar({
 element:'chart1',
 data:[<?php echo $chart_data; ?>],
 xkey:'country_name',
 ykeys:['natural_increase_2020','migration_in_2000'],
 labels:['Natural_increase','Net_Migration'],
 hideHover:'auto',
 stacked:true
});
</script>