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
    if(!isset($_POST['attribute1']) || !isset($_POST['attribute2']))
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

    $attribute1 = $_POST['attribute1'];         //przeslane wartosci
    $attribute2 = $_POST['attribute2'];


    /* trenowanie */

    if($_SESSION['skill_points'] >= ($attribute1 + $attribute2))                    //sprawdzamy czy ma dosc skill pointsow
    {
        $_SESSION['skill_points'] = $_SESSION['skill_points'] - ($attribute1 + $attribute2);            //udalo sie trenowac wiec odejmujemy zmienna sesyjna jak w bazie
        $connection->query("UPDATE players SET skill_points = skill_points-($attribute1 + $attribute2) WHERE players.player_id = $id");             // zuzywa skill points

        switch($_SESSION['class_type'] )    
        {                                       //trzeba dac odpowiednie atrybuty w zaleznosci od szkoly wiedzminskiej
            case 1:
                // $attribute1 = "Insight: ";                   //KOD Z TOWN.PHP    do referencji
                // $attribute2 = "Resolve: ";

                $_SESSION['insight'] = $_SESSION['insight'] + $attribute1;      //zmienna sesyjna zaktualizowana jak w bazie danych
                $_SESSION['resolve'] = $_SESSION['resolve'] + $attribute2;
                $connection->query("UPDATE players SET INSIGHT = INSIGHT+$attribute1 WHERE players.player_id = $id");             // trening
                $connection->query("UPDATE players SET RESOLVE = RESOLVE+$attribute2 WHERE players.player_id = $id");
            break;

            case 2:
                // $attribute1 = "Resolve: ";                   //KOD Z TOWN.PHP    do referencji
                // $attribute2 = "Prowess: ";

                $_SESSION['resolve'] = $_SESSION['resolve'] + $attribute1;      //zmienna sesyjna zaktualizowana jak w bazie danych
                $_SESSION['prowess'] = $_SESSION['prowess'] + $attribute2;
                $_SESSION['HP'] = $_SESSION['prowess'];
                $connection->query("UPDATE players SET RESOLVE = RESOLVE+$attribute1 WHERE players.player_id = $id");             // trening
                $connection->query("UPDATE players SET PROWESS = PROWESS+$attribute2 WHERE players.player_id = $id");
                $connection->query("UPDATE players SET HP = PROWESS WHERE players.player_id = $id");
            break;

            case 3:
                // $attribute1 = "Insight: ";                   //KOD Z TOWN.PHP    do referencji
                // $attribute1 = "Prowess: ";

                $_SESSION['insight'] = $_SESSION['insight'] + $attribute1;      //zmienna sesyjna zaktualizowana jak w bazie danych
                $_SESSION['prowess'] = $_SESSION['prowess'] + $attribute2;
                $_SESSION['HP'] = $_SESSION['prowess'];
                $connection->query("UPDATE players SET INSIGHT = INSIGHT+$attribute1 WHERE players.player_id = $id");             // trening
                $connection->query("UPDATE players SET PROWESS = PROWESS+$attribute2 WHERE players.player_id = $id");
                $connection->query("UPDATE players SET HP = PROWESS WHERE players.player_id = $id");
            break;
        }

        
        header('Location: town.php');                          //odsylamy
    }
    else        //nie ma dosc kasy
    {
        $_SESSION['u_stupid'] = '<span style="color: red">Not enough skill points</span>';         //flaga ze nie ma dosc skilla
        header('Location: town.php');                          //odsylamy
    }
    /**/

    

    $connection->close();       //trzeba zamknac polaczenie
?>