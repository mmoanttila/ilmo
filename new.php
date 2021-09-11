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
	
	$added=addcsv ( $nro, $nimi, $file );
	if ( $added == FALSE ) {
		echo "<pre>Tiedoston " . $file . " kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
	} else {
	}

}
?>
<!Doctype html>
<html lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
echo "<title>Ilmo " . $tapahtuma . "</title>\n";
?>
<style> .short-width td {   width: 10px; } </style>
</head>
<body>
<?php
echo "<H2>" . $tapahtuma . " ilmoittautuminen</H2>\n";
?>
<table border="1" style="width:50%">
<!-- <col style="width:10%"/><col style="width:90%"/> -->
<thead><tr><th style="width:1px">Nro:</th><th>Nimi:</th></tr></thead>
<tbody>
<form method="POST">
<input type="hidden" name="Tapahtuma" value="">
<tr><td style="width:1px"><input type="number" name="Nro" size="4" value=""></td><td><input type="text" name="Nimi" value""></td></tr>
<tr><td colspan="2" align="center"><input type="Submit" value="Lisää"></td></tr>
<tr><td colspan="2" bgcolor="#b6b6b6" align="center">Ilmoittautuneet:</td></tr>
</form>
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
