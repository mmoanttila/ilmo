<?php
/* vim: set ai sw=2: */

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

function addcsv ( $nro, $nimi, $file="ilmot.csv" ) {
  $fd = fopen($file, "a+");
  if ( $fd != FALSE ) {
    $rvalue = fputcsv($fd, array($nro, $nimi));
    fclose($fd);
    return $rvalue;
  } else {
    return FALSE;
  }
}

function new_number ( $nro, $file="ilmot.csv") {
  $array = read_csv ( $file );
  if (in_array($nro,$array)) {
	return FALSE;
  } else {
	return TRUE;
  }
}

function save_csv ( $lines, $file="ilmot.csv" ) {
  $fd = fopen($file, "w");
  if ( $fd != FALSE ) {
	  foreach ($lines as $row) {
		  fputcsv ($fd, $row);
	  }
	  fclose($fd);
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
    {
      $myarray[$line][0] = $row[0];
      $myarray[$line][1] = $row[1];
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
