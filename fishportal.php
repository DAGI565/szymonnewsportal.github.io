<html>
  <head>
        <title>Fish Portal</title>
        <link rel="stylesheet" href="./css/styl.css">
  </head>
 
  <body>

            <?php
               //--------- pobranie widoku z adresu strony 
               @$view=$_GET["view"];
               if (empty($view))  $view='log';
               $polaczenie = mysqli_connect('localhost','root','','samochody') or die("Błąd połaczenia");
 
               if ($view=='log')
               {
                    print("formularz logowania");
                    print'
                       <form method="POST" action="index.php?view=main"   >
                            <input type="text" name="login" placeholder="Podaj login">
                            <input type="password" name="haslo" placeholder="Podaj haslo">
                            <input type="submit" value="Zaloguj">
                            <input type="reset" value="Kasuj">                    
                       </form>                       
                    ';   
                    $view='main'; 
               }
               else
               {
                   print("sprawdzić logowanie i ew dać dopstęp");
                   @$login = $_POST["login"];
                   @$haslo = $_POST["haslo"]; 
                   $zapytanie = "SELECT * FROM uzytkownicy WHERE login = '$login';"; 
                   $wynik = mysqli_query($polaczenie,$zapytanie);
                   $rekord=mysqli_fetch_array($wynik); 
                  if($login==$rekord[1] && md5($haslo)==$rekord[2])
                  {
                    print("ok");
                  } 
                  else
                  {
                    print("niok");
                  }
               }







/*


               if ($view=='killfish')
               {
                  print("Kiluje fisz");
                  $id = $_GET["id"];
                  $zapytanie = "DELETE FROM ryby WHERE id = $id;";   
                 // mysqli_query($polaczenie,$zapytanie);
                  $view='main';

                 print'
                    <script>
                        document.write("Nasz pierwszy skrypt!");
                    </script>
                ';


                   
               }






                // ----------------   widok główny
                if ($view=='main')
                {
                    // - widok główny
                    print("<h1>Mega fish Portal 2.0</h1>");
                    print'<div id="szukaj">';
                    print' <form method="POST">';
                    print' Podaj nazwę ryby:  <input type="text"  name="pole_szukaj">';
                    print' <input type="submit" value="Szukaj">';
                    print' <button onclick="javascript:location.reload()">Czyść</button>';
                    print' <a href="?view=addfish">Dodaj</a>';
                    print' </form>';
                    print'</div>';
                    
                    @$szukaj=$_POST["pole_szukaj"];                    
                    if (empty($szukaj))
                    {
                        $zapytanie = "SELECT * FROM ryby;";   
                    }
                    else
                    {
                        $zapytanie = "SELECT * FROM ryby WHERE nazwa = '$szukaj';"; 
                    }                
                   
                    $wynik = mysqli_query($polaczenie,$zapytanie);
                    while($rekord=mysqli_fetch_array($wynik))
                    {
                        print('<div class="blok">');
                        print("<h2>$rekord[0]</h2>");
                        print('<img src="./img/'.$rekord[4].'"> '); 
                        print("<p>$rekord[1]</p> ");
                        print("<p>$rekord[2]</p>");
                        print('<p><a href="?view=editfish&id='.$rekord[0].'">Edycja</a> 
                                       | <a href="?view=killfish&id='.$rekord[0].'">Usuń</a></p>');
                        print("</div>");   
                    }    
                  

                }

                if ($view=='addfish')
                {
                    // - widok dodawania
                    
                    @$nazwa = $_POST["nazwa"];
                    @$wystepowanie = $_POST["wystepowanie"];
                    @$styl = $_POST["styl"];
                    @$plik =  $_POST["plik"];

                    ///------------------  zapisywanie do bazy danych
                    if(!empty($_POST["nazwa"]) && !empty($_POST["wystepowanie"]) 
                                     && !empty($_POST["styl"]) && !empty($_POST["plik"])) 
                    {
                            //----------- zapisujemy do bazy
                            $zapytanie="INSERT INTO ryby (id, nazwa, wystepowanie, styl_zycia, foto) 
                                        VALUES (NULL, '$nazwa', '$wystepowanie', '$styl', '$plik');";
                            $wynik = mysqli_query($polaczenie,$zapytanie);
                            if ($wynik == 1) print("Zapisałem do bazy: $wynik");
                    }   

                    
                    
                    print'
                    <h1>Dodaj nową rybę</h1>
                        <form method="POST">
                            <label>Podaj nazwę ryby:</label>
                            <input type="text" name="nazwa" placeholder="Podaj nazwę ryby">
                            <br>
                            <label>Wybierz występowanie ryby:</label>
                            <select name="wystepowanie">
                                <option value="jeziora">jeziora</option>
                                <option value="rzeki">rzeki</option>
                                <option value="stawy">stawy</option>
                                <option value="oceany">oceany</option>
                                <option value="morza">morza</option>
                            </select>
                            <br>
                            <label>Wybierz styl życia:</label>
                            <select name="styl">
                                <option value="1">Drapieżne</option>
                                <option value="2">Łagodne</option>
                                <option value="3">Nie mam zdania</option>
                            </select>
                            <br>
                            <label>Podaj nazwę pliku:</label>
                            <input type="text" name="plik" placeholder="Podaj nazwę pliku">
                            <br>
                            <input type="submit" value="Zapisz">
                        </form>
                    ';
                    



                    print('<a href="?view=main">Powrót</a>');
                    
                }




                if ($view=='editfish')
                {
                    // - widok dodawania
                    
                    @$nazwa = $_POST["nazwa"];
                    @$wystepowanie = $_POST["wystepowanie"];
                    @$styl = $_POST["styl"];
                    @$plik =  $_POST["plik"];
                    @$id = $_GET["id"]; 

                    if(!empty($nazwa) && !empty($wystepowanie) && !empty($styl) && !empty($plik))
                    {

                        $zapytanie = "UPDATE ryby SET nazwa = '$nazwa', 
                        wystepowanie = '$wystepowanie', styl_zycia = '$styl', 
                        foto = '$plik' WHERE id = $id;";
                        
                        mysqli_query($polaczenie,$zapytanie);    


                    }    



                       
                    $zapytanie = "SELECT * FROM ryby WHERE id=$id;";   
                    $wynik = mysqli_query($polaczenie,$zapytanie);
                    $rekord=mysqli_fetch_array($wynik);
                    
                    
                    print'
                    <h1>Popraw dane ryby</h1>
                        <form method="POST">
                            <label>Podaj nazwę ryby:</label>
                            <input type="text" name="nazwa" value="'.$rekord[1].'" placeholder="Podaj nazwę ryby">
                            <br>
                            <label>Wybierz występowanie ryby:</label>
                            <select name="wystepowanie">
                                <option value="'.$rekord[2].'">'.$rekord[2].'</option>
                                <option value="jeziora">jeziora</option>
                                <option value="rzeki">rzeki</option>
                                <option value="stawy">stawy</option>
                                <option value="oceany">oceany</option>
                                <option value="morza">morza</option>
                            </select>
                            <br>
                            <label>Wybierz styl życia:</label>
                            <select name="styl">';

                                if($rekord[3]==1) print'<option value="1">Drapieżne</option>';
                                if($rekord[3]==2) print'<option value="2">Łagodne</option>';
                                if($rekord[3]==3) print'<option value="3">Nie mam zdania</option>';
                                print'
                                <option value="1">Drapieżne</option>
                                <option value="2">Łagodne</option>
                                <option value="3">Nie mam zdania</option>
                            </select>
                            <br>
                            <label>Podaj nazwę pliku:</label>
                            <input type="text" name="plik" value="'.$rekord[4].'" placeholder="Podaj nazwę pliku">
                            <br>
                            <input type="submit" value="Zapisz">
                        </form>
                    ';
                    



                    print('<a href="?view=main">Powrót</a>');
                    
                }
*/



                mysqli_close($polaczenie);
            ?>
    </body>
</html>