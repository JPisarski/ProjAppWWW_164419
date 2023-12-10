<?php

include ('../cfg.php');

session_start();

function PokazKontakt()
{
    echo'
    <div class="Kontakt">
        <h1 class="heading">poczta:</h1>
        <div class="formularz">
            <form method="post" name="mail" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="formularz">
                    <tr><td class="kon4_t">Temat:</td><td><input type="text" name="kontakt" class="formularz" /></td></tr>
                    <tr><td class="kon4_t">Wiadomość:</td><td><input type="text" name="wiadomosc" class="formularz" /></td></tr>
                    <tr><td class="kon4_t">Nadawca:</td><td><input type="text" name="email" class="formularz" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x4_submit" class="kontakt" value="wyslij" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

}

function WyslijMailKontakt($odbiorca)
{
	if(empty($_POST['temat']) || empty($_POST['wiadomosc']) || empty($_POST['email']))
	{
		echo '[nie_wypelniles_pola]';
		PokazKontakt();
	}
	else
	{
		$mail['subject'] = $_POST['temat'];
		$mail['body'] = $_POST['wiadomosc'];
		$mail['sender'] = $_POST['email'];
		$mail['reciptient'] = $odbiorcal
		
		$header .= "From: Formularz kontaktowy <".$mail['sender'].">\n";
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
	if(empty($_POST['email']))
	{
		echo '[nie_wypelniles_pola]';
		echo '
		<div class="Haslo">
			<h1 class="heading">poczta:</h1>
			<div class="formularzZapomniane">
				<form method="post" name="mail" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
					<table class="formularz">
						<tr><td class="for4_t">email:</td><td><input type="text" name="emailz" class="formularzZapomniane" /></td></tr>
						<tr><td>&nbsp;</td><td><input type="submit" name="x5_submit" class="formularzZapomniane" value="wyslij" /></td></tr>
					</table>
				</form>
			</div>
		</div>
    ';
	}
	else
	{
		$query = "SELECT haslo FROM tabela_uzytkownikow WHERE email =".$_POST['emailz']" LIMIT 1";
        $wynik = mysqli_query($link, $query);
		if (mysqli_num_rows($wynik) > 0) 
		{
			$row = mysqli_fetch_assoc($wynik);
            $haslo = $row['haslo'];
			$mail['subject'] = 'Haslo';
			$mail['body'] = $_POST['wiadomosc'];
			$mail['sender'] = $_POST['emailz'];
			$mail['reciptient'] = $_POST['emailz'];
		
		
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



?>