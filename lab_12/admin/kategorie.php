<link rel="stylesheet" href="../css/style_admin.css">

<?php


// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja DodajKategorie() wyświetla formularz do dodawania kategorii,
// następnie dodaje kategorię według danych podanych we wcześniej wspomnianym formularzu

function DodajKategorie()
{
	global $link;

	// wyświetlamy formularz

	echo '
    <div>
        <h1 class="naglowek"><b>Dodaj kategorię<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Nazwa kategorii: <b/></td><td><input type="text" name="name" required /></td></tr>
                    <tr><td><b>Matka kategorii: <b/></td><td><input type="number" name="mother" value=0 required /></td></tr>
                    <tr><td></td><td><input type="submit" name="dodawanie" value="Dodaj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
	// jeśli przesłaliśmy formularz, to dodajemy kategorię
	
	if(isset($_POST['dodawanie'])) 
	{
		$nazwa = $_POST['name'];
        $matka = $_POST['mother'];
		
		// dodajemy kategorię w bazie danych
		
        $query = "INSERT INTO categories (mother, name) VALUES ('$matka', '$nazwa')";
        $result = mysqli_query($link, $query);

		// jeśli się nam uda, to przekierowujemy się do kategorie.php
		// w przeciwnym wypadku wyświetla się nam odpowiendni komunikat
	
        if($result) 
		{           
            echo "<script>window.location.href='kategorie.php';</script>";
            exit();
        } 
		else 
		{
            echo "<center>Błąd podczas dodawania kategorii: " . mysqli_error($link)."</center>";
        }
    }
}

// funkcja UsunKategorie($id) usuwa kategorie
// o id wskazanym jako argument i wszystkie podkategorie

function UsunKategorie($id)
{	
	global $link;

	// wyszukujemy w bazie danych kategorię o mother = $id 

	$query = "SELECT id FROM categories WHERE mother = '$id'";
    $result = mysqli_query($link, $query);
	
	// jeśli znajdziemy odpowiednią kategorię, to wywołujemy funkcję UsunKategorie() dla podkategorii 
	
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			UsunKategorie($row['id']);
		}
	}
	
	// usuwamy z bazy danych kategorię o id = $id
	
	$query1 = "DELETE FROM categories WHERE id = '$id' LIMIT 1";
	$result1 = mysqli_query($link, $query1);
	
	// jeśli nam się nie uda, wyświetlony zostanie odpowiedni komunikat
	
	if(!$result1)
	{
		echo '<center>Błąd podczas usuwania<br><center>';
	}
}

// funkcja EdytujKategorie() wyświetla formularz potrzebny do edytowania kategorii
// następnie edytuje kategorię według danych podanych we wcześniej wspomnianym formularzu

function EdytujKategorie()
{
    global $link;
		
	if(isset($_GET['id'])) 
	{
		$id = $_GET['id'];
	
		// wyszukujemy w bazie danych kategorię o id = $id
		
		$query = "SELECT * FROM categories WHERE id='$id' LIMIT 1";
		$result = mysqli_query($link ,$query);
		$row = mysqli_fetch_array($result);
			
		// wyświetlamy formularz z ustawionymi wartościami domyślnymi równymi obecnym wartościom kategorii	
			
		echo '
		<div>
			<h1 class="naglowek"><b>Edycja kategorii<b/></h1>
				<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
					<table>
						<tr><td><b>Nazwa kategorii: <b/></td><td><input type="text" name="name" value="'.$row['name'].'" required/></td></tr>
						<tr><td><b>Matka kategorii: <b/></td><td><input type="number" name="mother" value="'.$row['mother'].'" required//></td></tr>
						<tr><td></td><td><input type="submit" name="edytowanie" value="Edytuj" /></td></tr>
					</table>
				</form>
		</div>
		';
		
		// jeśli przesłaliśmy formularz, to edytujemy kategorie
			
		if(isset($_POST['edytowanie'])) 
		{	
			$nazwa = $_POST['name'];
			$matka = $_POST['mother'];
			
			// wyszukujemy odpowiednią kategorię
			
			$query = "SELECT * FROM categories WHERE id = '$id' LIMIT 1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_array($result);
			
			// jeśli nie istnieje taka kategoria, to wyświetlany jest odpowiedni komunikat
			
			if(is_null($row))
			{
				echo '<center>Nie istnieje kategoria o id '.$id.'!</center>';
				exit();
			}
			
			// jeśli istnieje, to edytujemy kategorię w bazie danych
			
			$query = "UPDATE categories SET name = '$nazwa', mother = '$matka' WHERE id = '$id' LIMIT 1";
			$result = mysqli_query($link, $query);
			
			// jeśli się udało, to przekierowujemy się do kategorie.php
			// w przeciwnym wypadku, wyświetlamy odpowiedni komunikat
			
			if($result) 
			{  
				echo "<script>window.location.href='kategorie.php';</script>";
				exit();
			} 
			else 
			{
				echo "<center>Błąd podczas edycji: ".mysqli_error($link)."</center>";
			}
		}   
	}
}

// funkcja PokazKategorie($mother, $ile) wyświetla drzewko kategorii 
// oraz umożliwia wywołanie funkcji UsunKategorie() oraz EdytujKategorie()

function PokazKategorie($mother = 0, $ile = 0)
{
	global $link;

	// wyszukujemy w bazie danych kategorie o mother = $mother

    $query = "SELECT * FROM categories WHERE mother = '$mother'";
    $result = mysqli_query($link, $query);
	
	// jeśli się udało, to kontynuujemy
	// w przeciwnym wypadku wyświetlamy odpowiedni komunikat
	
	if($result)
	{
		// jeśli nie ma kategorii wyświetlamy odpowiedni komunikat,
		//w przeciwnym wypadku wyświetlamy kategorię z napisami Usuń i Edytuj, które kolejno przekierowywują na odpowiednie strony
		
		$brak = 0;
		while($row = mysqli_fetch_array($result)) 
		{	
			$brak = 1;
			for($i=0; $i < $ile; $i++)
			{
					echo '&nbsp;&nbsp;&nbsp;<span style="color: #0000FF;">>>>>></span>';
			}
			echo ' 
					<b><span style="color:#e00cea;">&nbsp;'.$row['id'].' </span><span style="color:blue;">&nbsp;'.$row['name'].'</b>
					<a href="kategorie.php?funkcja=usun&id='.$row['id'].'"><b><span style="color:red;">&nbsp;&nbsp;&nbsp;Usuń</span></b></a>
					<a href="kategorie.php?funkcja=edytuj&id='.$row['id'].'"><b><span style="color:green;">&nbsp;&nbsp;&nbsp;Edytuj</span></b></a><br><br>
				';
			
			// używamy rekurencyjnie PokazKategorie() dla podkategorii
				
			PokazKategorie($row['id'], $ile+1);
		}
		if($brak == 0 && $ile == 0)
		{
			echo "<center>Brak kategorii</center>";
		}
	}
	else
	{
		echo "</center><b>Błąd podczas wyświetlania kategorii<b/></center>";
	}
	
	// jeśli jest ustawiona zmienna $_GET['funkcja'] i ma wartość 'usun',
	// to wywoływana jest funkcja UsunKategorie() dla id = $_GET['id'], potem przekierowujemy się do kategorie.php
	
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun')
	{
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
			UsunKategorie($id);
			echo "<script>window.location.href='kategorie.php';</script>";
			exit();
		}
	}
}

echo '<h1 class="naglowek">Lista kategorii</h1><p style="margin-left: 44%;">';

// wywołujemy funkcje PokazKategorie(), DodajKategorie()

PokazKategorie();

echo '</p>';

DodajKategorie();

// jeśli jest ustawiona zmienna $_GET['funkcja'] i ma wartość 'edytuj',
// to wywoływana jest funkcja EdytujKategorie

if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj')
{
	EdytujKategorie();
}

echo '<h2 class="naglowek"><a href="admin.php">Wróć do poprzedniej strony</a></h2>';

?>