<?php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przekierowania gracza do index.php jesli znalazl sie w tutaj bez uprzedniego logowania lub wybrania misji */
    if(!isset($_SESSION['login_token']))        //index jest uniwersalny bo jesli jest zalogowany to autmoatycznie przejdzie do gra.php
    {
        header('Location: index.php');
        exit();                       //zmienne nie istnieja bez logowania, bez sensu ladowac
    }
    /**/

    /* przekierowanie gracza jesli nie kupil */
    if(!isset($_POST['blacksmith']))
    {
        header('Location: town.php');
        exit();                       //bez sensu ladowac
    }
    /**/

    /* nawiazywanie polaczenia z baza danych */
    require_once"connect.php";                                  //wstawia tu kod z connect.php i sprawdza czy nie zostalo to zrobione wczesniej

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    /**/

    $id = $_SESSION['player_id'];           //musialo byc tak bo zmienne sesyjne nie wchodza do zapytan sql


    /* kupowanie */
    $cost = (500+($_SESSION['weapon_lvl']*100));        //tyle kosztuje nowy sprzet

    if($_SESSION['gold'] >= $cost)                    //sprawdzamy czy ma dosc pieniedzy
    {
        $_SESSION['gold'] = $_SESSION['gold'] - $cost;            //udalo sie kupic wiec odejmujemy zmienna sesyjna jak w bazie
        $connection->query("UPDATE players SET GOLD = GOLD-$cost WHERE players.player_id = $id");             // kupno

        $_SESSION['weapon_lvl'] = $_SESSION['weapon_lvl'] + 1;      //zmienna sesyjna zaktualizowana jak w bazie danych
        $connection->query("UPDATE players SET WEAPON_LVL = WEAPON_LVL+1 WHERE players.player_id = $id");             // upgrade wyposazenia
        header('Location: town.php');                          //odsylamy
    }
    else        //nie ma dosc kasy
    {
        $_SESSION['u_broke_af'] = '<span style="color: red">Not enough gold</span>';         //flaga ze nie ma dosc kasy
        header('Location: town.php');                          //odsylamy
    }
    /**/

    

    $connection->close();       //trzeba zamknac polaczenie
?>