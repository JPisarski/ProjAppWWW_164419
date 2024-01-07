<?php

// funkcja error_reporting() ustawia, jakie ostrzeżenia mają być wyświetlane przez interpreter PHP

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// plik index.php służy do dynamicznego generowania stron

?>

<!DOCTYPE html>
<html>
	<head>

		<meta http-equiv="Content-type" content="text/html; charset=UTF-8" /> 
		<meta http-equiv="Content-Language" content="pl" /> 
		<meta name="Author" content="Jakub Pisarski" /> 
		<title>Moje hobby to genealogia</title> 	
		
	</head>
	<body>
	
		<table>
			<tr>
				<td><a href="index.php?idp="><b><i>Strona główna</i></b></a></td>
				<td><a href="index.php?idp=dlaczego_genealogia"><b><i>Dlaczego genealogia?</i></b></a></td>
				<td><a href="index.php?idp=gdzie_szukac"><b><i>Gdzie szukać?</i></b></a></td>
				<td><a href="index.php?idp=skrypty"><b><i>Skrypty</i></b></a></td>			
				<td><a href="./admin/sklep.php"><b><i>Sklep</i></b></a></td>
			</tr>
			<tr>
				<td><a href="index.php?idp=drzewo_genealogiczne"><b><i>Drzewo genealogiczne</i></b></a></td>
				<td><a href="index.php?idp=historia_mojej_rodziny"><b><i>Historia mojej rodziny</i></b></a></td>
				<td><a href="index.php?idp=na_co_uwazac"><b><i>Na co uważać?</i></b></a></td>
				<td><a href="index.php?idp=filmy"><b><i>Filmy</i></b></a></td>			
				<td><a href="index.php?idp=kontakt"><b><i>Kontakt</i></b></a></td>
			</tr>
		</table>	
    
		<?php
		
		// dołączamy zawartość plików cfg.php i showpage.php
		
		include('cfg.php');
		include('showpage.php');
		
		// od podanego idp zależy, jaka podstrona się wyświetli
		
		if($_GET['idp'] == '') 
		{echo PokazPodstrone(1);}
		if($_GET['idp'] == 'dlaczego_genealogia') 
		{echo PokazPodstrone(2);}
		if($_GET['idp'] == 'gdzie_szukac') 
		{echo PokazPodstrone(3);}
		if($_GET['idp'] == 'js')
		{echo PokazPodstrone(4);}
		if($_GET['idp'] == 'jquery')
		{echo PokazPodstrone(5);}
		if($_GET['idp'] == 'drzewo_genealogiczne')
		{echo PokazPodstrone(6);}
		if($_GET['idp'] == 'historia_mojej_rodziny') 
		{echo PokazPodstrone(7);}
		if($_GET['idp'] == 'na_co_uwazac')
		{echo PokazPodstrone(8);}
		if($_GET['idp'] == 'filmy')
		{echo PokazPodstrone(9);}
		if($_GET['idp'] == 'kontakt')
		{echo PokazPodstrone(10);}
		if($_GET['idp'] == 'skrypty')
		{echo PokazPodstrone(11);}
	
		?>
		
        <div class="footer">
           
		    <?php
		   
			// wyświetlany na koniec stopkę 
		   
            $nr_indeksu = '164419';
            $nrGrupy = '3';
        
            echo 'Autor: Jakub Pisarski '.$nr_indeksu.' grupa '.$nrGrupy.' <br/><br/>';
			
            ?>
			
        </div>   

	</body>
</html>
