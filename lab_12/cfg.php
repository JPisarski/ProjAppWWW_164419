<?php

// plik cfg.php zawiera zmienne potrzebne do połączenia się z bazą danych,
// wysyłania maila za pomocą PHPMailer i logowania się do panelu admina

// zmienne potrzebne do połączenia się z bazą danych o nazwie 'moja_strona'

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
	
// zmienne potrzebne do wysyłania maila za pomocą PHPMailer
	
$config = array(
'smtp_host' => 'smtp.gmail.com',
'smtp_auth' => true,
'smtp_username' => 'jn242031@gmail.com',
'smtp_password' => 'kcyf itap juln lycn',
'smtp_secure' => 'tls',
'smtp_port' => 587,
);

// zmienne potrzebne do logowania się do panelu admina

$login = 'jpisarski';
$pass = '123PL';
	
// łączymy się z bazą danych

$link = mysqli_connect($dbhost, $dbuser, $dbpass);

// jeśli nie uda się nam połączyć z bazą danych,  wyświetla się komunikat: 'przerwane połączenie'

if(!$link)
{
	echo '<b>przerwane połączenie </b>';
}

// jeśli nie uda się ustanowić domyślnej bazy danych do połączenia, wyświetla się komunikaat: 'nie wybrano bazy'

if(!mysqli_select_db($link, $baza))
{
	echo 'nie wybrano bazy';
}

?>