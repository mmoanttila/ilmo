<?php
/* vim: set ai sw=2 fileencoding=utf-8 expandtab */
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
require_once("current.php");
$now = localtime(time(), true);
$yy = $now['tm_year']+1899;
$mm = $now['tm_mon']+1;
$dd = $now['tm_mday'];
$pvm_now=sprintf ("%u-%02u-%02u", $yy, $mm, $dd);
if ( $pvm < $pvm_now) { // Onko tuleva vai vanha tapahtumaa
  echo "<title>Ilmo EX-" . $tapahtuma . " " . $pvm ."</title>\n";
} else {
  echo "<title>Ilmo " . $tapahtuma . " " . $pvm ."</title>\n";
}
?>
<style> 
.nro { width: 3em; } 
.nimi { width: 10em; text-align: left; } 
.center { border: 1px solid black; text-align: center; }
.separator { border: 1px solid black; text-align: center; background: #b6b6b6; }
.submit { height: 2em; width: 5em; justify-content: center; align-items: center;}
.ilmo { border: 1px solid black; }

#nayta_ehdot {
  top: 0;
  right: 100%;
  bottom: 100%;
  left: 0;
  pointer-events: none;
  opacity: 0;
  transition: opacity 200ms;
  background-color: #34495E;
}
#nayta_ehdot:target {
  pointer-events: all;
  opacity: 1;
}
#nayta_ehdot #target-inner {
  position: absolute;
  display: block;
  padding: 12px;
  line-height: 1.5;
  width: 70%;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-20%);
  box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.5);
  background: white;
}
#nayta_ehdot a.close {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  opacity: 0.5;
  transition: opacity 200ms;
}

</style>
</head>
<body>
<?php
require_once("current.php");
require_once("lib.php"); /* Ainakin test_input ja addcsv */
echo "<H2>" . $tapahtuma . " " . $pvm . " ilmoittautuminen</H2>\n";

$nro = $nimi = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
	if (is_numeric($nro) and !empty($nimi)) { // Pitää antaa numero ja jotain nimeksi
//    $tapahtuma = test_input($_POST["Tapahtuma"]);
		if (new_number($nro, $file)) { // numeroa ei vielä ole csv:ssä
			$added=addcsv ( $nro, $nimi, $file );
			if ( $added == FALSE ) {
				echo "<pre>Tiedoston " . $file . " kirjoitus ei onnistunut (" . $nro . "," . $nimi . ")!!</pre>\n";
			} 
		} else {
?>
<H4 style="color:Tomato;">Ehdottamasi numero on jo käytössä, valitsepa joku muu numero</H4>
<?php
		}
	} else {
?>
<H4 style="color:Tomato;">Koitapa kirjoittaa jotakin nimeen ja numeroon.</H4>
<?php
	}
}

if ( $pvm >= $pvm_now) { // Onko tuleva vai vanha tapahtuma
?>
<div>
<form method="POST">
<div class="nro"><label for="nro">Nro: </label><input id="nro" type="number" style="width:7em" name="Nro" max="9999" value="" required></div>
<div class="nimi"><label for="nimi">Nimi: </label><input id="nimi" type="text" name="Nimi" size="30" value="" required></div>
<br>
<div class="ehdot">Olen lukenut <a href="#nayta_ehdot">Karhuenduron säännöt</a> ja hyväksyn ne. <input id="ehdot" type="checkbox" name="ehdot" required></div>
<div id="nayta_ehdot">
  <div id="target-inner">
  <a href="#" class="close"></a>
    <H2>Säännöt</H2>
<ul>
<li>Ajaminen tapahtuu aina omalla vastuulla.</li>
<li>Kuljettajalla oltava asianmukaiset ajovarusteet.</li>
<li>Pyörässä tulee olla vähintään rekisteröimättöman maastoajoneuvon liikennevakuutus.</li>
<li>Pyörän ja erityisesti äänenvaimennuksen tulee olla kunnossa.</li>
<li>Pyörää huollettaessa on käytettävä huoltomattoa.</li>
<li>Varikolla ajetaan vain kävelyvauhtia.</li>
<li>Jokainen käyttäjä hoitaa omat roskansa pois alueelta.</li>
<li>Ajo on sallittu vain merkityllä reitillä.</li>
<li>Jokaisella kuljettajalla on auttamisvelvoite. Kanssakuljettajaa tulee auttaa esim.
    kaatuessa, mahdollisesti loukkaantuessa tai muuten apua tarvittaessa.</li>
<li>Polttoainesäiliöt on säilytettävä autoissa tai peräkärryssä.</li>
<li>Avotulen teko maastoon on ehdottomasti kielletty.</li>
<li>Tupakointi on sallittu vain erikseen niille varatuilla alueilla.</li>
<li>Ajotapahtumissa käytettävillä reiteillä ajo on sallittu ainoastaan ajotapahtumien aikana.</li>
<li>Ajotapahtumissa tulee olla vähintään kaksi 6 kg:n jauhesammutinta järjestäjän toimesta.</li>
<li>Ajotapahtumissa järjestäjä huolehtii, että paikalla on lapioita ja imeytysturvetta mahdollisten öljyvuotojen varalle.</li>
<li>Ennen ajotapahtuman alkua pidetään ajajakokous sekä ajetaan tutustumiskierros.</li>
</ul>
  </div>
</div>
<br>
<div class="submit" style="width:30%"><input class="submit" style="width:10em" type="Submit" value="Lisää"></div>
<br>
</form>
</div>
<?php
} // Oli tuleva tapahtuma
?>
<div class="separator">Ilmoittautuneet:</div>
<table class="ilmo">
  <thead><tr><th class="nro">Nro:</th><th class="nimi">Nimi:</th></thead>
  <tbody>

<?php
$sorted = read_csv($file);
if ($sorted !== FALSE ) { // Saatiin csv auki, näytetään ilmoittautuneet
    $line = 0;
    sort($sorted);
    foreach($sorted as $row)
    {
		echo "<tr>\n";
		echo "  <td>" . $row[0] . "</td>";
		echo "  <td>" . $row[1] . "</td>";
		echo "</tr>\n";
    }
} // Saatiin luettua csv
echo "</tbody></table>\n";
echo "<br><hr>\n";
echo "<pre>";
// save_json (json_encode($sorted, JSON_PRETTY_PRINT));
//To display array data
//print_r($sorted);
echo "</pre>";
?>
</body>
</html>
