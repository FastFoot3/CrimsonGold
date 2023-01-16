<?php           //ten plik to mechaniki walki

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przekierowania gracza do index.php jesli znalazl sie w tutaj bez uprzedniego logowania lub wybrania misji */
    if(!isset($_SESSION['login_token']) || !isset($_SESSION['fight_token']))        //index jest uniwersalny bo jesli jest zalogowany to autmoatycznie przejdzie do gra.php
    {
      header('Location: index.php');
      exit();                       //zmienne nie istnieja bez logowania, bez sensu ladowac
    }
    /**/

    /* nawiazywanie polaczenia z baza danych */
    require_once"connect.php";                                  //wstawia tu kod z connect.php i sprawdza czy nie zostalo to zrobione wczesniej

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    /**/
?>

<!DOCTYPE html>

<html lang="en">

<head>
    

    <meta charset="utf-8" />

    <title>Crimson Gold</title>
    <meta name="description" content="web rpg in the world of Witcher" />
    <meta name="keywords" content="Witcher, rpg" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=UnifrakturMaguntia|Crimson+Text">

    <style> 
    <?php include "styles.css"    //to rozwiazuje problem z podlaczaniem pliku css, spartanskie ale dziala
    ?>
    </style>


</head>
<body>

<div id="battlefield">
    
        <div id="left-warrior">
            <?php echo $_SESSION['nickname'] ?><br>
            <img class="fighter" src = "<?php echo $_SESSION['avatar']?>"> 
        </div>
        <div id="right-warrior">
            <?php echo $_SESSION['monster_name'] ?><br>
            <img class="fighter" src = "<?php echo $_SESSION['monster_image']?>">
        </div>
    
    

    <div id="fight-progres">
        <?php       
            /*
            $_SESSION['nickname'] 
            $_SESSION['avatar'] 
            $_SESSION['class_type'] 
            $_SESSION['lvl'] 
            $_SESSION['prowess'] 
            $_SESSION['insight'] 
            $_SESSION['resolve'] 
            $_SESSION['HP'] 
            $_SESSION['gold'] 
            $_SESSION['weapon_lvl'] 
            $_SESSION['XP'] 
            */

            /*miejsce na myśli
            
            

            */

            /* ustawiamy ceche ktora walczy gracz */
            switch($_SESSION['class_type'] )
            {
                case 1:
                    $fighting_power = $_SESSION['prowess'];
                break;

                case 2:
                    $fighting_power = $_SESSION['insight'];
                break;

                case 3:
                    $fighting_power = $_SESSION['resolve'];
                break;
            }
            /**/

            /* zmienne uniwersalne */
            $attacker_name = $_SESSION['nickname'];
            $attacker_HP = $_SESSION['HP'];
            $attacker_power = $fighting_power;
            $attacker_dodge = $_SESSION['insight'];
            $attacker_dmg = $_SESSION['weapon_lvl'];

            $defender_name = $_SESSION['monster_name'];
            $defender_HP = $_SESSION['monster_HP'];
            $defender_power = $_SESSION['monster_power'];
            $defender_dodge = $_SESSION['monster_dodge'];
            $defender_dmg = $_SESSION['monster_damage'];

            $i = 1;             //licznik pentli, pozwoli ustalic kto wygral
            /**/

            /* walka */
            while($attacker_HP > 0)
            {
                

                /* atak */
                $K100 = random_int(1, 100);        //rzucamy koscia K100

                if($K100 <= $attacker_power)       //sukces
                {
                    $attac_sucess = 1;                  //normalny
                    if($K100 <= (($attacker_power)/2) )
                    {
                        $attac_sucess = 2;             //silny
                        if($K100 <= (($attacker_power)/4) )
                        {
                            $attac_sucess = 3;         //ekstremalny
                        }
                    }
                }                                       //mozesz to zoptymalizowac zaczynajac od ekstremalnego i robiac else if konczac na normalnym i porazce
                else
                {
                    $attac_sucess = 0;        //porazka
                }
                /**/

                /* obrona */
                $K100 = random_int(1, 100);        //rzucamy koscia K100
                
                if($K100 <= $defender_dodge)       //sukces
                {
                    $dodge_sucess = 1;                  //normalny
                    if($K100 <= (($defender_dodge)/2) )
                    {
                        $dodge_sucess = 2;             //silny
                        if($K100 <= (($defender_dodge)/4) )
                        {
                            $dodge_sucess = 3;         //ekstremalny
                        }
                    }
                }                                       //mozesz to zoptymalizowac zaczynajac od ekstremalnego i robiac else if konczac na normalnym i porazce
                else
                {
                    $dodge_sucess = 0;        //porazka
                }
                /**/

                /* porownanie */
                if($attac_sucess == 0)      
                {
                    //nie trafia bo miss
                    echo $attacker_name." missed ".$defender_name.".<br><br>";
                }
                else
                { 
                    if($attac_sucess <= $dodge_sucess)
                    {
                        //nie trafia bo unik
                        echo $defender_name." dodged ".$attacker_name." attack. <br><br>";
                    }
                    else
                    {
                        //trafia
                        

                        /* obrazenia */
                        $damage = random_int(1 + ($attacker_dmg*3), 14 + ($attacker_dmg*3));           //dmg obliczony z poziomu broni
                        $defender_HP = $defender_HP - $damage;            //pozostale HP
                        /**/

                        echo $attacker_name." reaches ".$defender_name." with their strike! <br>";
                        echo $attacker_name." deals ".$damage." points of damage. <br><br>";
                    }
                }
                
                /**/


                /* zamiana wartosci KONIEC TURY */
                swap($attacker_name, $defender_name);
                swap($attacker_HP, $defender_HP);
                swap($attacker_dmg, $defender_dmg);
                swap($attacker_power, $defender_power);
                swap($attacker_dodge, $defender_dodge);

                $i++;
                /**/
            }
            /**/
            
            
            $winner = $defender_name;       //jako ze na koncu pentli jest swap to defender jest tym ktory jeszcze stoi na nogach
            $id = $_SESSION['player_id'];           //musialo byc tak bo zmienne sesyjne nie wchodza do zapytan sql
            

            /* rozwiazanie walki i rozdzielenie xp i golda */

            if($winner == $_SESSION['nickname'])        //wygrywa gracz
            {
                echo "<br><br><span id='fightResult'>The ".$_SESSION['monster_name']." falls! ".$_SESSION['nickname']." takes its head and returns to collect their bounty.</span> <br>";

                $gold = ($_SESSION['resolve'] * $_SESSION['difficulty'])+($_SESSION['weapon_lvl']*10);     //nagroda za ubicie potwora
                $xp = 100 * $_SESSION['difficulty'];            //xp za ubicie potwora

                $connection->query("UPDATE players SET GOLD = GOLD+$gold WHERE players.player_id = $id");       //dodajemy zloto bo tylko tutaj dostajemy jakiekolwiek

                $_SESSION['gold'] = $_SESSION['gold'] + $gold;      //update zmiennej sesyjnej zeby byla taka sama jak w bazie danych

            }
            else            //wygrywa potwor
            {
                echo "<br><br><span id='fightResult'>The ".$_SESSION['monster_name']." will kill you if this continue! ".$_SESSION['nickname']." needs to reatret to survive.</span> <br>";

                $xp = 50;
            }
            /**/

            /* update zmiennych */
            $_SESSION['XP'] = $_SESSION['XP'] + $xp;
            //zloto jest w if powyzej bo nie zawsze sie je dostaje
            /**/

            /* modyfikacja zmiennych w bazie danych */
            $connection->query("UPDATE players SET XP = XP+$xp WHERE players.player_id = $id");             // dodajemy xp
            //zloto jest w if powyzej bo nie zawsze sie je dostaje
            /**/

            /* lvl up */
            if($_SESSION['XP'] >= 1000)
            {
                if($_SESSION['lvl'] < 10)       //limit lvlu, potem juz wyzej nie dostaniesz nic, musze gdzies dac koniec
                {
                    $_SESSION['lvl'] = $_SESSION['lvl'] + 1;            //zmieniamy zmienna na taka jak w bazie danych
                    $connection->query("UPDATE players SET lvl = lvl+1 WHERE players.player_id = $id");             // lvl-up

                    $_SESSION['skill_points'] = $_SESSION['skill_points'] + 3;      //zmieniamy zmienna na taka jak w bazie danych
                    $connection->query("UPDATE players SET skill_points = skill_points+3 WHERE players.player_id = $id");       //punkty do wydania w miescie

                    $_SESSION['XP'] = $_SESSION['XP'] - 1000;       //zmieniamy tak zeby bylo jak w bazie danych
                    $xp_lvlup = $_SESSION['XP'];
                    $connection->query("UPDATE players SET XP = $xp_lvlup WHERE players.player_id = $id");             // zerujemy xp

                    /* zwiekszenie cech po lvlup */
                    switch($_SESSION['class_type'] )
                    {                                               // w zaleznosci od szkoly wiedzminskiej
                        case 1:
                            $_SESSION['prowess'] = $_SESSION['prowess'] + 5;        //zmienne sesyjne
                            $_SESSION['insight'] = $_SESSION['insight'] + 1;
                            $_SESSION['resolve'] = $_SESSION['resolve'] + 1;
                            $_SESSION['HP'] = $_SESSION['prowess'];

                            $connection->query("UPDATE players SET PROWESS = PROWESS+5 WHERE players.player_id = $id");     //dane w bazie
                            $connection->query("UPDATE players SET INSIGHT = INSIGHT+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET RESOLVE = RESOLVE+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET HP = PROWESS WHERE players.player_id = $id");
                        break;

                        case 2:
                            $_SESSION['insight'] = $_SESSION['insight'] + 5;        //zmienne sesyjne
                            $_SESSION['prowess'] = $_SESSION['prowess'] + 1;
                            $_SESSION['resolve'] = $_SESSION['resolve'] + 1;
                            $_SESSION['HP'] = $_SESSION['prowess'];

                            $connection->query("UPDATE players SET INSIGHT = INSIGHT+5 WHERE players.player_id = $id");     //dane w bazie
                            $connection->query("UPDATE players SET PROWESS = PROWESS+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET RESOLVE = RESOLVE+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET HP = PROWESS WHERE players.player_id = $id");
                        break;

                        case 3:
                            $_SESSION['resolve'] = $_SESSION['resolve'] + 5;        //zmienne sesyjne
                            $_SESSION['insight'] = $_SESSION['insight'] + 1;
                            $_SESSION['prowess'] = $_SESSION['prowess'] + 1;
                            $_SESSION['HP'] = $_SESSION['prowess'];

                            $connection->query("UPDATE players SET RESOLVE = RESOLVE+5 WHERE players.player_id = $id");     //dane w bazie
                            $connection->query("UPDATE players SET INSIGHT = INSIGHT+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET PROWESS = PROWESS+1 WHERE players.player_id = $id");
                            $connection->query("UPDATE players SET HP = PROWESS WHERE players.player_id = $id");
                        break;
                    }
                    /**/
                }
                
            }
            /**/

            /* pobieranie nowych nazw misji, kopia kodu z zaluguj.php */
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
            
            
            unset($_SESSION['fight_token']);               //walka skonczona wiec nie dajmy mozliwosci powrotu



            $connection->close();       //trzeba zamknac polaczenie


            function swap(&$a, &$b)         //php jest proste, to powinno zamieniac dowolne zmienne (&=referencja)
            {
                $c = $a;
                $a = $b;
                $b = $c;
            }
        ?>
    </div>

    
    <div id="return">
        <br>
        <form action="gra.php">
            <input type="submit" value="Return" />
        </form>
    </div>
</div>

</body>

</html>