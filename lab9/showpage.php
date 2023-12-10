<?php

function PokazPodstrone($id)
{
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$baza = 'moja_strona';

	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
	
	$id_clear = htmlspecialchars($id);
	$query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
	$result = mysqli_query($link ,$query);
	$row = mysqli_fetch_array($result);
	
	if(empty($row['id']))
	{
		$web = '[nie_znaleziono_strony]';
	}
	else
	{		
		$web = $row['page_content'];
	}
	return $web;
}

?>