<?php 

$username = "sidgupta";                       // Use your username
$password = "Iamaverygoodboy1998";                      // and your password
$database = "oracle.cise.ufl.edu/orcl"; 

$query = "WITH CalculateTFR(country_name,year,total_fertility_rate)
AS (SELECT f.country_name,f.year,
    ((f.fertility_rate_15_19 + f.fertility_rate_20_24 + f.fertility_rate_25_29 + f.fertility_rate_30_34 + f.fertility_rate_35_39 + f.fertility_rate_40_44 + f.fertility_rate_45_49) * 5) as TFR
    FROM JAYETRI.fertility f
    where f.country_name = 'India'
       and f.year > 2000
       and f.year < 2010)
       
SELECT country_name,year,total_fertility_rate, 
       fertility_women, unfertility_women, round(fertility_women/unfertility_women,2) as child_bearing_ratio
FROM ( select country_name,year, (population_age_11_to_20+
                                 population_age_21_to_30 +
                                  population_age_31_to_40 +
                                  population_age_41_to_50) 
                                as fertility_women
       from JAYETRI.population_gender 
       where country_name = 'India'
          and year > 2000
          and year < 2010
          and sex = 'Female'
          and ROWNUM <= 5
       order by year ASC) natural join
       (select country_name,year, (population_age_0_to_10 +
                                   population_age_51_to_60 +
                                   population_age_61_to_70+
                                   population_age_71_to_80 +
                                   population_age_81_to_90 +
                                   population_age_91_to_100) as unfertility_women
        from JAYETRI.population_gender
        where country_name = 'India'
           and year > 2000

           and year < 2050
           and sex = 'Female'
        order by year ASC) natural join
        CalculateTFR
where (country_name,year) in (select country_name,year
                            from ISWARYA.country)


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
$chart_data1 = " ";
while(($row = oci_fetch_array($s, OCI_BOTH))!= false){
  //$data[] = $row;
  //'" < These quotes + Double quotes below on year represent X-Axis > "'
     $chart_data .= "{year:'".$row["YEAR"]."',child_bearing_ratio:".$row["CHILD_BEARING_RATIO"]."}, ";
     $chart_data1 .= "{year:'".$row["YEAR"]."',fertility_women:".$row["FERTILITY_WOMEN"].",unfertility_women:".$row["UNFERTILITY_WOMEN"]."}, ";
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
  <div class="container">
   <!--<h2 align="center">Morris.js chart with PHP & Mysql</h2>-->
   <h1 align="center">Child Bearing Ratio</h1>   
   <br /><br />
   <div id="chart"></div>
   <br><br>
   <h1 align="center">Woman Fertility</h1> 
   <div id="chart1"></div>
   
  </div>
</center>
 </body>
</html>

<script>
Morris.Bar({
 element:'chart',
 data:[<?php echo $chart_data; ?>],
 xkey:'year',
 ykeys:['child_bearing_ratio'],
 labels:['Ratio'],
 hideHover:'auto',
 stacked:false

});
Morris.Line({
 element:'chart1',
 data:[<?php echo $chart_data1; ?>],
 xkey:'year',
 ykeys:['fertility_women','unfertility_women'],
 labels:['Fertility_women','Unfertility_women'],
 hideHover:'auto',
 stacked:true

});
</script>