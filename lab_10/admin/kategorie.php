<?php

include('../cfg.php');

function DodajKategorie()
{
	global $link;

	echo '
    <div>
        <h1 class="naglowek"><b>Dodaj kategorię<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Nazwa kategorii: <b/></td><td><input type="text" name="name" required /></td></tr>
                    <tr><td><b>Matka kategorii: <b/></td><td><input type="text" name="mother" value=0 required /></td></tr>
                    <tr><td></td><td><input type="submit" name="dodawanie" value="Dodaj" /></td></tr>
                </table>
            </form>
    </div>
    ';
	
	if(isset($_POST['dodawanie'])) 
	{
		$nazwa = $_POST['name'];
        $matka = $_POST['mother'];
		
        $query = "INSERT INTO categories (mother, name) VALUES ('$matka', '$nazwa')";
        $result = mysqli_query($link, $query);

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

function FormularzDoUsuwania()
{
	echo '
    <div>
        <h1 class="naglowek"><b>Usuń kategorię<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>Id kategorii: <b/></td><td><input type="text" name="id1" required /></td></tr>
                    <tr><td></td><td><input type="submit" name="usuwanie" value="Usuń" /></td></tr>
                </table>
            </form>
    </div>
    ';	
}

function UsunKategorie($id)
{	
	global $link;

	$query = "SELECT id FROM categories WHERE mother = '$id'";
    $result = mysqli_query($link, $query);
	
	if($result)
	{
		while($row = mysqli_fetch_array($result))
		{
			UsunKategorie($row['id']);
		}
	}
	
	$query1 = "DELETE FROM categories WHERE id = '$id' LIMIT 1";
	$result1 = mysqli_query($link, $query1);
	if(!$result1)
	{
		echo '<center>Błąd<br><center>';
	}
}

function FormularzDoEdytowania()
{
	echo '
    <div>
        <h1 class="naglowek"><b>Edytuj kategorię<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    
                    <tr><td></td><td><input type="submit" name="edytowanie" value="Edytuj" /></td></tr>
                </table>
            </form>
    </div>
    ';
}

function EdytujKategorie()
{
    global $link;
		
	echo '
	<div>
		<h2 class="naglowek"><b>Edycja kategorii<b/></h2>
			<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
				<table>
					<tr><td><b>Id kategorii: <b/></td><td><input type="text" name="id2" required /></td></tr>
					<tr><td><b>Nazwa kategorii: <b/></td><td><input type="text" name="name" /></td></tr>
					<tr><td><b>Matka kategorii: <b/></td><td><input type="text" name="mother" /></td></tr>
					<tr><td></td><td><input type="submit" name="edytowanie" value="Edytuj" /></td></tr>
				</table>
			</form>
	</div>
	';
		
	if(isset($_POST['edytowanie'])) 
	{	
		$id = $_POST['id2'];
		$nazwa = $_POST['name'];
		$matka = $_POST['mother'];
		
		$query = "SELECT * FROM categories WHERE id = '$id'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		
		if(is_null($row))
		{
			echo 'Nie istnieje kategoria o id '.$id;
			exit();
		}
		$query = "UPDATE categories SET name = '$nazwa', mother = '$matka' WHERE id = '$id' LIMIT 1";
		$result = mysqli_query($link, $query);
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

function PokazKategorie($mother = 0, $ile = 0)
{
	global $link;

    $query = "SELECT * FROM categories WHERE mother = '$mother'";
    $result = mysqli_query($link, $query);
	if($result){
		$brak = 0;
		while($row = mysqli_fetch_array($result)) 
		{	
			$brak = 1;
			for($i=0; $i<$ile; $i++)
			{
					echo '&nbsp;&nbsp;&nbsp;>>>>>';
			}
			echo ' '.$row['id'].' '.$row['name'].'<br><br>';
			PokazKategorie($row['id'], $ile+1);
		}
		if($brak == 0 && $ile == 0)
		{
			echo "</center>Brak kategorii</center>";
		}
	}
}

echo '<h1 class="naglowek">Lista kategorii</h1>';
PokazKategorie();
DodajKategorie();
FormularzDoUsuwania();
if(isset($_POST['usuwanie']))
{
	$id = $_POST['id1'];
	UsunKategorie($id);
	echo "<script>window.location.href='kategorie.php';</script>";
    exit();
}
EdytujKategorie();


?>