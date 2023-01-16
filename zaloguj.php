<?php           //ten plik pobierze i zapisze dane gracza z bazy na podstawie index.php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przekierowanie gracza do index.php jesli znalazl sie w zaloguj.php bez uprzedniego 
    wpisania loginu lub hasla (nie bedzie mogl wpisac z palca linku) */
    if(!isset($_POST['login']) || !isset($_POST['password']))
    {
        header('Location: index.php');
        exit();                                                 //nie ma potrzeby ladowac dalej wiec wychodzimy
    }
    /**/
    

    /* nawiazywanie polaczenia z baza danych */
    require_once"connect.php";                                  //wstawia tu kod z connect.php i sprawdza czy nie zostalo to zrobione wczesniej

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    /**/

    
    
    /* pobieranie wyslanych lancuchow z index.php do zmiennych metoda POST */
    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");         //obie funkcje zabespieczaja przed wstrzykiwaniem SQL
    //$password = htmlentities($password, ENT_QUOTES, "UTF-8");   //poprzez zamiane znakow na encje html
    // nie ma potrzeby juz przepuszczac hasla bo uzywamy hasha wiec szukamy po loginie tylko i potem sprawdzamy czy haslo jest ok 
    //czyli zmienilem tez zapytanie ponizej
    /**/

    /* zapytanie pobierajace dane konkretnego uzytkownika o pobranym loginie i hasle */
    //$sql = "SELECT * FROM players WHERE login='$login' AND password='$password'";

    //stara czesc kodu wstawiana ponizej, trzeba wpisac bezposrednio zapytanie zeby zabespieczyc
    //przed wstrzykiwaniem SQL dzieki mysqli_real_escape_string()
    /**/


    /* wyciagniecie z bazy danych uzytkownika do zmiennej $result i sprawdzenie poprawnosci */
    if($result = $connection->query(           //przypisuje wartosci do $result i sprawdza czy pobrano wartosc (czy jest taki login)
        sprintf("SELECT * FROM players WHERE login='%s'",
        mysqli_real_escape_string($connection, $login))
        ))                                     //ten dziwny zapis wykrywa wstrzykiwanie
    {
        
        if($result->num_rows > 0)              //sprawdzenie ilosci pobranych graczy (wierszy)
        {
            $row = $result->fetch_assoc();     //nazywa pobrane kolumny po nazwach z bazy i umieszcza je w zmiennej $row

            if(password_verify($password, $row['password']))  //tu sprawdzamy czy podane haslo zgadza sie z tym z bazy
            {
                $_SESSION['login_token'] = true;   //flaga oznaczajaca ze udalo sie zalogowac poprawnie
                

                /* przekazywanie pobranych zmiennych do globalnych zmiennych sesyjnych */
                $_SESSION['player_id'] = $row['player_id']; //zeby wiedziec kto jest zalogowany
                $_SESSION['login'] = $row['login'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['nickname'] = $row['nickname'];
                $_SESSION['avatar'] = $row['avatar'];
                $_SESSION['class_type'] = $row['class_type'];
                $_SESSION['lvl'] = $row['lvl'];
                $_SESSION['prowess'] = $row['PROWESS'];
                $_SESSION['insight'] = $row['INSIGHT'];
                $_SESSION['resolve'] = $row['RESOLVE'];
                $_SESSION['HP'] = $row['HP'];
                $_SESSION['gold'] = $row['GOLD'];
                $_SESSION['weapon_lvl'] = $row['WEAPON_LVL'];
                $_SESSION['XP'] = $row['XP'];
                $_SESSION['skill_points'] = $row['skill_points'];
                /**/

                /* pobieramy misje, ktore uzyjemy w lesie za pierwszym razem */
                $rand_id = array_rand(array_flip(range(1, 15)), 3);          //wybiera 3 cyfry z przedzialu (1,15) jako array, nie beda sie powtarzac

                $mission1 = $connection->query("SELECT * FROM missions WHERE mission_id='$rand_id[0]'");      //pobieramy te 3 misje
                $mission2 = $connection->query("SELECT * FROM missions WHERE mission_id='$rand_id[1]'");
                $mission3 = $connection->query("SELECT * FROM missions WHERE mission_id='$rand_id[2]'");

                $mission1_row = $mission1->fetch_assoc();           //to nazywa kolumny po ich nazwach z bazy
                $mission2_row = $mission2->fetch_assoc();
                $mission3_row = $mission3->fetch_assoc();

                $_SESSION['mission1_name'] = $mission1_row['name'];                     //przypisujemy zmienne sesyjne uzyjemy w forset.php
                $_SESSION['mission1_description'] = $mission1_row['description'];

                $_SESSION['mission2_name'] = $mission2_row['name'];
                $_SESSION['mission2_description'] = $mission2_row['description'];

                $_SESSION['mission3_name'] = $mission3_row['name'];
                $_SESSION['mission3_description'] = $mission3_row['description'];
                /**/

                unset($_SESSION['login_fail']);     //usuwamy zmienna (profilaktycznie) bo udalo sie zalogowac
                $result->close();                   //czysci rezultaty zapytania
                header('Location: gra.php');        //przekierowywuje na gre
            }
            else                                    //dobry login zle haslo
            {
                $_SESSION['login_fail'] = ' <span style="color: red"> The password or login 
                was incorrect! </span>';            //uzywamy w index.php gdy nie udalo sie zalogowac
                header('Location: index.php');      // odsyla z powrotem do index.php zeby mozna sprobowac raz jeszcze 
            }
        }
        else                                    //login nie pasowal do zadnego w bazie
        {
            $_SESSION['login_fail'] = ' <span style="color: red"> The password or login 
            was incorrect! </span>';            //uzywamy w index.php gdy nie udalo sie zalogowac
            header('Location: index.php');      // odsyla z powrotem do index.php zeby mozna sprobowac raz jeszcze 
        }
    }
    else
    {
        $_SESSION['login_fail'] = ' <span style="color: red"> Connection to data base has been interrupted </span>';            //uzywamy w index.php gdy nie udalo sie zalogowac
            header('Location: index.php');      // odsyla z powrotem do index.php zeby mozna sprobowac raz jeszcze
    }
    /**/

    
    /* zamkniecie polaczenia */
    $connection->close();
    /**/
?>