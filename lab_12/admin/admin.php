<link rel="stylesheet" href="../css/style_admin.css">

<?php

// rozpoczynamy sesję

session_start();

// załączamy zawartość pliku cfg.php

include('../cfg.php');

// funkcja FormularzLogowania() zwraca formularz potrzebny do zalogowania się

function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="naglowek">Panel CMS</h1>
            <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                <table>
                    <tr><td>Login: </td><td><input type="text" name="login" /></td></tr>
                    <tr><td>Hasło: </td><td><input type="password" name="pass" /></td></tr>
                    <tr><td></td><td><input type="submit" name="logowanie" value="Zaloguj się" /></td></tr>
                </table>
            </form>
    </div>
    ';

    return $wynik;
}

// funkcja ListaPodstron() pobiera odpowiednie dane z naszej bazy danych i 
// wyświetla listę podstron  
// (kolejno: id podstrony, tytuł podstrony, guzik do usuwania podstrony i guzik do edytowania podstrony),
// w zależności od parametrów $_GET wywołuje funkcję UsunPostrone() lub EdytujPodstrone()
// na końcu wywołuje też funkcję DodajNowaPodstrone()

function ListaPodstron()
{
	// wyszukujemy podstrony z tabeli page_list według id rosnąco
	
	global $link;
    $query = "SELECT * FROM page_list ORDER BY id ASC";
    $result = mysqli_query($link, $query);
	
	echo '<h1 class="naglowek">Lista podstron</h1><center><table>';
	
	// jeśli zapytanie się udało, wyświetlamy wynik zapytania
	// jeśli nie, wyświetlamy komunikat 'Bład podczas wyświetlania strony'
	
	if($result)
	{
		// jeśli wynik zapytania znalazł podstrony, to wyświetlamy je 
		// w przeciwnym przypadku, wyświetlamy komunikat 'Brak podstron'
		
		$brak = 0;
		
		while ($row = mysqli_fetch_array($result)) 
		{
			$brak = 1;
			echo '
					<tr>
						<td class="tdid"><b>'.$row['id'] . '<b></td>
						<td class="tdnazwa"><b>'.$row['page_title'].'<b></td>
						<td class="tdusun"><a href="admin.php?funkcja=usun&id='.$row['id'].'"><b>Usuń</b></a></td>
						<td class="tdedytuj"><a href="admin.php?funkcja=edytuj&id='.$row['id'].'"><b>Edytuj</b></a></td>
					</tr>
				';
		}
		
		echo '</table></center><br>';
		
		if($brak == 0)
		{
			echo "<center>Brak podstron</center>";
		}
	}
	else
	{
		echo '<center>Bład podczas wyświetlania strony</center>';
	}
	
	// jeśli parametr $_GET['funkcja'] jest ustawiony i ma wartość 'usun', to wywoływana jest funkcja UsunPodstrone()
	
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun')
	{
		UsunPodstrone();
	}
	
	// jeśli parametr $_GET['funkcja'] jest ustawiony i ma wartość 'edytuj', to wywoływana jest funkcja EdytujPodstrone()

	
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj')
	{
		EdytujPodstrone();
	}
	
	// na końcu wywołujemy funkcję DodajNowaPodstrone()
	
	DodajNowaPodstrone();
}

// funkcja EdytujPodstrone() wyświetla formularz do edycji podstrony,
// następnie edytuje podaną podstronę według danych podanych we wcześniej wspomnianym formularzu

function EdytujPodstrone()
{
	// wyszukujemy podstronę z tabeli page_list według id = $id

	global $link;
	
	if(isset($_GET['id'])) 
	{
		$id = $_GET['id'];
	}
	
	$query = "SELECT * FROM page_list WHERE id='$id' LIMIT 1";
	$result = mysqli_query($link ,$query);
	$row = mysqli_fetch_array($result);
	
	// wyświetlamy formularz edycji podstrony z ustawionymi domyślnymi wartościami równymi obecnym wartościom podstrony
	
	echo '
    <div>
        <h1 class="naglowek"><b>Edytuj podstronę<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title" size="108" value='.$row['page_title'].' /></td></tr>
                    <tr><td><b>Treść podstrony: <b/></td><td><textarea rows=20 cols=100 name="page_content"/>'.$row['page_content'].'</textarea></td></tr>
                    <tr><td><b>Status podstrony: <b/></td><td><input type="checkbox" name="status" checked /></td></tr>
                    <tr><td></td><td><input type="submit" name="edytowanie" value="Edytuj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
	// jeśli przesłaliśmy formularz, to edytujemy podstronę
	
    if(isset($_POST['edytowanie'])&& isset($_GET['id'])) 
	{
        $id = $_GET['id'];
        $tytul = $_POST['page_title'];
        $tresc = $_POST['page_content'];
        $status = isset($_POST['status']) ? 1 : 0;

        if(!empty($id)) 
		{
			// zmieniamy wartość podstrony w bazie danych
			
            $query = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = '$status' WHERE id = '$id' LIMIT 1";
            $result = mysqli_query($link, $query);

			// jeśli się udało, przenosimy się do admin.php
			// w przeciwnym wypadku zostanie wyświetlony komunikat o błędzie

            if($result) 
			{  
                echo "<script>window.location.href='admin.php';</script>";
                exit();
            } 
			else 
			{
                echo "<center>Błąd podczas edycji: ".mysqli_error($link)."</center>";
            }
        }
    }
}

// funkcja DodajNowaPodstrone() wyświetla formularz do dodawania nowej podstrony,
// a następnie dodaje nową podstroną z danymi podanymi w wcześniej wspomnianym formularzu 

function DodajNowaPodstrone()
{
    global $link;
	
	// wyświetlamy formularz do dodawania nowej podstrony
	
	echo '
    <div>
        <h1 class="naglowek"><b>Dodaj podstronę<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title_add" size="108"/></td></tr>
                    <tr><td><b>Treść podstrony: <b/></td><td><textarea rows=20 cols=100 name="page_content_add" /></textarea></td></tr>
                    <tr><td><b>Status podstrony: <b/></td><td><input type="checkbox" name="status_add" checked /></td></tr>
                    <tr><td></td><td><input type="submit" name="dodawanie" value="Dodaj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
	// jeśli przesłaliśmy formularz, to dodajemy podstronę
	
    if(isset($_POST['dodawanie'])) 
	{
        $tytul = $_POST['page_title_add'];
        $tresc = $_POST['page_content_add'];
        $status = isset($_POST['status_add']) ? 1 : 0;

		// dodajemy podstronę w bazie danych

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', '$status')";
        $result = mysqli_query($link, $query);

		// jeśli się udało, przenosimy się do admin.php
		// w przeciwnym wypadku zostanie wyświetlony komunikat o błędzie

        if($result) 
		{           
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } 
		else 
		{
            echo "<center>Błąd podczas dodawania podstrony: " . mysqli_error($link)."</center>";
        }
    }
}

// funkcja UsunPodstrone() usuwa podstronę usuwa podstronę o podanym id

function UsunPodstrone()
{
    global $link;
	
	// jeśli jest ustawiona wartość $_GET['id'], to usuwamy podstronę
	
    if(isset($_GET['id'])) 
	{
		// usuwamy podstronę o id = $id w bazie danych
		
        $id = $_GET['id'];
        $query = "DELETE FROM page_list WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);

		// jeśli się udało, przenosimy się do admin.php
		// w przeciwnym wypadku zostanie wyświetlony komunikat o błędzie
		
        if($result) 
		{         
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        }
		else 
		{
            echo "<center>Błąd podczas usuwania podstrony: " . mysqli_error($link)."</center>";
        }
    }
}

// sprawdzamy, czy jesteśmy zalogowani
// jeśli tak, to wyświetla się nam odpowiedni komunikat,
// wywołuje się funkcja ListaPodstron(),
// przekierowania do Kontaktu i Panelu dla Kategorii oraz dla Produktów 
// oraz wyświetla napis Wyloguj się, który umożliwia wylogowanie się
// jeśli nie, to znowu wyświetla się FormularzLogowania

if(isset($_SESSION['status_logowania']) && $_SESSION['status_logowania'] == 1)
{
	echo '<center><br><b>Jesteś zalogowany i masz dostęp do metod administracyjnych.</b><br><br></center>';
	ListaPodstron();
	echo '<h2 class="naglowek"><a class="linki" href="contact.php">Kontakt</a><br></h2>';
	echo '<h2 class="naglowek"><a class="linki" href="kategorie.php">Zarządzaj kategoriami</a><br></h2>';
	echo '<h2 class="naglowek"><a class="linki" href="produkty.php">Zarządzaj produktami</a><br></h2>';
	echo '<h2 class="naglowek"><a href="wyloguj.php">Wyloguj się</a></h2>';
} 
else 
{
	echo FormularzLogowania();
}

// sprawdzamy, czy podane w formularzu logowania dane zgadzają się,
// jeśli tak, to zostaliśmy zalogowani do Panelu CMS,
// w przeciwnym przypadku otrzymujemy odpowiedni komunikat

if(isset($_POST['login']) && isset($_POST['pass']))
{
	if($_POST['login'] == $login && $_POST['pass'] == $pass){
		$_SESSION['status_logowania'] = 1;
		echo "<script>window.location.href='admin.php';</script>";
	}
	else{
		echo '<center><br><br><br><br><br>Wprowadzono niepoprawne dane! <br><br>Spróbuj zalogować się ponownie.</center>';
	}
}

?>