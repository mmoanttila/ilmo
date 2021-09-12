<?php
/*** PREVENT THE PAGE FROM BEING CACHED BY THE WEB BROWSER ***/
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<!Doctype html>
<html lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php
echo "<title>Ilmo " . $tapahtuma . "</title>\n";
?>
<style> 
.nro { width: 5; } 
.nimi { width: 5; text-align: left; } 
.center { border: 1px solid black; text-align: center; }
.separator { border: 1px solid black; text-align: center; background: #b6b6b6; }
</style>
</head>
<body>
<?php
require_once("current.php");
require_once("lib.php"); /* Ainakin test_input ja addcsv */

$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
//    $tapahtuma = test_input($_POST["Tapahtuma"]);
	if (new_number($nro, $file)) { // numeroa ei vielä ole csv:ssä
		$added=addcsv ( $nro, $nimi, $file );
		if ( $added == FALSE ) {
			echo "<pre>Tiedoston " . $file . " kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
		} else {
		}
	} else {
?>
<pre>Ehdottamasi numero on jo käytössä, valitsepa joku muu numero!</pre>
<?php
	}
}

echo "<H2>" . $tapahtuma . " ilmoittautuminen</H2>\n";
?>
<form method="POST">
<table style="border:1px">
<col style="width:10%"/><col style="width:90%"/> 
<thead><tr><th class="nro">Nro:</th><th class="nimi">Nimi:</th></tr></thead>
<tbody>
<tr><td style="width:1px"><input type="number" name="Nro" max="9999" value=""></td><td><input type="text" name="Nimi" size="64" value""></td></tr>
<tr><td colspan="2" style="width:30%"><input type="Submit" value="Lisää"></td></tr>
</tbody></table>
<div class="separator">Ilmoittautuneet:</div>
<tr><td colspan="2" bgcolor="#b6b6b6" align="center">Ilmoittautuneet:</td></tr>
</form>
<table>
<tr>
<?php
$sorted = read_csv($file);
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet
    $line = 0;
    sort($sorted);
    foreach($sorted as $row)
    {
	  $index=0;
      foreach($row as $col)
  	  {
		echo "<td>" . $col . "</td>";
        $index++;
  	  }
      echo "</tr>\n";
    }
} // Saatiin luettua csv
echo "</tbody></table>\n";
echo "<br><hr>\n";
echo "<pre>";
save_json (json_encode($sorted, JSON_PRETTY_PRINT));
//To display array data
//print_r($sorted);
echo "</pre>";
?>
</body>
</html>
