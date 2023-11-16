
<!DOCTYPE html>

<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

?>

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
			<td><a href="index.php?idp=jq"><b><i>JQ</i></b></a></td>			
			<td><a href="index.php?idp=skrypty"><b><i>Skrypty</i></b></a></td>
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
	
		include($strona);
        $strona = '';
        if($_GET['idp'] == '') 
        {$strona = './html/glowna.html';}
        if($_GET['idp'] == 'dlaczego_genealogia') 
        {$strona = './html/dlaczego_genealogia.html';}
        if($_GET['idp'] == 'drzewo_genealogiczne') 
        {$strona = './html/drzewo_genealogiczne.html';}
        if($_GET['idp'] == 'gdzie_szukac') 
        {$strona = './html/gdzie_szukac.html';}
        if($_GET['idp'] == 'historia_mojej_rodziny') 
        {$strona = './html/historia_mojej_rodziny.html';}
        if($_GET['idp'] == 'kontakt') 
        {$strona = './html/kontakt.html';}
        if($_GET['idp'] == 'na_co_uwazac')
        {$strona = './html/na_co_uwazac.html';}
        if($_GET['idp'] == 'filmy')
        {$strona = './html/filmy.html';}
		if($_GET['idp'] == 'skrypty')
        {$strona = './html/skrypty.html';}
		if($_GET['idp'] == 'jq')
        {$strona = './html/jq.html';}

		include 'cfg.php';
        if(file_exists($strona))
        {
        	include($strona);
        }
        ?>
        
        <div class="footer">
           <?php
            $nr_indeksu = '164419';
            $nrGrupy = '3';
        
            echo 'Autor: Jakub Pisarski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
            ?>
        </div>   

</body>
</html>