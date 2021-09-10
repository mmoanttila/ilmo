<?php
/*** PREVENT THE PAGE FROM BEING CACHED BY THE WEB BROWSER ***/
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require_once("wp-authenticate.php");

/*** REQUIRE USER AUTHENTICATION ***/
login();

require_once("lib.php"); /* Ainakin test_input ja addcsv */

$nro = $nimi = $tapahtuma ="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
    $tapahtuma = test_input($_POST["Tapahtuma"]);
	
	$added=addcsv ( $nro, $nimi, $tapahtuma . ".csv" );
	if ( $added == FALSE ) {
		echo "<pre>Tiedoston " . $tapahtuma . ".csv kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
	} else {
	}

}

/*** RETRIEVE LOGGED IN USER INFORMATION ***/
$user = wp_get_current_user();

// if (($open = fopen($tapahtuma . ".csv", "r")) !== FALSE) 
if (($open = fopen("Murtamonsankicrossi_2021.csv", "r")) !== FALSE) 
{
	while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
    {        
      $array[] = $data; 
	}
    fclose($open);
}
    echo "<H2>Moro " . $user->user_firstname . "</H2>\n";
	echo "<H5>" . $tapahtuma . ": </H5>\n";
    $sorted = array();
    sort ($array, SORT_NUMERIC);
    $line = 0;
    foreach($array as $row)
	{
	  $sorted[$line][0] = $row[0];
	  $sorted[$line][1] = $row[1];
         $line++;
	}
    echo "<style> .short-width td {   width: 10%; } </style>";
    echo "<table style=\"width:50%\">\n";
	echo "  <col style=\"width:10%\"/><col style=\"width:90%\"/>\n";
    echo "  <thead><tr><th class=\"short-width\">Nro:</th><th>Nimi:</th></tr></thead>\n";
	echo "  <tbody>\n";
    echo "<form method=\"POST\">";
	echo "<input type=\"hidden\" name=\"Tapahtuma\" value=\"Murtamonsankicrossi_2021\">\n";
    echo "<tr><td class=\"short-width\"><input type=\"number\" name=\"Nro\" value=\"\"></td><td><input type=\"text\" name=\"Nimi\" value\"\"></td></tr>\n";
    echo "<tr><td colspan=\"2\"><input type=\"Submit\" value=\"Lisää\"></td></tr>\n";
    echo "</form>";
    echo "<tr>";
    sort($sorted);
    foreach($sorted as $row)
        {
        $index=0;
        foreach($row as $col)
  	    {
                echo "<td><input name='var".$index."' type='text' value='".$col."'></td>";
    	        $index++;
  	    }
        echo "</tr>\n";
        }
    echo "</tbody></table>\n";
    echo "<br><hr>\n";
    echo "<pre>";
    //To display array data
    print_r(json_encode($sorted, JSON_PRETTY_PRINT));
    echo "</pre>";
