<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            $nr_indeksu = '164419';
            $nrGrupy = '3';
	
            echo 'Jakub Pisarski '.$nr_indeksu.' grupa'.$nrGrupy.' <br/><br/>';
	
            echo 'Zastosowanie metody include() na przykładzie pliku dodatek.php <br/>';
            include 'dodatek.php';
            include 'dodatek.php';
            echo '<br/>';
            
            echo 'Zastosowanie metody require_once() na przykładzie pliku aneks.php <br/>';
            require_once 'aneks.php'; 
            require_once 'aneks.php';
            echo '<br/>';
            
            echo 'Zastosowanie if, else, elseif, switch <br/>';
            $a = 25;
            if ($a == 100)
            {
                echo 'a = 100 <br/>';
            }
            elseif ($a > 100)
            {
                echo 'a > 100';
            }
            else
            {
                echo 'a < 100 <br/>';
            }
            switch ($a) {
                case 10:
                    echo "a=10 <br/>";
                    break;
                case 20:
                    echo "a=20 <br/>";
                    break;
                default:
                    echo "a /= 10 i a /= 20 <br/>";
            }
            echo '<br/>';
            
            echo 'Zastosowanie pętli while() i for() <br/>';
            $B = 5;
            while($B >  0)
            {
                echo 'B = '.$B.'<br/>';
                $B-=1;
            }
            for($C=5; $C>0; $C--)
            {
                echo 'C = '.$C.'<br/>';
            }
            echo '<br/>';
            
            echo 'Zastosowanie typów zmiennych $_GET, $_POST, $_SESSION <br/>';
            echo '$_GET to tablica asocjacyjna zmiennych przekazywana do '
            . 'bieżącego skryptu poprzez parametry adresu URL '
            . '(metoda HTTP GET).<br/>';
            echo '$_POST to tablica asocjacyjna zmiennych przekazywana do '
            . 'bieżącego skryptu metodą żądania HTTP POST.<br/>';
            session_start();
            echo '$_SESSION to tablica asocjacyjna zawierająca zmienne sesji '
            . 'dostępne dla bieżącego skryptu.<br/>';
            $_SESSION['zmienna1']='wartosc1';
            echo 'zmienna1 = '.$_SESSION["zmienna1"].'<br/>';
        ?>
    </body>
</html>
