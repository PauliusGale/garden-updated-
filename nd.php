<?php

    ini_set('display_errors',1);
    error_reporting(1);

    $con = new mysqli('localhost', 'weather_data', 'peletrunas', 'weather_database');
   $con->select_db('weather_database');
    if($con->connect_errno) { echo $con->connect_error . PHP_EOL; exit(); }
?>

<!DOCTYPE html>
<html>
  <head>
   <title>Your interface</title>
   <meta charset="UTF-8">
   <meta name="keywords" content="HTML">
   <meta name="description" content="Sodas">
   <meta name="author" content="Grigalavicius">
   <link type="text/css" rel="stylesheet" href="main.css"/>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">


  </head>
  <body>
   <div class="container">
     <div class="name">
       <h1>  Garden interface </h1>
    </div>

    <section class="weather">
      <h2 class="hweather"> Weather </h2>
      <div class="weathertable">
      <table>
      <tr>
    <th>Date</th>
    <th>Temperature (°C)</th>
    <th>Humidity (%)</th>
    <th>Conditions</th>
     </tr>

        <?php
    $sql_emp = "SELECT * FROM weather_data  WHERE datetime < CURRENT_DATE+3 AND datetime > NOW();";

    $result = $con->query($sql_emp);

    if($result->num_rows > 0)
    {
        
        while($row = $result->fetch_array()) {
            echo "<tr>";
                echo "<td class='table-datecond'>";
                    echo !empty($row['datetime'])? htmlentities($row['datetime']) : (($row['datetime'] === NULL)? 'NULL' : (($row['datetime'] === FALSE)? 'FALSE' : (($row['datetime'] === 0)? '0' : (($row['datetime'] === 0.00)? '0.00' : ''))));
                echo "</td>";
                echo "<td class='table-degrees'>";
                    echo !empty($row['temp'])? htmlentities($row['temp']) : (($row['temp'] === NULL)? 'NULL' : (($row['temp'] === FALSE)? 'FALSE' : (($row['temp'] === 0)? '0' : (($row['temp'] === 0.00)? '0.00' : ''))));
                echo "</td>";
                echo "<td class='table-degrees'>";
                    echo !empty($row['humidity'])? htmlentities($row['humidity']) : (($row['humidity'] === NULL)? 'NULL' : (($row['humidity'] === FALSE)? 'FALSE' : (($row['humidity'] === 0)? '0' : (($row['humidity'] === 0.00)? '0.00' : ''))));
                echo "</td>";
                echo "<td class='table-datecond'>";
                    echo !empty($row['conditions'])? htmlentities($row['conditions']) : (($row['conditions'] === NULL)? 'NULL' : (($row['conditions'] === FALSE)? 'FALSE' : (($row['conditions'] === 0)? '0' : (($row['conditions'] === 0.00)? '0.00' : ''))));
                echo "</td>";
            echo "</tr>";
        }

        $con->free_result;
    }
    else
    {
        echo "<tr>";
            echo "<td colspan='8'>";
                echo "There are no data about our employees.";
            echo "</td>";
        echo "</tr>";
    }

    $con->close();
?>

      </table>
    </div>
    </section>
    <section class="meetings">
      <h2 class="hmeeting"> Events </h2>
      <div class="ulmeeting">
      <p class="meet-time"> Today </p>
      <ul>
        <li> 8:00 Viso ploto palaistymas </li>
        <li> 14:00 Išsausėjusios žemės palaistymas</li>
        <li> 17:30 Viso ploto apipurškimas nuo kirminų </li>
      </ul>
      <p class="meet-time"> Tomorrow </p>
      <ul>
        <li> 8:00 Viso ploto palaistymas </li>
        <li> 12:40 Trašų liejimas ant vaismedžių</li>
        <li> 13:00 Trašų liejimas ant uogų </li>
        <li> 13:40 Trašų liejimas ant gėlių</li>
      </ul>
      <p class="meet-time"> After tomorrow </p>
      <ul>
        <li> 15:00 Lauko patikra </li>
      </ul>
      <button class="plus">
      </button>
      <button class="minus">
      </button>
      </div>
    </section>
    <section class="todolist">
      <h2 class="htodolist"> To do list</h2>
      <div class="checktodolist">
      <label class="todocheck">
        <input type="checkbox" checked="checked">
        <span class="checkmark"></span>
        Nugriebti lapus
      </label>
      <br/>
      <label class="todocheck">
        <input type="checkbox">
        <span class="checkmark"></span>
        Išrauti piktžoles
      </label>
      <br/>
      <label class="todocheck">
        <input type="checkbox">
        <span class="checkmark"></span>
        Užpildyti baką vaismedžio trašomis
      </label>
      <br/>
      <label class="todocheck">
        <input type="checkbox">
        <span class="checkmark"></span>
        Nurinkti derlių
      </label>
      <br/>
      <button class="plus"></button> <button class="minus"> </button>
    </div>
    </section>
    <section class="recom">
      <h2 class="recom-h2"> Recommendations for today</h2>
      <div class="star">
      <svg height="100" width="100">
       <polygon  class="twinkle-star" points="50,5 20,99 95,39 5,39 80,99" style="fill:yellow;"/>
     </svg>
   </div>
     <p class="recom-p">
       Nors šiandien rytą planuojamas trumpalaikis lietus, laistymo operacijos rekomenduojama neatšaukti, nes žemei reikia daugiau vandens nei planujama iškristi kritulių. </p>
       <p class="recom-p">Nurinkti derlių rekomenduojama prieš apipurškimą nuo kirminų.</p>
       <p class="recom-p">Galimos skruzdžių invazijos prie obels.</p>
 </section>
 <img src="./sodas.png" alt="sodas" class="img1">
   </div>
  </body>
</html>
