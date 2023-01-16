<?php               //ten plik pobierze ciag znakow i wysle je do zaloguj.php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    if(isset($_POST['login']))        //sprawdzamy czy formularz zostal wyslany
    {
      $all_correct = true;          //ustawienie falgi poprawnosci

      /* sprawdzanie poprawnosci nicka */
      $nickname = $_POST['nick'];

      if((strlen($nickname) < 3) || (strlen($nickname) > 21)) //sprawdzenie dlugosci nicka
      {
        $all_correct = false;
        $_SESSION['nickname_error']="Nickname must be at least 3 letters long and no longer than 20 letters";

      }

      if(ctype_alnum($nickname) == false)         //sprawdzanie znakow nicka
      {
        $all_correct = false;
        $_SESSION['nickname_error'] = "Nickname must consist only letters and numbers";
      }
      /**/

      /* sprawdzanie poprawnosci loginu */
      $login = $_POST['login'];

      if((strlen($login) < 6) || (strlen($login)) > 51) //sprawdzenie dlugosci loginu
      {
        $all_correct = false;
        $_SESSION['login_error']="Login must be at least 6 characters long and no longer than 50 letters";

      }

      if(ctype_alnum($login) == false)         //sprawdzanie znakow loginu
      {
        $all_correct = false;
        $_SESSION['login_error'] = "Login must consist only letters and numbers";
      }
      /**/

      /* sprawdzanie poprawnosci hasla */
      $password1 = $_POST['password1'];
      $password2 = $_POST['password2'];

      if((strlen($password1) < 6) || (strlen($password1)) > 51) //sprawdzenie dlugosci hasla
      {
        $all_correct = false;
        $_SESSION['password_error']="Password must be at least 6 characters long and no longer than 50 letters";

      }

      if($password1 != $password2)      //sprawdzanie czy hasla sa identyczne
      {
        $all_correct = false;
        $_SESSION['password_error']="Passwords are not the same";
      }

      $password_hash = password_hash($password1, PASSWORD_DEFAULT);     //zakrywa haslo w bazie, przy okazji nie trzeba sprawdzac zawartosci

      /**/

      /* sprawdzenie powielenia danych */
      require_once "connect.php";       //zaczynamy polaczenie
      $connection = new mysqli($host, $db_user, $db_password, $db_name);

      $result = $connection->query("SELECT player_id FROM players WHERE login='$login'"); //pobieramy id gracza o podanym loginie

      if($result->num_rows > 0) //sprawdzamy ilosc rezultatow, powinno byc 0
      {
        $all_correct = false;
        $_SESSION['login_error']="This login already exists";
      }
      /**/

      /* wprowadzenie reszty danych do zmiennych */
      $avatar = $_POST['avatar'];
      $class = intval($_POST['class']);
      
      switch($class)
      {
        case 1:
          $prowess = 50;
          $insight = 25;
          $resolve = 25;
        break;
        case 2:
          $prowess = 25;
          $insight = 50;
          $resolve = 25;
        break;
        case 3:
          $prowess = 25;
          $insight = 25;
          $resolve = 50;
        break;
      }
      $HP = $prowess;
      $gold = $resolve;
      /**/
      
      /* wpisywanie do bazy */
      if($all_correct == true)    //konczonce sprawdzenie czy wszystko jest dobrze wypelnione
      {                                                   //player_id, login, password, nickname, avatar, class_type, lvl, PROWESS, INSIGHT, RESOLVE, HP, GOLD, WEAPON_LVL, XP, skill_points
        if($connection->query("INSERT INTO players VALUES(NULL,'$login','$password_hash','$nickname','$avatar',$class,1,$prowess,$insight,$resolve,$HP,$gold,0,0,0)"))
        {
          $_SESSION['register_token'] = true;    //flaga oznaczajaca ze sie udalo zarejestrowac
          header('Location: witamy.php');
        }
      }
      /**/

      $connection->close();       //trzeba zamknac polaczenie
    }
?>
<!DOCTYPE html>

<html lang="en">

  <head>
    

    <meta charset="utf-8" />

    <title>Crimson Gold - Login</title>
    <meta name="description" content="web rpg in the world of Witcher" />
    <meta name="keywords" content="Witcher, rpg" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;700&family=UnifrakturMaguntia&display=swap" rel="stylesheet">
  
    <style>
      <?php include "styles.css"    //to rozwiazuje problem z podlaczaniem pliku css, spartanskie ale dziala
      ?>
    </style>

  </head>

  <body>
    <div class="CG" style="font-size: 140px">Crimson Gold</div> 
    
    <form method = "post">
      <div id="registerForm">
        
          <div class="login">
            Login: <br /><input type="text" name="login" /><br />
          </div>

          <?php
            /* wyswietlenie komunikatu o bledzie loginu */
            if(isset($_SESSION['login_error']))
            {
              echo '<div class="login_error" style="color: red">'.$_SESSION['login_error'].'</div>';
              unset($_SESSION['login_error']);     //trzeba usunac zeby przy nastepnym przeslaniu znowu sprawdzic
            }
            /**/
          ?>

          <div class="password">
            Password: <br /><input type="password" name="password1" /><br />
          </div>

          <div class="password">
            Repeat password: <br /><input type="password" name="password2" /><br />
          </div>

          <?php
            /* wyswietlenie komunikatu o bledzie hasla */
            if(isset($_SESSION['password_error']))
            {
              echo '<div class="password_error" style="color: red">'.$_SESSION['password_error'].'</div>';
              unset($_SESSION['password_error']);     //trzeba usunac zeby przy nastepnym przeslaniu znowu sprawdzic
            }
            /**/
          ?>

          
          <div class="password">
            Nickname: <br /><input type="text" name="nick" /><br />
          </div>
          <?php
            /* wyswietlenie komunikatu o bledzie nicku */
            if(isset($_SESSION['nickname_error']))
            {
              echo '<div class="nickname_error" style="color: red">'.$_SESSION['nickname_error'].'</div>';
              unset($_SESSION['nickname_error']);     //trzeba usunac zeby przy nastepnym przeslaniu znowu sprawdzic
            }
            /**/
          ?>
      </div>
      
      <div id="witcherSchools">
        <p>Choose your witcher school:</p>
        <br>
        <div class="schoolsChoice">
          <input type="radio" id="prowess" name="class" value="1" checked/>
          <label for="prowess"> 
            <span class="schoolName">School of the Bear</span> <br/>
            <br>
            Its training focuse mainly on phisical strength and endurance, 
            making its witchers into a tough beasts, going toe to toe with monsters, 
            being able to withstand even the most serious injuries and stand with a sword in hand to the last drop of blood.<br> 
            Witcher's PROWESS will define your character making their Health Points pool higher.<br>
          <br>
          </label>
        </div>

        <div class="schoolsChoice">
          <input type="radio" id="insight" name="class" value="2" />
          <label for="insight"> 
            <span class="schoolName">School of the Rat</span> <br/>
            <br>
            Quick reactions, inner instinct and agility are what distinguish Rat's witchers, 
            their speed makes them untouchable in battles, dodging and stepping out of the way of danger 
            and their perception makes them into excellent hunters.<br> 
            Witcher's INSIGHT will define your character, making their Dodge Chance higher.<br>
          <br>
          </label>
        </div>

        <div class="schoolsChoice">
          <input type="radio" id="resolve" name="class" value="3" />
          <label for="resolve"> 
            <span class="schoolName">School of the Griffin</span> <br />
            <br>  
            "Pen is mightier than sword" couldn't be further from truth in this world, 
            but the truth is that being able to write does tend to make your pockets heavier and by that make your swords sharper, 
            this idea is the main premise of Griffin's Witchers, they power comes from overall knowledge, 
            but mainly focusing on sign magic and social tactics.<br> 
            Witchers RESOLVE will define your character, making them earn more Gold And get better equipment faster, meaning your Damage will be higher.
          </label>
        </div>
        <div style="clear:both"></div>

      </div>

      <div id="avatarsRegister">
        <p>Choose your avatar:</p>
        <div class="avatarChoice">
          <input type="radio" id="1" name="avatar" value="obrazy/witcher1.jpg" checked/><br>
          <label for="1"> <img class = "avatarRegisterImg" src = "obrazy/witcher1.jpg"> </label>
        </div>

        <div class="avatarChoice">
          <input type="radio" id="2" name="avatar" value="obrazy/witcher2.jpg" /><br>
          <label for="2"> <img class = "avatarRegisterImg" src = "obrazy/witcher2.jpg"> </label>
        </div>

        <div class="avatarChoice">
          <input type="radio" id="3" name="avatar" value="obrazy/witcher3.jpg" /><br>
          <label for="3"> <img class = "avatarRegisterImg" src = "obrazy/witcher3.jpg"> </label>
        </div>

        <div style="clear:both"></div>

      </div>
      
      <div class="loginSubmit">
        <input type="submit" value="sign in" />
      </div>

    </form>
  </body>


</html>

