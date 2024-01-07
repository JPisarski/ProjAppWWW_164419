<link rel="stylesheet" href="../css/style_admin.css">

<?php

// rozpoczynamy sesję

session_start();

// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja addToCard() wyświetla formularz do dodawania produktów,
// następnie dodaje do koszyka produkt według wcześniej wymienionego formularza

function addToCard($id)
{
	global $link;
	
	// jeśli $id = 0, to wyświetla wszystkie dostępne produkty
	// w przeciwnym razie, wyświetla tylko produkt o $id 
	
	if($id == 0)
	{
		$query = "SELECT * FROM products ORDER BY id ASC";
		$result = mysqli_query($link, $query);
	}
	else
	{
		$query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
		$result = mysqli_query($link, $query);
	}
	
	echo '<div class="col"><h1 class="naglowek"><b>Dostępne produkty</b></h1>';
	
	// jeśli w bazie danych udało się nam wyszukać odpowiednie produkty,
	// to wyświetla się nam formularz dodawania,
	// w przeciwnym razie, wyświetla się nam odpowiedni komunikat
	
	if($result)
	{		

		// jeśli jakiś produkt jest dostępny, kontynuujemy
		// w przeciwnym razie, wyświetla się nam odpowiedni komunikat
		
		$brak = 0;
		while($row = mysqli_fetch_array($result)) 
		{	
			// ustalamy cenę brutto produktu
	
			$cena = $row['cena_netto'] + $row['podatek_vat'];
			
			if($row['status_dostepnosci'] == 1)
			{
				$brak = 1;
				echo ' 
				<div><center>
					<img src="data:image/jpeg;base64,'.base64_encode($row['zdjecie']).'" height="150"/>
					<form method="post" action="koszyk.php?funkcja=dodaj&id='.$row['id'].'">
						<input type="hidden" name="idp" value='.$row['id'].' />
						<input type="hidden" name="cena" value='.$cena.' />
						<table>
							<tr><td><b>Nazwa:</b></td><td><b>'.$row['tytul'].'</b></td></tr>
							<tr><td><b>Cena:</b></td><td>'.$cena.'</td></tr>
							<tr><td><b>Ilość:</b></td><td><input type="number" name="ilosc" value=1 required /></td></tr>
							<tr><td></td><td><input type="submit" name="dodawanie_koszyk" value="Dodaj" /></td></tr>
						</table>
					</form>
				</div></center>
			';
			}
		}
		if($brak == 0)
		{
			echo '<center>Brak dostępnych produktów</center>';
		}
		echo '</div>';
	}
	else
	{
		echo '<center>Brak produktów</center>';
	}
	
	// jeśli przesłaliśmy formularz, to kontynuujemy
	
	if(isset($_POST['dodawanie_koszyk']) && isset($_GET['id']) && isset($_GET['funkcja']))
	{
		if($_GET['funkcja'] == 'dodaj')
		{	
			// ustawienie licznika ilości produktów
	
			if(!isset($_SESSION['count']))
			{
				$_SESSION['count'] = 1;
			}
			else
			{	
				$_SESSION['count']++;
			}
			
			// nadanie numeru dla produktu w koszyku i innych pól

			$nr = $_SESSION['count'];
			$id_prod = $_POST['idp'];
			$cena = $_POST['cena'];
			$ile_sztuk = $_POST['ilosc'];
			
			// jeśli dodajemy ilość < 1 do koszyka,
			// to ustawiamy ilość na 1
			
			if($ile_sztuk < 1)
			{
				$ile_sztuk = 1;
			}
			
			// wyszukujemy w bazie danych produkt o id = $id_prod
			
			$query = "SELECT * FROM products WHERE id='$id_prod' LIMIT 1";
			$result = mysqli_query($link ,$query);
			$row = mysqli_fetch_array($result);
			
			// jeśli dodajemy kolejny produkt o tym samym id_prod, 
			// to edytujemy pola produktu o tym samym id_prod

			$x = 1;
		
			while($x < $_SESSION['count'])
			{
				if($_SESSION[$x.'_1'] == $id_prod)
				{
					$_SESSION[$x.'_2'] += $ile_sztuk;
					$_SESSION[$x.'_6'] += $cena * $ile_sztuk;
					
					// jeśli przekroczymy ilość sztuk w magazynie, to ustawiana jest ilość sztuk w magazynie
					
					if($_SESSION[$x.'_2'] > $row['ilosc_sztuk_w_magazynie'])
					{
						$_SESSION[$x.'_2'] = $row['ilosc_sztuk_w_magazynie'];
						$_SESSION[$x.'_6'] = $cena * $row['ilosc_sztuk_w_magazynie'];
					}
					$_SESSION[$x.'_3'] = time();
					$_SESSION['count']--;
					
					// przekierowywujemy do koszyk.php
					
					echo "<script>window.location.href='koszyk.php';</script>";
					exit();
				}
			
				$x++;
			}
			
			// zapisanie danych produktów w tablicy 2 wymiarowej - resztę pobierzemy po id_prod z bazy danych
			
			$prod[$nr]['id_prod'] = $id_prod;
			$prod[$nr]['ile_sztuk'] = $ile_sztuk;
			$prod[$nr]['data'] = time();
			$prod[$nr]['tytul'] = $row['tytul'];
			$prod[$nr]['cena_jednostkowa'] = $cena;
			$prod[$nr]['cena_łączna'] = $cena * $prod[$nr]['ile_sztuk']; 
			$prod[$nr]['obraz'] = $row['zdjecie'];

			// stworzenie dwuwymiarowej numeracji - dla jednowymiarowej tablicy

			$nr_0 = $nr.'_0';
			$nr_1 = $nr.'_1';
			$nr_2 = $nr.'_2';
			$nr_3 = $nr.'_3';
			$nr_4 = $nr.'_4';
			$nr_5 = $nr.'_5';
			$nr_6 = $nr.'_6';
			$nr_7 = $nr.'_7';
			
			// zapisanie w tablicy sesji danych produktów

			$_SESSION[$nr_0] = $nr;
			$_SESSION[$nr_1] = $prod[$nr]['id_prod'];
			$_SESSION[$nr_2] = $prod[$nr]['ile_sztuk'];
			$_SESSION[$nr_6] = $prod[$nr]['cena_łączna'];
			
			// jeśli liczba sztuk przekracza stan w magazynie,
			// to ustalana jest wartość stanu magazynu
			
			if($_SESSION[$nr_2] > $row['ilosc_sztuk_w_magazynie'])
			{
				$_SESSION[$nr_2] = $row['ilosc_sztuk_w_magazynie'];
				$_SESSION[$nr_6] = $cena * $row['ilosc_sztuk_w_magazynie'];
			}
			$_SESSION[$nr_3] = $prod[$nr]['data'];
			$_SESSION[$nr_4] = $prod[$nr]['tytul'];      
			$_SESSION[$nr_5] = $prod[$nr]['cena_jednostkowa'];    
			   
			$_SESSION[$nr_7] = $prod[$nr]['obraz'];  
			
			// przekierowywujemy do koszyk.php
			
			echo "<script>window.location.href='koszyk.php';</script>";
		}	
	}
}

// funkcja removeFromCard() usuwana produkt z koszyka

function removeFromCard()
{
	if(isset($_GET['nr']))
	{
		$nr = $_GET['nr'];
		
		if($nr == $_SESSION['count'])
		{
			// jeśli produkt jest na końcu koszyka
			
			unset($_SESSION[$nr.'_0']);
			unset($_SESSION[$nr.'_1']);
			unset($_SESSION[$nr.'_2']);
			unset($_SESSION[$nr.'_3']);
			unset($_SESSION[$nr.'_4']);
			unset($_SESSION[$nr.'_5']);
			unset($_SESSION[$nr.'_6']);
			unset($_SESSION[$nr.'_7']);
		}
		else
		{
			// jeśli produkt nie jest na końcu koszyka
			
			for($x = $nr; $x < $_SESSION['count'] ; $x++)
			{
				$t = $x + 1;
				$_SESSION[$x.'_1'] = $_SESSION[$t.'_1'];
				$_SESSION[$x.'_2'] = $_SESSION[$t.'_2'];
				$_SESSION[$x.'_3'] = $_SESSION[$t.'_3'];
				$_SESSION[$x.'_4'] = $_SESSION[$t.'_4'];
				$_SESSION[$x.'_5'] = $_SESSION[$t.'_5'];
				$_SESSION[$x.'_6'] = $_SESSION[$t.'_6'];
				$_SESSION[$x.'_7'] = $_SESSION[$t.'_7'];
			}
			
				unset($_SESSION[$_SESSION['count'].'_0']);
				unset($_SESSION[$_SESSION['count'].'_1']);
				unset($_SESSION[$_SESSION['count'].'_2']);
				unset($_SESSION[$_SESSION['count'].'_3']);
				unset($_SESSION[$_SESSION['count'].'_4']);
				unset($_SESSION[$_SESSION['count'].'_5']);
				unset($_SESSION[$_SESSION['count'].'_6']);
				unset($_SESSION[$_SESSION['count'].'_7']);
		}
		
		// jeśli usunęliśmy jedyny produkt w koszyku
		
		$_SESSION['count']--;
		if($_SESSION['count'] == 0)
		{
			unset($_SESSION['count']);
		}
		
		// przekierowanie do koszyk.php
		
		echo "<script>window.location.href='koszyk.php';</script>";
	}
}

// funkcja EdytujIlosc() wyświetla formularz do edytowania ilości produktu w koszyku

function EdytujIlosc()
{
	global $link;
			
	if(isset($_GET['nr']))
	{
		$nr = $_GET['nr'];
		$id = $_SESSION[$nr.'_1'];
		
		// wyszukujemy w bazie danych produkt o id = $id
		
		$query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
		$result = mysqli_query($link ,$query);
		$row = mysqli_fetch_array($result);
		
		// wyświetlamy formularz
		
		echo '
		<div>
			<h1 class="naglowek"><b>Edytuj ilość</b></h1>
				<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
					<table>
						<tr><td><b>Ilość: </b></td><td><input type="number" name="ilosc" value="'.$_SESSION[$nr.'_2'].'" required /></td></tr>
						<tr><td></td><td><input type="submit" name="edytowanie_ilosci" value="Edytuj" /></td></tr>
				</table>
				</form>
		</div>
		';
		
		// jeśli przesłaliśmy formularz, to kontynuujemy
		
		if(isset($_POST['edytowanie_ilosci']) && !empty($_GET['nr']))
		{
			// jeśli ilość jest mniejsza od 1, to ustanawiamy na 1
			
			$ile =   $_POST['ilosc'];
			if($ile < 1)
			{
				echo "<script>window.location.href='koszyk.php';</script>";
				exit();
			}
			
			// jeśli ilość jest większa od ilości sztuk w magazynie, to ustanawiamy na ilość sztuk w magazynie
			
			if($ile > $row['ilosc_sztuk_w_magazynie'])
			{
				$ile = $row['ilosc_sztuk_w_magazynie'];
			}
			
			$_SESSION[$nr.'_2'] = $ile;
			$_SESSION[$nr.'_6'] = $_SESSION[$nr.'_5'] * $ile;
			
			// przekierowujemy do koszyk.php
			
			echo "<script>window.location.href='koszyk.php';</script>";
			exit();
		}
	}
}

// funkcja showCard() wyświetla zawartość koszyka i umożliwia usunięcia produktu, zmianę jego ilości
// oraz wyczyszczenie koszyka oraz złożenie zamówienia

function showCard()
{
	// ustanawiamy zmienną $suma - wartość całego koszyka
	
	$suma = 0;
	
	echo '<div class="colskrol"><br><h1 class="naglowek">Koszyk</h1><center>';
	
	// jeśli mamy choć 1 produkt w koszyku, to wyświetlamy zawartość koszyka
	// jeśli nie, to jest wyświetlany odpowiedni komunikat
	
	if(isset($_SESSION['count']))
	{
		// wyświetlamy zawartość koszyka
		
		echo '
			<table><tr><th></th><th class="tn">Nazwa</th><th class="tn">&nbsp;&nbsp;Ilość&nbsp;&nbsp;</th><th class="tn">Cena</th><th class="tn">&nbsp;&nbsp;Razem&nbsp;&nbsp;</th></tr>';
		
		$x = 1;
		
		while($x <= $_SESSION['count'])
		{
			// obliczamy wartość zmiennej $suma
			
			$suma += $_SESSION[$x.'_6'];
		
			echo '
					<tr>				
						<td class="ooo"><b><img src="data:image/jpeg;base64,'.base64_encode($_SESSION[$x.'_7']).'" height="50"/></b></td>
						<td class="tdid"><b>'.$_SESSION[$x.'_4'].'</b></td>
						<td class="tdnazwa">'.$_SESSION[$x.'_2'].'</td>
						<td class="tdane">'.$_SESSION[$x.'_5'].'</td>
						<td class="tdane">'.$_SESSION[$x.'_6'].'</td>
						<td class="tdusun"><a href="koszyk.php?funkcja=usun&nr='.$_SESSION[$x.'_0'].'"><b>Usuń</b></a></td>
						<td class="tdedytuj"><a href="koszyk.php?funkcja=edytuj&nr='.$_SESSION[$x.'_0'].'"><b>Ilość</b></a></td>
					</tr>
				';
			
			$x++;
		}
		
		echo '
				<tr>	
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td class="tsumapr"><b>Suma: </b></td>
					<td class="tsumalw">'.$suma.'</td>
					<td class="tdusun"><a href="koszyk.php?funkcja=wyczysc"><b>Wyczyść</b></a></td>
					<td class="tdedytuj"><a href="koszyk.php?funkcja=zamowienie"><b>Zamów</b></a></td>
					<td></td><td></td>			
				</tr>				
				</table></center><br>
			';	
			
		// jeśli jest ustanowiona zmienna $_GET['funkcja'] i jest równa 'edytuj',
		// to wywołujemy funkcję EdytujIlosc()
			
		if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj')
		{
			EdytujIlosc();
		}	
		echo '</div>';
	}
	else
	{
		echo 'Brak produktów w koszyku</center></div>';
	}
}

echo '<div class="row">';

// jeśli jest ustanowiona zmienna $_GET['dostepne_produkty'] i nie jest pusta,
// to wywołujemy funkcję addToCard() z argumentem $iddp,
// w przeciwnym przypadku wywołujemy funkcję addToCard() z argumentem 0

if(isset($_GET['dostepne_produkty']) && !empty($_GET['dostepne_produkty']))
{
	$iddp = $_GET['dostepne_produkty'];
	addToCard($iddp);
}
else
{
	addToCard(0);
}

// wywołujemy funkcję showCard();

showCard();

// jeśli jest ustanowiona zmienna $_GET['funkcja'] i jest równa 'usun',
// to wywołujemy funkcję removeFromCard()

if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun')
{
	removeFromCard();
}

// jeśli jest ustanowiona zmienna $_GET['dostepne_produkty'] i nie jest pusta,
// to wyświetlamy przekierowania do poprzedniej strony

if(isset($_GET['dostepne_produkty']) && !empty($_GET['dostepne_produkty']))
{
	echo '<br><br><br><br><h2 class="naglowek"><a href="produkt_pokaz.php?id='.$iddp.'">Wróć do poprzedniej strony</a></h2>';
}

// jeśli jest ustanowiona zmienna $_GET['funkcja'] i jest równa 'wyczysc',
// to niszczymy sesję i przekierowywujemy do koszyk.php

if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'wyczysc')
{
	session_destroy();
    echo "<script>window.location.href='koszyk.php';</script>";
	exit();
}	

// jeśli jest ustanowiona zmienna $_GET['funkcja'] i jest równa 'zamowienie',
// to niszczymy sesję i przekierowywujemy do potwierdzenie.php

if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'zamowienie')
{
	session_destroy();
    echo "<script>window.location.href='potwierdzenie.php';</script>";
	exit();
}	

echo '<br><h2 class="naglowek"><a href="sklep.php">Wróć do sklepu</a></h2>';
echo '</div>';

?>
