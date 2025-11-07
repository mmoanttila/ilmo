<?php

include ("lib.php");
head();
$nro = $nimi = $tapahtuma ="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = test_input($_POST["Nro"]);
    $nimi = test_input($_POST["Nimi"]);
    $tapahtuma = test_input($_POST["Tapahtuma"]);
}
?>
<body>
<h2>Ilmoittautuminen</h2>
<?php
if ($_POST["TT"] != "true" { ?>
<pre><?php include("vastuuvapaus.txt") ?></pre>
<form method="POST">
    <input type="check" name="TT" value="true">
    <input type="hidden" name="Nro" value="<?php $nro ?>">
    <input type="hidden" name="Nimi" value="<?php $nimi ?>">
    <input type="hidden" name="Tapahtuma" value="<?php $tapahtuma ?>">
    <input type="submit"  name="Hyväksy" value="Hyväksy">
</form>
<?php
} else { ?>
<p> Otin vastaan ilmoittautumisen:
<table>
  <tr><td># <?php echo $nro; ?></td><td>Nimi: <?php echo $_POST["Nimi"]; ?></td></tr>
</table>

<?php
$added=addcsv ( $nro, $nimi, $tapahtuma . ".csv" );
if ( $added == FALSE ) {
?>
<pre>Tiedoston kirjoitus ei onnistunut!!</pre>
<?php
} else {
}
}
?>
</body>
</html>
