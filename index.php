<?php               //ten plik pobierze ciag znakow i wysle je do zaloguj.php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przeslanie gracza z powrotem do gra.php jesli juz jest zalogowany */
    if(isset($_SESSION['login_token'])&&($_SESSION['login_token']=true))
    {
      header('Location: gra.php');
      exit();                                 //dalsza generacja kodu jest niepotrzebna, wiec wychodzimy
    }
    /**/
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

    <div id="loginForm">
      <form action="zaloguj.php" method="post">

        <?php //ten formularz wysyla ciag znakow metoda post ?>

        <div class="login">
          Login: <br><input type="text" name="login"/> 
        </div>
        <div class="password">
        Password: <br><input type="password" name="password"/> 
        </div>
        <div class="loginSubmit">
          <input type="submit" value="Login"> <br/>
        </div>

      </form>
      <br>
      <?php

        /* wyswietlenie powiadomienia o niepoprawnym loginie lub hasle */
        if(isset($_SESSION['login_fail']))        //sprawdza czy ta zmienna juz istnieje
        {
          echo $_SESSION['login_fail'];           //zmienna z zaloguj.php ustawiana gdy nie udalo sie zalogowac
        }
        /**/
        
      ?>

      <br /> <br />
      <div id="register">Need an acount? <a href = "rejestracja.php"> Sign in for free! <a/></div>

      
    </div>
  </body>


</html>

