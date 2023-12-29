<link rel="stylesheet" href="../css/style_admin.css">

<?php


session_start();
include('../cfg.php');
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="naglowek">Panel CMS</h1>
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="">
                    <tr><td class="kog4_t">Login: </td><td><input type="text" name="login" /></td></tr>
                    <tr><td class="log4_t">Hasło: </td><td><input type="password" name="pass" /></td></tr>
                    <tr><td></td><td><input type="submit" name="x1_submit" value="Zaloguj się" /></td></tr>
                </table>
            </form>
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
		echo '<h1 class="naglowek">Lista podstron</h1><center><table>';
		if($result){
			while ($row = mysqli_fetch_array($result)) {
				echo '<tr><td class="tdid"><b>'.$row['id'] . '<b></td><td class="tdnazwa"><b>'. $row['page_title'].'<b></td><td class="tdusun"><a href="admin.php?funkcja=usun&id='.$row['id'].'"><b>Usuń</b></a></td><td class="tdedytuj"><a href="admin.php?funkcja=edytuj&id='.$row['id'].'"><b>Edytuj</b></a></td></tr>';
			}
			echo '</table></center><br>';
		}
		else{
			echo "Brak stron";
		}
    }
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun'){
		UsunPodstrone();
	}
	if(isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj'){

		EdytujPodstrone();
	}
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
    <div>
        <h1 class="naglowek"><b>Edytuj podstronę<b/></h1>
            <form method="post" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table ">
                    <tr><td class="edit_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title" size="108" value='.$row['page_title'].' /></td></tr>
                    <tr><td class="edit_4t"><b>Treść podstrony: <b/></td><td><textarea rows=20 cols=100 name="page_content"/>'.$row['page_content'].'</textarea></td></tr>
                    <tr><td class="edit_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status" checked /></td></tr>
                    <tr><td></td><td><input type="submit" name="x2_submit" class="edycja" value="Edytuj" /></td></tr>
                </table>
            </form>
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
                echo "<script>window.location.href='admin.php';</script>";
                exit();
            } else {
                echo "<center>Błąd podczas edycji: " . mysqli_error($link)."</center>";
            }
        }
    }
}


function DodajNowaPodstrone()
{
    global $link;
	echo '
    <div>
        <h1 class="naglowek"><b>Dodaj podstronę<b/></h1>
            <form method="post" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="dodaj">
                    <tr><td class="add_4t"><b>Tytuł podstrony: <b/></td><td><input type="text" name="page_title_add" size="108"/></td></tr>
                    <tr><td class="add_4t"><b>Treść podstrony: <b/></td><td><textarea rows=20 cols=100 name="page_content_add" /></textarea></td></tr>
                    <tr><td class="add_4t"><b>Status podstrony: <b/></td><td><input type="checkbox" name="status_add" checked /></td></tr>
                    <tr><td></td><td><input type="submit" name="x3_submit" class="dodaj" value="Dodaj" /></td></tr>
                </table>
            </form>
    </div>
    ';
    if (isset($_POST['x3_submit'])) {
        $tytul = $_POST['page_title_add'];
        $tresc = $_POST['page_content_add'];
        $status = isset($_POST['status_add']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', $status)";
        $result = mysqli_query($link, $query);

        if ($result) {           
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } else {
            echo "<center>Błąd podczas dodawania podstrony: " . mysqli_error($link)."</center>";
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
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } else {
            echo "<center>Błąd podczas usuwania podstrony: " . mysqli_error($link)."</center>";
        }
    }
}


if(isset($_SESSION['status_logowania']) && $_SESSION['status_logowania'] == 1){
	echo '<center><br>Jesteś zalogowany i masz dostęp do metod administracyjnych.<br><br></center>';
	ListaPodstron();
	echo '<h2 class=naglowek><a href="wyloguj.php">Wyloguj się</a></h2>';
} 
else {
	echo FormularzLogowania();
}

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