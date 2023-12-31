<link rel="stylesheet" href="../css/style_admin.css">

<?php

// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja DodajProdukt() wyświetla formularz do dodawania produktu,
// następnie dodaje produkt według danych podanych we wcześniej wspomnianym formularzu

function DodajProdukt()
{
	global $link;

	echo '
    <div>
        <h1 class="naglowek"><b>Dodaj produkt<b/></h1>
            <form method="post" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Tytuł: <b/></td><td><input type="text" name="tytuł" required /></td></tr>
					<tr><td><b>Opis: <b/></td><td><textarea rows=10 cols=40 name="opis"></textarea></td></tr>
					<tr><td><b>Data wygaśnięcia: <b/></td><td><input type="date" name="data" required /></td></tr>
					<tr><td><b>Cena netto: <b/></td><td><input type="text" name="cena" required /></td></tr>
					<tr><td><b>Podatek VAT: <b/></td><td><input type="text" name="vat" required /></td></tr>
					<tr><td><b>Ilość sztuk w magazynie: <b/></td><td><input type="text" name="ilosc" required /></td></tr>
					<tr><td><b>Kategoria: <b/></td><td><input type="text" name="kategoria" required /></td></tr>
					<tr><td><b>Gabaryt produktu: <b/></td><td><input type="text" name="gabaryt" required /></td></tr>
					<tr><td><b>Zdjęcie: <b/></td><td><input type="file" name="zdjęcie" accept="image/*" required /></td></tr>
                    <tr><td></td><td><input type="submit" name="dodawanie_produktu" value="Dodaj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
	if(isset($_POST['dodawanie_produktu'])) 
	{
		$tytuł = $_POST['tytuł'];
        $opis = $_POST['opis'];
		$data_u = date('Y-m-d');
		$data_m = date('Y-m-d');
		$data_w = $_POST['data'];
		$cena = $_POST['cena'];
		$vat = $_POST['vat'];
		$ilosc = $_POST['ilosc'];
		if ($ilosc > 0 && $data_w >= date('Y-m-d')) 
		{
        $status_dostepnosci = 1;
		} 
		else 
		{
        $status_dostepnosci = 0;
		}
		$kategoria = $_POST['kategoria'];
		$gabaryt = $_POST['gabaryt'];
		$zdjecie = addslashes(file_get_contents($_FILES['zdjęcie']['tmp_name']));
	
        $query = "INSERT INTO products (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto,
					podatek_vat, ilosc_sztuk_w_magazynie, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) 
					VALUES ('$tytuł', '$opis',  '$data_u', '$data_m', '$data_w', '$cena',
					'$vat', '$ilosc', '$status_dostepnosci', '$kategoria', '$gabaryt', '$zdjecie')";
        $result = mysqli_query($link, $query);

        if($result) 
		{           
            echo "<script>window.location.href='produkty.php';</script>";
            exit();
        } 
		else 
		{
            echo "<center>Błąd podczas dodawania produktu: " . mysqli_error($link)."</center>";
        }
    }
}

// funkcja UsunProdukt() usuwa produkt o danym id

function UsunProdukt()
{
    global $link;
	
    if(isset($_GET['id'])) 
	{
        $id = $_GET['id'];
        $query = "DELETE FROM products WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);

        if($result) 
		{         
            echo "<script>window.location.href='produkty.php';</script>";
            exit();
        }
		else 
		{
            echo "<center>Błąd podczas usuwania produktu: " . mysqli_error($link)."</center>";
        }
    }
}

// funkcja EdytujProdukt() wyświetla formularz do edytowania produktu,
// następnie edytuje produkt według danych podanych we wcześniej wspomnianym formularzu

function EdytujProdukt()
{
    global $link;
	
	if(isset($_GET['id'])) 
	{
		$id = $_GET['id'];
	}
	$query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
	$result = mysqli_query($link ,$query);
	$row = mysqli_fetch_array($result);
	echo '
    <div>
        <h1 class="naglowek"><b>Edytuj produkt<b/></h1>
            <form method="post" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Tytuł: <b/></td><td><input type="text" name="tytuł" value="'.$row['tytul'].'" required /></td></tr>
					<tr><td><b>Opis: <b/></td><td><textarea rows=10 cols=40 name="opis">'.$row['opis'].'</textarea></td></tr>
					<tr><td><b>Data wygaśnięcia: <b/></td><td><input type="date" name="data" value="'.$row['data_wygasniecia'].'" required /></td></tr>
					<tr><td><b>Cena netto: <b/></td><td><input type="text" name="cena" value="'.$row['cena_netto'].'" required /></td></tr>
					<tr><td><b>Podatek VAT: <b/></td><td><input type="text" name="vat" value="'.$row['podatek_vat'].'" required /></td></tr>
					<tr><td><b>Ilość sztuk w magazynie: <b/></td><td><input type="text" name="ilosc" value="'.$row['ilosc_sztuk_w_magazynie'].'" required /></td></tr>
					<tr><td><b>Kategoria: <b/></td><td><input type="text" name="kategoria" value="'.$row['kategoria'].'" required /></td></tr>
					<tr><td><b>Gabaryt produktu: <b/></td><td><input type="text" name="gabaryt" value="'.$row['gabaryt_produktu'].'" required /></td></tr>
					<tr><td><b>Zdjęcie: <b/></td><td><input type="file" name="zdjęcie" accept="image/*" required/></td></tr>
                    <tr><td></td><td><input type="submit" name="edytowanie_produktu" value="Edytuj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
    if(isset($_POST['edytowanie_produktu']) && isset($_GET['id'])) 
	{
        $id = $_GET['id'];
        $tytuł = $_POST['tytuł'];
        $opis = $_POST['opis'];
		$data_m = date('Y-m-d');
		$data_w = $_POST['data'];
		$cena = $_POST['cena'];
		$vat = $_POST['vat'];
		$ilosc = $_POST['ilosc'];
		if ($ilosc > 0 && $data_w >= date('Y-m-d')) 
		{
        $status_dostepnosci = 1;
		} 
		else 
		{
        $status_dostepnosci = 0;
		}
		$kategoria = $_POST['kategoria'];
		$gabaryt = $_POST['gabaryt'];
		$zdjecie = addslashes(file_get_contents($_FILES['zdjęcie']['tmp_name']));

        if(!empty($id)) 
		{	
            $query = "UPDATE products SET tytul = '$tytuł', opis = '$opis', data_modyfikacji = '$data_m', data_wygasniecia = '$data_w',
						cena_netto = '$cena', podatek_vat = '$vat', ilosc_sztuk_w_magazynie = '$ilosc', kategoria = '$kategoria',
						gabaryt_produktu = '$gabaryt', zdjecie = '$zdjecie' WHERE id = '$id' LIMIT 1";
            $result = mysqli_query($link, $query);

            if($result) 
			{  
                echo "<script>window.location.href='produkty.php';</script>";
                exit();
            } 
			else 
			{
                echo "<center>Błąd podczas edycji: ".mysqli_error($link)."</center>";
            }
        }
    }
}

// funkcja PokazObraz() wyświetla obraz produktu o danym id

function PokazObraz()
{
	global $link;
	
    if(isset($_GET['id'])) 
	{
		$id = $_GET['id'];
	}
	
	$query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
    $result = mysqli_query($link, $query);
	
	if($result)
	{
		while($row = mysqli_fetch_array($result)) 
		{
			echo '<center><p>Obraz produktu:  <b>'.$row['tytul'].'</b></p><img src="data:image/jpeg;base64,'.base64_encode($row['zdjecie']).'"/></center>';
		}
	}
	else 
	{
           echo "<center>Błąd podczas wyświetlania obrazu: " . mysqli_error($link)."</center>";
    }
}

// funkcja PokazProdukty() pokazuje tabelę z produktami oraz umożliwia wywołanie
// funkcji EdytujProdukt() i UsunProdukt() oraz PokazObraz()

function PokazProdukty()
{
	global $link;

    $query = "SELECT * FROM products ORDER BY id ASC";
    $result = mysqli_query($link, $query);
	
	if($result)
	{
		
		echo '<h1 class="naglowek">Lista produktów</h1><center><table>
			<tr><th class="tn">Id</th><th class="tn">Tytuł</th><th class="tn">Opis</th><th class="tn">Data utworzenia</th><th class="tn">Data modyfikacji</th>
			<th class="tn">Data wygaśnięcia</th><th class="tn">Cena netto</th><th class="tn">Podatek VAT</th><th class="tn">Ilość sztuk w magazynie</th>
			<th class="tn">Status dostępności</th><th class="tn">Kategoria</th><th class="tn">Gabaryt</th></tr>';
			
		while($row = mysqli_fetch_array($result)) 
		{
			echo '
					<tr>
						<td class="tdid"><b>'.$row['id']. '<b></td>
						<td class="tdnazwa"><b>'.$row['tytul'].'<b></td>
						<td class="tdane">'.$row['opis']. '</td>
						<td class="tdane">'.$row['data_utworzenia'].'</td>
						<td class="tdane">'.$row['data_modyfikacji'].'</td>
						<td class="tdane">'.$row['data_wygasniecia'].'</td>
						<td class="tdane">'.$row['cena_netto'].'</td>
						<td class="tdane">'.$row['podatek_vat'].'</td>
						<td class="tdane">'.$row['ilosc_sztuk_w_magazynie'].'</td>
						<td class="tdane">'.$row['status_dostepnosci'].'</td>
						<td class="tdane">'.$row['kategoria'].'</td>
						<td class="tdane">'.$row['gabaryt_produktu'].'</td>
						<td class="tobraz"><a href="produkty.php?funkcja=obraz&id='.$row['id'].'"><b>Obraz</b></a></td>	
						<td class="tdusun"><a href="produkty.php?funkcja=usun&id='.$row['id'].'"><b>Usuń</b></a></td>
						<td class="tdedytuj"><a href="produkty.php?funkcja=edytuj&id='.$row['id'].'"><b>Edytuj</b></a></td>
					</tr>
				';
		}
		echo '</table></center><br>';
	}
	else
	{
		echo "<center>Brak produktów</center>";
	}
	
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'obraz')
	{
		PokazObraz();
	}
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun')
	{
		UsunProdukt();
	}
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj')
	{
		EdytujProdukt();
	}
}

// wywołujemy funkcję PokazProdukty() i DodajProdukt()

PokazProdukty();
DodajProdukt();

?>