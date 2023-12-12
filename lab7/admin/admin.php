<?php


session_start();
include('../cfg.php');
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="logowanie">
                    <tr><td class="kog4_t">[email]</td><td><input type="text" name="login" class="logowanie" /></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="pass" class="logowanie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}


function ListaPodstron()
{
	global $link;
    if (!isset($_SESSION['status']) || $_SESSION['status'] == 1) {
        $query = "SELECT * FROM page_list ORDER BY id ASC";
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            echo $row['id'] . ' ' . $row['page_title'].' <a href="admin.php?funkcja=usun&id='.$row['id'].'">Usuń</a> <a href="admin.php?funkcja=edytuj&id='.$row['id'].'">Edytuj</a>  <br />';
        }
    }
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun'){
		UsunPodstrone();
	}
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj'){

		EdytujPodstrone();
	}
	echo FormularzDodawania();
	DodajNowaPodstrone();
	
	
	
}


function EdytujPodstrone()
{
    global $link;
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	}
	$query = "SELECT * FROM page_list WHERE id='$id' LIMIT 1";
	$result = mysqli_query($link ,$query);
	$row = mysqli_fetch_array($result);
	echo '
    <div class="edycja">
        <h1 class="heading"><b>Edytuj podstronę<b/></h1>
        <div class="edycja">
            <form method="post" name="EditForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="edycja">
                    <tr><td class="edit_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title" class="edycja" value='.$row['page_title'].' /></td></tr>
                    <tr><td class="edit_4t"><b>Treść podstrony: <b/></td><td><textarea rows=50 cols=100 name="page_content" class="edycja" />'.$row['page_content'].'</textarea></td></tr>
                    <tr><td class="edit_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status" class="edycja" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x2_submit" class="edycja" value="Edytuj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';
    if (isset($_POST['x2_submit'])&& isset($_GET['id'])) {
        $id = $_GET['id'];
        $tytul = $_POST['page_title'];
        $tresc = $_POST['page_content'];
        $status = isset($_POST['status']) ? 1 : 0;

        if (!empty($id)) {
            $query = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = $status WHERE id = $id LIMIT 1";

            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Edycja zakończona pomyślnie!";
                header("Location: admin.php");
                exit();
            } else {
                echo "Błąd podczas edycji: " . mysqli_error($link);
            }
        }
    }
}


function FormularzDodawania()
{
    $add = '
    <div class="dodaj">
        <h1 class="heading"><b>Dodaj podstronę<b/></h1>
        <div class="dodaj">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="dodaj">
                    <tr><td class="add_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t"><b>Treść podstrony: <b/></td><td><input type="text" name="page_content_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status_add" class="dodaj" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x3_submit" class="dodaj" value="dodaj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}

function DodajNowaPodstrone()
{
    global $link;
    if (isset($_POST['x3_submit'])) {
        $tytul = $_POST['page_title_add'];
        $tresc = $_POST['page_content_add'];
        $status = isset($_POST['status_add']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', $status)";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano podstronę!";
            header("Location: admin.php");
            exit();
        } else {
            echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
        }
    }
}



function UsunPodstrone()
{
    global $link;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie usunięto podstronę!";
            header("Location: admin.php");
            exit();
        } else {
            echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
        }
    }
}


if(isset($_SESSION['status_logowania']) && $_SESSION['status_logowania'] == 1){
	echo 'Jesteś zalogowany i masz dostęp do metod administracyjnych. </br>';
	ListaPodstron();
	echo '<a href="wyloguj.php">Wyloguj się</a>';
} 
else {
	echo FormularzLogowania();
}

if(isset($_POST['login']) && isset($_POST['pass']))
{
	if($_POST['login'] == $login && $_POST['pass'] == $pass){
		$_SESSION['status_logowania'] = 1;
		header("Location: admin.php");
	}
	else{
		echo 'Błędne dane. Spróbuj jeszcze raz.';
	}
}


?>