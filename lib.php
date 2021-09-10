<?php

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

function dumpcsv ( $lines ) {
	$fp = fopen('php://output', 'wb');
	fclose($fp);
}

?>
