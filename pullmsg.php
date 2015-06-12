<?php

function str_url_to_link($string)
{
	preg_match_all('/(http:\/\/[^\s]+)/', $string, $matches);

	if(count($matches[0]) > 0) {
		foreach($matches[0] as $match) {
			$hypertext = "<a href='". $match ."'' target='_blank'>". $match ."</a>";
			$string = str_replace($match, $hypertext, $string);
		}
	}

	preg_match_all('/(https:\/\/[^\s]+)/', $string, $matches);

	if(count($matches[0]) > 0) {
		foreach($matches[0] as $match) {
			$hypertext = "<a href='". $match ."'' target='_blank'>". $match ."</a>";
			$string = str_replace($match, $hypertext, $string);
		}
	}
	 
	return $string;
}

require_once('../_MyClasses/DBConnection.class.php');
require_once('_safeupperfolder/config.inc.php');

$dbConnection = new DBConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$dbConnection->openConnection();

$client = str_replace(".fritz.box", "", gethostbyaddr($_SERVER['REMOTE_ADDR']));

$sqlQuery = "SELECT message, sender, timestamp FROM messages ORDER BY timestamp DESC LIMIT 0,100";

$sqlResult = $dbConnection->sendSqlQuery($sqlQuery);

if($sqlResult->num_rows > 0) {
	foreach ($sqlResult as $value) {
		
		if($client == $value['sender']) {
			echo "<div class='msg margin_left'>";
		}
		else {
			echo "<div class='msg margin_right'>";

			switch($value['sender'])
			{
				case CASE_01	    :	$sender = VALUE_01;
										break;

				case CASE_02    	:	$sender = VALUE_02;
										break;

				case CASE_03	    :	$sender = VALUE_03;
										break;

				default 			:	$sender = $value['sender'];
			}
			
			echo "	<p class='sender'><b>".$sender."</b></p>";
		}
			$timeObject = new DateTime($value['timestamp']);

			echo "	<p class='timestamp'>".$timeObject->format('d.m.Y - H:i')." Uhr</p>";
			echo "	<br>";
			echo "	<br>";
			$message = str_url_to_link($value['message']);
			echo "	<p>".$message."</p>";
			echo "</div>";
	}
}

$dbConnection->closeConnection();