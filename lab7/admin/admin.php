<?php

function FormularzLogowania()
{
	$wynik= '
	<div class="logowanie">
	 <h1 class="heading">Panel CMS:</h1>
	  <div class="logowanie">
	   <form method="post" name="LoginForm" enctype-"multipart/form-data" action="'. $_SERVER['REQUEST_URI'].'">
	    <table class="logowanie">
		 <tr><td class="kog4_t">[email]</tdt><td><input type="text" name="login_email" class="logowanie" /></td></tr>
		 <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
		</table>
	   </form>
	</div>
	</div>
	';
	
	return $wynik;
}


function ListaPodstron()
{
	
	$query = "SELECT * FROM page_list WHERE id='$id_clear' ORDER BY data DESC LIMIT 100";
	 $result = mysql_query($query);
	 
	 while($row = mysql_fetch_array($result))
	 {
		 $row['id'].' '.$row['tytul'].' <br/>';
	 }
	
}
	
function EdytujPodstrone()
{
	
}

function DodajNowaPodstrone()
{
	
}


function Usun Podstrone()
{
	
}
	
?>