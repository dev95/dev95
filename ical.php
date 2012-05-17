<?php
require_once( "lib.php");
session_start();
$class = $_SESSION['class'];
$version = $_SESSION['version'];
$line = "";
$useful = find_param( 'useful');
foreach( $useful as $param)
{
	$line .= " ";
	$line .= trim( $param);
}

$ics = gen_ics( $class, $version, $line);
if ( $ics)
{
	header( "Content-type: text/calendar");
	header( "Content-Disposition: filename=\"$class.ics");
	echo $ics;
}
else
{
	echo "<p> generating ics file error!</p>";
}
session_destroy();
?>
