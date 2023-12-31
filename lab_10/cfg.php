<?php

// plik cfg.php zawiera zmienne potrzebne do połączenia się z bazą danych,
// wysyłania maila za pomocą PHPMailer i logowania się do panelu admina

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
	
$config = array(
'smtp_host' => 'smtp.gmail.com',
'smtp_auth' => true,
'smtp_username' => 'jn242031@gmail.com',
'smtp_password' => 'kcyf itap juln lycn',
'smtp_secure' => 'tls',
'smtp_port' => 587,
);

$login = 'jpisarski';
$pass = '123PL';
	
// łączymy się z bazą danych

$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if(!$link) echo '<b>przerwane połączenie </b>';
if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';

?>