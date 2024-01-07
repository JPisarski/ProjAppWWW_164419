<link rel="stylesheet" href="../css/style_admin.css">

<?php

// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja PokazKategorie() wyświetla drzewko kategorii oraz umożliwia wywołanie funkcji PrzegladajKategorie()

function PokazKategorie($mother = 0, $ile = 0)
{
	// wyszukujemy w bazie danych kategorie o mother = $mother

	global $link;
    $query = "SELECT * FROM categories WHERE mother = '$mother'";
    $result = mysqli_query($link, $query);
	
	// jeśli zapytanie się udało, to kontynuujemy
	// przeciwnym przypadku wyświetlany jest odpowiedni komunikat
	
	if($result)
	{			
		$brak = 0;
			
		// jeśli istnieją jakieś kategorie, kontynuujemy,
		// w przeciwnym przypadku wyświetlamy odpowiedni komunikat
			
		while($row = mysqli_fetch_array($result)) 
		{	
			// wyświetlamy kategorie
	
			$brak = 1;
			for($i=0; $i < $ile; $i++)
			{
					echo '&nbsp;&nbsp;&nbsp;<span style="color: #0000FF;">>>>>></span>';
			}
			echo ' 
					<b><span style="color:#e00cea;">&nbsp;'.$row['id'].' </span><span style="color:blue;">&nbsp;'.$row['name'].'</b>';
			
			// wyszukujemy, czy istnieją produkty o danej kategorii
			// jeśli tak, to wyświetlany jest napis Przeglądaj przekierowujący po kliknięciu do sklep.php?funkcja=przegladaj&id='.$row['id'].'
			
			$idk = $row['id'];
			$query1 = "SELECT * FROM products WHERE kategoria='$idk'";
			$result1 = mysqli_query($link ,$query1);
			
			if($row1 = mysqli_fetch_array($result1))
			{
				echo '<a href="sklep.php?funkcja=przegladaj&id='.$row['id'].'"><b><span style="color:green;">&nbsp;&nbsp;&nbsp;Przeglądaj</span></b></a>';
			}
			
			echo '<br><br>';
			
			// wywołujemy rekurencyjnie funkcję PokazKategorie() dla podkategorii
			
			PokazKategorie($row['id'], $ile+1);
		}
		if($brak == 0 && $ile == 0)
		{
			echo "<center>Brak kategorii</center>";
		}
	}
	else
	{
		echo "<center>Błąd podczas wyświetlania kategorii</center>";
	}
}

// funkcja PrzegladajKategorie() wyświetla produkty o danej kategorii i umożliwia ich pokazanie

function PrzegladajKategorie($id)
{
	// wyszukujemy w bazie danych produktów o kategorii = $id
	
	global $link;
    $query =  "SELECT * FROM products WHERE kategoria='$id'";
    $result = mysqli_query($link, $query);
	
	echo '<h2 class="naglowek">Lista produktów</h2><center><table>';
	
	// jeśli zapytanie się udało, to kontynuujemy,
	// w przeciwnym przypadku wyświetlamy odpowiedni komunikat
	
	if($result)
	{
		$brak = 0;
		
		//
		// jeśli istnieją jakieś produkty o danej kategorii, to kontynuujemy, 
		// w przeciwnym przypadku wyświetlamy odpowiedni komunikat
		
		while($row = mysqli_fetch_array($result)) 
		{
			if($brak == 0)
			{
				// wyświetlamy nagłówki
				
				echo '<tr><th class="tn">Id</th><th class="tn">Nazwa</th><th class="tn">Cena</th></tr>';
			}
			
			$brak = 1;
			
			// ustalamy cenę brutto produktu
			
			$cena = $row['cena_netto'] + $row['podatek_vat'];
			
			// wyświetlamy produkt z możliwością jego pokazania
			
			echo '
					<tr>
						<td class="tdid"><b>'.$row['id'].'<b></td>
						<td class="tdnazwa"><b>'.$row['tytul'].'<b></td>
						<td class="tdane"><b>'.$cena.'<b></td>
						<td class="tdedytuj"><a href="produkt_pokaz.php?id='.$row['id'].'"><b>Pokaż</b></a></td>
					</tr>
				';
		}
		if($brak == 0)
		{
			echo '<center>Brak produktów</center>';
		}
		else
		{
			echo '</table></center><br>';
		}
	}	
	else
	{
		echo '<center>Błąd podczas wyświetlania produktów</center>';
	}
	
}

echo '<br><h1 class="naglowek">Witamy w naszym sklepie internetowym!</h1><br>';
echo '<h2 class="naglowek">Przeglądaj nasze kategorie</h2><p style="margin-left: 44%;">';

// wywołujemy funkcję PokazKategorie()

PokazKategorie();

echo '</p>';

// jeśli zmienna $_GET['funkcja'] jest ustawiona i ma wartość równą 'przegladaj',
// to wywołujemy funkcję PrzegladajKategorie z argumentem $id

if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'przegladaj')
{
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		PrzegladajKategorie($id);
		echo '<p><center><a href="sklep.php">Ukryj listę</a></p></center>';
	}
}

echo '<h2 class="naglowek"><a href="koszyk.php">Przejdź do koszyka</a></h2>';
echo '<h2 class="naglowek"><a href="../index.php">Wróć do strony głównej</a></h2>';

?>