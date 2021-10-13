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
echo "<form method=\"post\">\n";
echo "<input type=\"hidden\" name=\"new\" value=\"1\">\n";
echo "<B>Tapahtuma: </B><input type=\"text\" size=\"30\" name=\"tapahtuma\" value=\"" . $tapahtuma . "\"></input>\n";
echo "<B>PVM: </B><input type=\"text\" size=\"10\" name=\"pvm\" value=\"" . $pvm . "\"></input>\n";
echo "</form><br>\n";
$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (is_uploaded_file($_FILES['csv-file']['tmp_name'])) { // Vastaanotettiin CSV-tiedosto
		if (file_exists($file)) { // varmuuskopio vanhasta jos sellainen on
			rename ($file, $file . ".bak");
		}
		move_uploaded_file($_FILES['csv-file']['tmp_name'], $file);
	}
	if ( $_POST["edit"] == "1" ) { // Yritetään korjata yhtä riviä
		$rivi = test_input($_POST["rivi"]);
		$nro = test_input($_POST["Nro"]);
		$nimi = test_input($_POST["Nimi"]);
		$tagi = test_input($_POST["Tagi"]);
		$paikalla = test_input($_POST["Paikalla"]);
		$sorted = read_csv($file);
		sort($sorted);
		if ($sorted !== FALSE ) { // Saatiin csv auki
			/* echo "<pre>";
			print_r ($sorted[$rivi]);
			echo "</pre>\n"; */
			$sorted[$rivi] = array( "0"=>$nro, "1"=>$nimi, "2"=>$tagi, "3"=>$paikalla );
			/* echo "<pre> => ";
			print_r ($sorted[$rivi]);
			echo "</pre>\n"; */
			save_csv($sorted, $file);
		}

	}
	if ( $_POST["new"] == "1" ) { // Lisätään uusi tapahtuma

	}

	// Täähän ei voi toimia, kun ei noita muuttujia ole vielä luettu POST:sta
	if ( is_numeric($nro) and !empty($nimi) and !empty($_POST["add"] ) ) { // Pitää antaa numero ja jotain nimeksi
		$nro = test_input($_POST["Nro"]);
		$nimi = test_input($_POST["Nimi"]);
	
		$added=addcsv ( $nro, $nimi, $file );
		if ( $added == FALSE ) {
			echo "<pre>Tiedoston " . $tapahtuma . ".csv kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
		}
	}

}

if (sizeof($sorted) == "0" ) { // Käytetäänkö jo edellä luettua&editoitua taulukkoa
	$sorted = read_csv($file);
}
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet

    echo "<style> .short-width td {   width: 5em; } </style>";
    echo "<table border=\"1px\">\n";
//	echo "  <col style=\"width:5%\"/><col style=\"width:75%\"/><col style=\"width:15%\"/><col style=\"width:5%\">\n";
    echo "  <thead><tr><th class=\"short-width\">Nro:</th><th>Nimi:</th><th class=\"tagi\">Tagi:</th><th colspan=\"3\">Paikalla</th></tr></thead>\n";
	echo "  <tbody>\n";
    echo "<form method=\"POST\">";
    echo "<tr><td><input style=\"width:4em\" type=\"number\" name=\"Nro\" value=\"\"></td><td><input type=\"text\" name=\"Nimi\" size=\"24\" value=\"\"></td><td colspan=\"4\" class=\"tagi\"></tr>\n";
    echo "<tr><td colspan=\"6\"><input type=\"Submit\" value=\"Lisää\"></td></tr>\n";
    echo "</form>\n";
    sort($sorted);

    $line = $paikalla = 0;
    foreach($sorted as $row)
        {
		echo "<tr>\n";
		echo "  <form action=\"$SCRIPT_NAME\" method=\"POST\"><input type=\"hidden\" name=\"edit\" value=\"1\"><input type=\"hidden\" name=\"rivi\" value=\"$line\"/>";
		echo "  <td class=\"nro\"><input type=\"number\" style=\"width:4em\" name=\"Nro\" value=\"" . $row[0] . "\"/></td>\n";
		echo "  <td class=\"nimi\"><input type=\"text\" size=\"24\" name=\"Nimi\" value=\"" . $row[1] . "\"/></td>\n";
		echo "  <td class=\"tagi\"><input type=\"text\" size=\"8\" name=\"Tagi\" value=\"" . $row[2] . "\"/></td>\n";
		echo "  <td class=\"paikalla\"><input type=\"checkbox\" name=\"Paikalla\" value=\"1\"";
		if ($row[3]=="1") { $paikalla++; echo " checked "; }
		echo "/></td>\n";
		echo "  <td class=\"submit\"><input type=\"submit\" name=\"mode\" value=\"Muuta\"/></td>\n";
		echo "  <td class=\"submit\"><input type=\"submit\" name=\"mode\" value=\"Poista\"/></td>\n";
		echo "  </form>\n";
        echo "</tr>\n";
		$line++;
        }
    echo "</tbody></table>\n";
	echo "<P>Paikalla " . $paikalla . "/" . sizeof($sorted) . " kuskia.</P>\n";
    echo "<br><hr>\n";
	echo "<P>Lataa ilmoittautuneet CSV-tiedostona <a href=\"$file\">tästä</a>.\n";
?>
<form action="" method="post" enctype="multipart/form-data">
<p>Päivitä editoitu CSV-file:
<input type="file" name="csv-file" />
<input type="submit" value="Lähetä" />
</p>
</form>
<?php
    echo "<pre>";
	// save_json (json_encode($sorted, JSON_PRETTY_PRINT));
	save_csv ( $sorted );
    // To display array data
    // print_r($sorted);
    echo "</pre>";
}
?>
</body>
</html>
