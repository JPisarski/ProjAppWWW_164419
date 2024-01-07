<link rel="stylesheet" href="../css/style_admin.css">

<?php

// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja PokazProdukt pokazuje informacje o produkt o danym id 
// oraz umożliwia przekierowanie do koszyka, jeśli produkt jest dostępny

function PokazProdukt($id)
{
	// wyszukujemy w bazie danych produkt od id = $id
	
	global $link;
    $query = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($link, $query);
	
	// jeśli zapytanie się udało, to kontynuujemy
	// w przeciwnym przypadku wyświetlany jest odpowiedni komunikat
	
	if($result)
	{
		$brak = 0;
		while($row = mysqli_fetch_array($result)) 
		{	
			// wyszukujemy w bazie danych kategorii odpowiadającej produktowi
			
			$idk = $row['kategoria'];
			$query1 = "SELECT * FROM categories WHERE id='$idk' LIMIT 1";
			$result1 = mysqli_query($link ,$query1);
			$row1 = mysqli_fetch_array($result1);
			
			$brak = 1;		
			
			// ustalamy cenę brutto produktu
			
			$cena = $row['cena_netto'] + $row['podatek_vat'];
			
			// wyświetlamy informację o produkcie
			
			echo '<br><h1 class="naglowek">'.$row['tytul'].'</h1>';
			echo '<center><img src="data:image/jpeg;base64,'.base64_encode($row['zdjecie']).'" height="300"/>';
			echo '<br><br><table>	
					<tr><th class="tn">Nazwa</th><td class="tdane">'.$row['tytul'].'</td></tr>		
					<th class="tn">&nbsp;&nbsp;&nbsp;Opis&nbsp;&nbsp;&nbsp;</th><td class="topis">'.$row['opis'].'</td>
					<tr><th class="tn">Kategoria</th><td class="tdane">'.$row1['name'].'</td></tr>	
					<tr><th class="tn">Cena</th><td class="tdane">'.$cena.'</td></tr>
					<tr><th class="tn">&nbsp;&nbsp;Ilość dostępnych sztuk&nbsp;&nbsp;</th><td class="tdane">'.$row['ilosc_sztuk_w_magazynie'].'</td></tr>					
					<tr><th class="tn">Gabaryt</th><td class="tdane">'.$row['gabaryt_produktu'].'</td></tr>
					</center>
				';
				
			// jeśli produkt jest dostępny, to można dodać go do koszyka,
			// w przeciwnym przypadku wyświetla się odpowiedni komunikat
				
			if($row['status_dostepnosci'] == 1)
			{
				echo '<tr><th class="tn">Produkt dostępny</th><td class="tdedytuj"><a href="koszyk.php?dostepne_produkty='.$row['id'].'"><b>Dodaj do koszyka</b></a></td></tr></table>';
			}
			else
			{
				echo '<tr><th class="tn">Produkt niedostępny</th><td class="tdedytuj">Funkcja niedostępna</td></tr></table>';
			}
		} 
	
		echo '</table></center><br>';
	}
	else
	{
		echo '<br><h1 class="naglowek">Lista produktów</h1><center>Brak produktów</center>';
	}
	
}

// jeśli jest ustanowiona zmienna $_GET['id'] i nie jest pusta, to wywołujemy funkcję PokazProdukt

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = $_GET['id'];
	PokazProdukt($id);
}

echo '<h2 class="naglowek"><a href="sklep.php">Wróć do poprzedniej strony</a></h2>';

?>