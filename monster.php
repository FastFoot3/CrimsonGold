<?php           //ten plik pobiera odpowiedniego potwora wzgledem trudnosci i poziomu gracza

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przekierowania gracza do index.php jesli znalazl sie w tutaj bez uprzedniego logowania */
    if(!isset($_SESSION['login_token']))
    {
      header('Location: index.php');
      exit();                       //zmienne nie istnieja bez logowania, bez sensu ladowac
    }
    /**/
    
    /* przekierowanie gracza do forest.php jesli znalazl sie w tutaj bez uprzedniego 
    wyboru misji */
    if(!isset($_POST['difficulty']))
    {
        header('Location: forest.php');
        exit();                                                 //nie ma potrzeby ladowac dalej wiec wychodzimy
    }
    /**/
    

    

    /* nawiazywanie polaczenia z baza danych */
    require_once"connect.php";                                  //wstawia tu kod z connect.php i sprawdza czy nie zostalo to zrobione wczesniej

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    /**/

    /* pobieranie wyslanej trudnosci */
    $_SESSION['difficulty'] = $_POST['difficulty'];
    /**/

    /* ustalanie poziomu potwora */
    switch($_SESSION['difficulty'])
    {
        case 1:
            $monster_lvl = $_SESSION['lvl'] - 2;
        break;

        case 2:
            $monster_lvl = $_SESSION['lvl'];
        break;

        case 3:
            $monster_lvl = $_SESSION['lvl'] + 2;
        break;
    }
    /**/

    /* pobieramy potwora */
    $sql = "SELECT * FROM monsters WHERE lvl='$monster_lvl'";       //zapytanie zeby bylo schludnie

    if($result = $connection->query($sql))                                     
    {
        
        if($result->num_rows > 0)              //sprawdzenie ilosci pobranych potworow (wierszy)
        {
            $random = random_int(1, $result->num_rows);       //losuje potwora jesli jest ich wiecej niz jeden

            for($i=1; $i <= $random; $i++)        //zeby pobrac nastepny wiersz trzeba wywolac funkcje fetch drugi raz, tutaj wykona sie 1 lub wiecej razy
            {
                $row = $result->fetch_assoc();     //nazywa pobrane kolumny po nazwach z bazy i umieszcza je w zmiennej $row
            }


            /* dane potwora */
            $_SESSION['monster_id'] = $row['monster_id'];
            $_SESSION['monster_name'] = $row['name'];
            $_SESSION['monster_lvl'] = $row['lvl'];
            $_SESSION['monster_power'] = $row['POWER'];
            $_SESSION['monster_damage'] = $row['DAMAGE_LVL'];
            $_SESSION['monster_HP'] = $row['HP'];
            $_SESSION['monster_dodge'] = $row['DODGE'];
            $_SESSION['monster_image'] = $row['image'];
            /**/

            $result->close();                   //wazne zeby zamknac
            $_SESSION['fight_token'] = true;   //flaga oznaczajaca ze mozna walczyc smialo
            header('Location: fight.php');        //przekierowywuje na walke
        }
        else
        {
            header('Location: forest.php');          //cos poszlo nie tak to odsylamy spowrotem
        }
    }
    else
    {
        header('Location: forest.php');          //cos poszlo nie tak to spowrotem wracaj
    }
    /**/

    /* zamkniecie polaczenia */
    $connection->close();
    /**/
?>