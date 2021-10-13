<?php
$tapahtuma = "Järilänvuoren sprintti";
$pvm = "2021-09-18";
$file = "ilmot/jarilan-sprint.csv";
$json = json_decode(file_get_contents("current.json"));
$tapahtuma = $json['tapahtuma'];
$pvm = $json['pvm'];
$file = $json['file'];
?>
