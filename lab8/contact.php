<?php


include('cfg.php')

function PokazKontakt()
{
	echo '
	<form method="post">
		<label for="email">Adres e-mail: </label>
		<input type="text" name="email" required><br/>
		<label for="temat">Temat: </label>
		<input type="text" name="temat" required><br/>
		<label for="tresc">Treść wiadomości: </label>
		<textarea name="tresc" required></textarea><br>
		<input type="submit" name="wyslj" value="Wyślij wiadomość">
	</form>
	';
}

function WyslijMailKontakt()
{
	if(empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
	{
		echo '[nie_wypelniles_pola]';
		echo PokazKontakt();
	}
	else
	{
		$mail['subject'] = $_POST['temat'];
		$mail['body'] = $_POST['tresc'];
		$mail['sender'] = $_POST['email'];
		$mail['reciptient'] = $odbiorcal
		
		$header = "From: Formularz kontaktowy <".$mail['sender'].">\n";
		$header .= "MIMIE-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: \n";
		$header .= "X-Sender : <".$mail['sender'].">\n";
		$header .= "X-Mailer: PRapWWW mail 1.2\n";
		$header .= "X-Priority: 3\n";
		$header .= "Return-Path: <".$mail['sender'].">\n";
		
		mail($mail['reciptient'],$mail['subject'],$mail['body'],$header);
		
		echo '[wiadomosc_wyslana]';
	}
}

function PrzypomnijHaslo()
{
	
}sd



?>