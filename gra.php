<?php           //ten plik bedzie glowna strona gry do ktorej przekieruje gracza zaloguj.php

    /* otwarcie sesji (globalnej tablicy asocjacyjnej), musi to miec kazdy plik ktory chce
    korzystac z tych globalnych zmiennych, jest to polaczenie ukryte przed uzytkownikiem, 
    przesylanie zmiennych pomiedzy plikami .php */
    session_start();
    /**/

    /* przekierowania gracza do index.php jesli znalazl sie w gra.php bez uprzedniego logowania */
    if(!isset($_SESSION['login_token']))
    {
      header('Location: index.php');
      exit();                       //zmienne nie istnieja bez logowania, bez sensu ladowac
    }
    /**/
?>

<!DOCTYPE html>

<html lang="en">

  <head>
      

    <meta charset="utf-8" />

    <title>Crimson Gold</title>
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

    <a class="logout" href="logout.php"> 
      logout
      <?php //te przekierowanie wyloguje gracza i przeniesie do index.php ?>
    </a>

    <div class = CG>
      Crimson Gold
    </div>

    <div style="float:left">
        <?php
          echo '<img class="avatar" src="'.$_SESSION['avatar'].'" width="207"/>'

        ?>
    </div>

    <div style="
      margin-top: 15px;
      margin-bottom: 0px;
      display: flex;
      flex-direction: row;
    ">
      
      <div style= "
        background-color: rgb(88, 149, 169, 0);
        width: 10px;
      "></div> 
      <div class ="Witchers">
      
            
         Witchers name: <br>
        <?php
        echo $_SESSION['nickname'];
        ?> 
      </DIV>
      <div style= "
        background-color: rgb(88, 149, 169, 0);
        flex: 6;
      "></div>   

                <div>
                  <div class="INSIGHT">
                    INSIGHT <br>
                    
                    <?php
                      echo $_SESSION['insight'];
                     ?>

                  </div>
                </div>

      <div style= "
        background-color: rgb(88, 149, 169, 0);
        width: 70px;
      "></div> 

                <div>
                  <div class="RESOLVE">
                    RESOLVE <br>
                    
                    <?php
                     echo $_SESSION['resolve'];
                     ?>

                  </div>
                </div>

      <div style= "
        background-color: rgb(88, 149, 169, 0);
        width: 70px;
      "></div> 

                <div>
                  <div class="PROWESS">
                    PROWESS <br>
                    <?php
                    echo $_SESSION['prowess'];
                     ?>

                  </div>
                </div>

      <div style= "
        background-color: rgb(88, 149, 169, 0);
        width: 70px;
      "></div> 
      
    </div>

   

    <div style="
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    ">
      <div class = "left_panel"></div>
      
        
        <div class ="front">
        <a href="town.php">
        Town
          <img class = "town" src = "obrazy/town.png">
        </a>
        </div>
        </a>

      
        <div class ="front">
        <a href="forest.php">
        Forest
        <img class = "town" src = "obrazy/forest.png">
        </a>
        </div>

      <div>
            <div class = "Helth_Points" >
              Helth Points:<br>
              
              <?php
                echo $_SESSION['HP'];
                ?>

            </div><br>
            <div class = "Gold" >
              Gold:<br>
              
              <?php
                echo $_SESSION['gold'];
                ?>

            </div><br>
            <div class = "Level" >
              Level:<br>
              
              <?php
                 echo $_SESSION['lvl'];
                ?>

            </div><br>
            <div class = "Experience" >
              Experience:<br>
              
              <?php
                 echo $_SESSION['XP'];
                ?>

            </div>
      </div>
    </div>
    

  </body>




</html>