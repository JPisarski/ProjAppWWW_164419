<?php 

// plik wyloguj.php umożliwia wylogowanie się, jeśli zalogowaliśmy się do panelu admina
// rozpoczynamy sesję, następnie ją niszczymy i przekierowujemy się na stronę admina

 session_start(); 
 session_destroy(); 
 header("Location: admin.php"); 
  
?> 