<?php
$file = 'log.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$record = date('m/d/Y h:i');
$log = print_r($_REQUEST["xml"], true);
$current .= "\n".$record." ".$log;

// Write the contents back to the file
file_put_contents($file, $current);
?>