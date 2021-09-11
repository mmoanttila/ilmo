<?php
/*** PREVENT THE PAGE FROM BEING CACHED BY THE WEB BROWSER ***/
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require_once("current.php");
require_once("lib.php"); /* Ainakin test_input ja addcsv */

$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
//    $tapahtuma = test_input($_POST["Tapahtuma"]);
	
	$added=addcsv ( $nro, $nimi, $tapahtuma . ".csv" );
	if ( $added == FALSE ) {
		echo "<pre>Tiedoston " . $tapahtuma . ".csv kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
	} else {
	}

}

echo "<html><head>\n";
echo "<title>Ilmo " . $tapahtuma . "</title></head>\n";
echo "<body>\n";

echo "<H2>" . $tapahtuma . " ilmoittautuminen</H2>\n";
?>
<style> .short-width td {   width: 10%; } </style>
<table style="width:50%">
<col style="width:10%"/><col style="width:90%"/>
<thead><tr><th class="short-width">Nro:</th><th>Nimi:</th></tr></thead>
<tbody>
<form method="POST">
<input type="hidden" name="Tapahtuma" value="">
<tr><td class="short-width"><input type="number" name="Nro" value=""></td><td><input type="text" name="Nimi" value""></td></tr>
<tr><td colspan="2"><input type="Submit" value="Lisää"></td></tr>
</form>
<tr>
<?php
$sorted = read_csv($file);
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet
    $line = 0;
    sort($sorted);
    foreach($array as $row)
    {
	  $index=0;
      foreach($row as $col)
  	  {
		echo "<td><input name='var".$index."' type='text' value='".$col."'></td>";
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
