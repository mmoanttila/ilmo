<?php
/*** PREVENT THE PAGE FROM BEING CACHED BY THE WEB BROWSER ***/
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require_once("wp-authenticate.php");

/*** REQUIRE USER AUTHENTICATION ***/
login();

require_once("current.php");
require_once("lib.php"); /* Ainakin test_input ja addcsv */
?>
<!Doctype html>
<html lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
	
	$added=addcsv ( $nro, $nimi, $file );
	if ( $added == FALSE ) {
		echo "<pre>Tiedoston " . $tapahtuma . ".csv kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
	} else {
	}

}

$sorted = read_csv($file);
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet

    echo "<H2>Moro " . $user->user_firstname . "</H2>\n";
	echo "<H5>" . $tapahtuma . ": </H5>\n";
    $line = 0;
    echo "<style> .short-width td {   width: 10%; } </style>";
    echo "<table>\n";
	echo "  <col style=\"width:5%\"/><col style=\"width:80%\"/><col style=\"width:15%\"/>\n";
    echo "  <thead><tr><th class=\"short-width\">Nro:</th><th>Nimi:</th><th class=\"tagi\">Tagi:</th></tr></thead>\n";
	echo "  <tbody>\n";
    echo "<form method=\"POST\">";
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
	save_json (json_encode($sorted, JSON_PRETTY_PRINT));
    //To display array data
    print_r($sorted);
    echo "</pre>";
}
?>
</body>
</html>
