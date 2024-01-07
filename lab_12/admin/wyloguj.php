<?php 

// plik wyloguj.php umożliwia wylogowanie się, jeśli zalogowaliśmy się do panelu admina CMS
// rozpoczynamy sesję

 session_start(); 
 
 // niszczymy sesję
 
 session_destroy(); 
 
 // przekierowywujemy się do admin.php już jako niezalogowani użytkownicy
 
 header("Location: admin.php"); 
  
?> 