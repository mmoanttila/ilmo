<?php
/* vim: set ai sw=2 fileencoding=utf-8 expandtab */

function head ($title="karhuenduro.fi") {
  //  print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"\n";
  //  print "    \"http://www.w3.org/TR/html4/strict.dtd\">\n";
  print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
  print "       \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
  print "<html>\n";
  print "<head>\n";
  print "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></meta>\n";
  print "  <meta name=\"author\" content=\"Mika Anttila\"></meta>\n";
  print "  <meta name=\"copyright\" content=\"&copy; karhuenduro.fi\"></meta>\n";
  print "  <meta name=\"keywords\" content=\"karhuenduro, ilmo, ilmoittautunimen\"></meta>\n";
  print "  <link rel=\"stylesheet\" href=\"/blue.css\"></link>\n";
  print "  <title>$title</title>\n";
  print "</head>\n";
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function addcsv ( $nro, $nimi, $file="ilmot.csv", $tag=FALSE, $paikalla=FALSE) {
  $fd = fopen($file, "a+");
  if ( $fd != FALSE ) {
	if ( $tag == FALSE ) { // Ei annettu parametrinä
	  $tag = str_pad($nro, 8, "0", STR_PAD_LEFT); 
    } // Muuten laitetaan annettu tagi
    $rvalue = fputcsv($fd, array($nro, $nimi, $tag, $paikalla));
    fclose($fd);
    return $rvalue;
  } else {
    return FALSE;
  }
}

function new_number ( $nro, $file="ilmot.csv") {
  $array = read_csv ( $file );
  foreach ($array as $row) {
	if (in_array($nro,$row)) {
	  return FALSE;
	}
  }
  return TRUE;
}

function save_csv ( $lines, $file="ilmot.csv" ) {
  $fd = fopen($file, "w");
  if ( $fd != FALSE ) {
	  foreach ($lines as $row) {
		  fputcsv ($fd, $row);
	  }
	  fclose($fd);
  } else {
      echo "<pre>Enpä saanut " . $file . ":ä auki!! </pre>\n";
  }
}

function read_csv ( $file="ilmot.csv" ) {
  if (($open = fopen( $file, "r")) !== FALSE) 
  {
	while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
	{        
	  $array[] = $data; 
	}
    fclose($open);
    $myarray = array();
	// foreach($array as $row) {
	//  $myarray[$row[0]] = "$row[1]";
	$line = 0;
    foreach($array as $row)
    { // luetaan vain kolme ekaa saraketta: nro, nimi ja tagi
      $myarray[$line][0] = $row[0];
      $myarray[$line][1] = $row[1];
      $myarray[$line][2] = $row[2];
      $myarray[$line][3] = $row[3];
      $line++;
    }
	return $myarray;
  }
  return FALSE;
}

function dumpcsv ( $lines ) {
	$fd = fopen('php://output', 'wb');
	fclose($fd);
}

function save_json ( $data, $file="ilmot.json" ) {
	$fd = fopen($file, "w");
	if ( $fd != FALSE ) {
		fwrite ($fd, $data);
		fclose ($fd);
	}
}
?>
