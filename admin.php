<?php
/* vim: set ai sw=2 fileencoding=utf-8 expandtab */
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
echo "<H2>Moro " . $user->user_firstname . "</H2>\n";
echo "<H5>Tapahtuma: </H5><input type=\"text\" size=\"30\" name=\"tapahtuma\" value=\"" . $tapahtuma . "\"></input>\n";
$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (is_uploaded_file($_FILES['csv-file']['tmp_name'])) { // Vastaanotettiin CSV-tiedosto
		if (file_exists($file)) { // varmuuskopio vanhasta jos sellainen on
			rename ($file, $file . ".bak");
		}
		move_uploaded_file($_FILES['csv-file']['tmp_name'], $file);
	}
	if (is_numeric($nro) and !empty($nimi)) { // Pitää antaa numero ja jotain nimeksi
		$nro = test_input($_POST["Nro"]);
		$nimi = test_input($_POST["Nimi"]);
	
		$added=addcsv ( $nro, $nimi, $file );
		if ( $added == FALSE ) {
			echo "<pre>Tiedoston " . $tapahtuma . ".csv kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
		}
	}

}

$sorted = read_csv($file);
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet

    $line = 0;
    echo "<style> .short-width td {   width: 10%; } </style>";
    echo "<table style=\"border:1px\">\n";
	echo "  <col style=\"width:5%\"/><col style=\"width:75%\"/><col style=\"width:15%\"/><col style=\"width:5%\">\n";
    echo "  <thead><tr><th class=\"short-width\">Nro:</th><th>Nimi:</th><th class=\"tagi\">Tagi:</th><th>Paikalla</th></tr></thead>\n";
	echo "  <tbody>\n";
    echo "<form method=\"POST\">";
    echo "<tr><td class=\"short-width\"><input type=\"number\" name=\"Nro\" value=\"\"></td><td><input type=\"text\" name=\"Nimi\" value=\"\"></td><td colspan=\"3\" class=\"tagi\"></tr>\n";
    echo "<tr><td colspan=\"4\"><input type=\"Submit\" value=\"Lisää\"></td></tr>\n";
    echo "</form>";
    sort($sorted);
    foreach($sorted as $row)
        {
		echo "<tr>";
		echo "  <td class=\"nro\"><input type=\"number\" style=\"width:7em\" name=\"Nro\" value=\"" . $row[0] . "\"/></td>\n";
		echo "  <td class=\"nimi\"><input type=\"text\" size=\"30\" name=\"Nimi\" value=\"" . $row[1] . "\"/></td>\n";
		echo "  <td class=\"tagi\"><input type=\"text\" size=\"8\" name=\"Tagi\" value=\"" . $row[2] . "\"/></td>\n";
		echo "  <td class=\"paikalla\"><input type=\"checkbox\" name=\"Paikalla\" value=\"" . $row[3] . "\"/></td>\n";
        echo "</tr>\n";
        }
    echo "</tbody></table>\n";
    echo "<br><hr>\n";
	echo "<P>Lataa ilmoittautuneet CSV-tiedostona <a href=\"$file\">tästä</a>.\n";
?>
<form action="" method="post" enctype="multipart/form-data">
<p>Upload edited CSV-file:
<input type="file" name="csv-file" />
<input type="submit" value="Lähetä" />
</p>
</form>
<?php
    echo "<pre>";
	save_json (json_encode($sorted, JSON_PRETTY_PRINT));
    // To display array data
    // print_r($sorted);
    echo "</pre>";
}
?>
</body>
</html>
