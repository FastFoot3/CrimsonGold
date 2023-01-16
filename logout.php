<?php           //ten plik bedzie odpowiadal za mozliwosc wylogowania sie 

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* zniszczenie sesji i odeslanie gracza na index.php (warunek na poczatku index.php nie
    wykona sie bo zmienna $_SESSION['login_token'] przestanie istniec) */
    session_unset();
    session_destroy();

    header("Location: index.php");
    /**/

?>