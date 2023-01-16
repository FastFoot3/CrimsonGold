<?php               //ten plik pobierze ciag znakow i wysle je do zaloguj.php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przeslanie gracza z powrotem do index.php jesli nie rejestrowal sie */
    if(! isset($_SESSION['register_token']))
    {
      header('Location: index.php');
      exit();                                 //dalsza generacja kodu jest niepotrzebna, wiec wychodzimy
    }
    else
    {
      unset($_SESSION['register_token']);     //rejestracja sie powiodla, musimy dac opcje zeby sie zarejestrowac znowu
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
    <div class="CG" style="font-size: 140px">Slay monsters and earn gold!</div>  

    <div id="registrationComplited">
        Registration complited
        <div id="loginBack">You can now <a href="index.php">Login</a></div>
    </div>
</body>


</html>


