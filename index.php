<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/styl.css">
    <title>Najlepsze newsy</title>
</head>
<body>


    <?php 
        session_start();

        @$view=$_GET["view"];

        if (empty($view))  {$view='log';};

        $polaczenie = mysqli_connect('localhost','root','','lubuskienews') or die("Błąd połaczenia");


        if(empty($_SESSION['login']) && $view=='log'){
                print('
                    <div class="log-main">
                        <div class="naglowek">
                            <div class="baner">
                                <img id="ob"src="./img/logo2.png">
                                <p id="tekst-ob">LUBUSKIE NEWS</p>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="logowanie">
                                <input id="log"type="text" name="login" placeholder="Podaj login">
                                <input id="pass"type="password" name="haslo" placeholder="Podaj haslo">
                                <input type="submit" value="Zaloguj" class="akceptuj" >
                                <input type="reset" value="Kasuj" class="kasuj"> 
                                <a href="?view=rejestracja">Rejestracja</a>
                            </div>   
                            
                        </form>  
                    
                    </div>

                    
                    
                ');   
                
                @$login = $_POST["login"];
                @$haslo = $_POST["haslo"]; 

                $zapytanie = "SELECT * FROM konta WHERE nazwa = '$login';"; 
                $wynik = mysqli_query($polaczenie,$zapytanie);
                $rekord=mysqli_fetch_array($wynik); 

                
                if($login==@$rekord[1] && md5($haslo)==@$rekord[2])
                {
                    
                    $_SESSION['login'] = $login;
                    $_SESSION['zdj'] = $rekord[3];
                    $_SESSION['perm'] = $rekord[4];
                    $view = 'main';
                    $_SESSION['zalogowany'] = 1;
                    $admin = $rekord[4];
                    header('Location: ./index.php?view=main');

                }


            }else if(empty($_SESSION['login']) && $view=='rejestracja'){
                print('
                    


                <div class="log-main">
                <div class="naglowek">
                    <div class="baner">
                        <img id="ob"src="./img/logo2.png">
                        <p id="tekst-ob">LUBUSKIE NEWS</p>
                    </div>
                </div>
                
                <form method="POST">
                    <div class="logowanie">
                        <input id="log"type="text" name="login" placeholder="Podaj login">
                        <input id="pass"type="password" name="haslo" placeholder="Podaj haslo">
                        <input id="pass"type="password" name="haslo2" placeholder="Podaj haslo">
                        <input id="log" type="text" name="zdjecie" placeholder="Zdjecie(opcjonalne)">
                        <input type="submit" value="Rejestruj" class="akceptuj" >
                        <input type="reset" value="Kasuj" class="kasuj"> 
                        <a href="?view=log">Zaloguj</a>
                    </div>   
                    
                </form>   
            
            </div>
                
                
            ');   
            
            @$login = $_POST["login"];
            @$haslo = $_POST["haslo"]; 
            @$haslo2 = $_POST["haslo2"]; 
            @$zdj = $_POST["zdjecie"]; 

            $zapytanie = "SELECT * FROM konta;"; 
            $wynik = mysqli_query($polaczenie,$zapytanie);
            while($rekord=mysqli_fetch_array($wynik)){
                if($rekord[1] == $login){
                    echo '<script type="text/javascript">
                    window.onload = function () { alert("Login zajęty."); } 
                    </script>'; 

                }
            }

            if($haslo != $haslo2){
                echo '<script type="text/javascript">
                        window.onload = function () { alert("Złe lub puste hasło - dwa podane hasła powinny być identyczne"); } 
                    </script>'; 

            }
            if($haslo < 5 || $haslo2 < 5){
                echo '<script type="text/javascript">
                        window.onload = function () { alert("Hasło za krotkie, min. 5 znaków"); } 
                    </script>'; 

            }
            
            if($haslo == $haslo2 && !empty($haslo) && !empty($login)){

                if(!empty($zdj)) $zapytanie = "INSERT INTO konta (nazwa, haslo, zdjecie) VALUES ('$login', MD5('$haslo'), '$zdj');"; 
                if(empty($zdj)) $zapytanie = "INSERT INTO konta (nazwa, haslo) VALUES ('$login', MD5('$haslo'));"; 

                $wynik = mysqli_query($polaczenie,$zapytanie);

                $_SESSION['login'] = $login;
                $_SESSION['zdj'] = $zdj;
                $view = 'main';
                $_SESSION['perm'] = 0;
                $_SESSION['zalogowany'] = 1;
                $admin = $rekord[4];
                header('Location: ./index.php?view=main');

            }
            

            
            


            
            }
            else{
                if($view != 'log' && $view!='rejestracja'){
            
                    print('   <div class="naglowek">
                                <div class="baner">
                                    <img id="ob"src="./img/logo2.png">
                                    <p id="tekst-ob">LUBUSKIE NEWS</p>
                                </div>
                        
                                
                        
                                <div class="naw">
                                    <a href="?view=main" class="naw-tekst">Strona Główna</a>
                                    <a href="?view=kontakt" class="naw-tekst">Kontakt</a>
                                    <a href="?view=addwpis" class="naw-tekst">Dodaj wpis</a>
                                    <a href="?view=konto" class="naw-tekst">Konto</a>
                                </div>
                        
                            </div>
                ');
                
                }
        
                if($view=='konto'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];
                    $zapytanie = "SELECT * FROM konta WHERE nazwa = '$login';"; 
                    $wynik = mysqli_query($polaczenie,$zapytanie);
                    $rekord=mysqli_fetch_array($wynik); 
                    print("<div class='main'>

                            <div class='pomoc'>
                                <div class='informacje-konta'>
                                    <h3 style='margin-bottom: 100px'>Jesteś zalogowany</h3>
                                    <p>Informacje o koncie: </p>
                                    <p>Nazwa: $rekord[1]</p>
                                    <p>Profilowe: <img src='./img/$rekord[3]' class='profilowe'></p>
                                    <p>Permisje(0/1): $rekord[4]</p>
                                    <br>
                                    <a class='przycisk'href='wyloguj.php'>Wyloguj</a>
                                </div>
                             </div>

                        </div>"
                    );
        
        
        
                }else if($view == 'konto'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }
        
        
                if($view == 'main'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];
                    print (' 
        
                            <div class="main">
                                <div class="naw2">
                                    <a href="?view=main" class="naw2-tekst">Strona Główna</a>
                                    <a href="?view=kontakt" class="naw2-tekst">Kontakt</a>
                                    <a href="?view=addwpis" class="naw2-tekst">Dodaj Wpis</a>
                                    <a href="?view=konto" class="naw2-tekst">Konto</a>
                                </div>
        
                                <div class="wpisy">
        
                            ');
        
                    $zapytanie = "SELECT * FROM wpisy WHERE 1 ORDER BY id DESC;;";
        
                    $wynik = mysqli_query($polaczenie, $zapytanie);
        
                    while($rekord = mysqli_fetch_array($wynik)){
                        print("
        
                            <a href='?view=shownews&id=$rekord[0]'>
                                <div class='tekst'>
                                    <h2>$rekord[2]</h2>    
                                    <sup>by $rekord[1]</sup>
        
                                    <br>
                                    $rekord[3]
                            ");

                                    if($permisje==1 || $login == $rekord[1]){
                                        print("<div class='edit-box'>
                                                    <a href='?view=edit&id=$rekord[0]' class='link-button' >Edytuj
                                                    </a>
                                                </div>
                                            </a>
                                            ");
                            
                                    }
                        print("</div>  
                        </a> ");           
        
                    }               
                    
                    print("</div>");                  
        
                }else if($view == 'main'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }
                
                
                if($view == 'shownews'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];
        
                    @$id=$_GET["id"];
                    $zapytanie = "SELECT * FROM wpisy WHERE id = $id;";
                    $wynik = mysqli_query($polaczenie, $zapytanie);
        
                    $rekord = mysqli_fetch_array($wynik);
                    print("
        
                        <div class='main'>
                            <div class='wpis'>
                                <img class='profilowe' src='./img/$rekord[4]'><p class='nagl'>Wpis Użykownika $rekord[1]</p>
                            
                                <p class='wpis-tytul'>Tytuł: $rekord[2]</p>
                                <br>
                                <p class='wpis-tekst'>$rekord[3]</p>
                            </div>
                    ");
        
                    print("
                    <div class='komentarze'>
                        <h3 class='nagl'>Napisz Komentarz</h3>
                        <form method='POST'>

                                <textarea name='koment' placeholder='Dodaj Komentarz'></textarea>
                                <input class='przycisk' type='submit' value='Opublikuj'>
                        </form>
                    ");
        
                    @$komentarz=$_POST["koment"];
                    if(!empty($komentarz)){
                        $zapytanie = "INSERT INTO komentarze (wpis_id, nazwa, komentarz, zdjecie) VALUES ($id, '$login', '$komentarz', '$zdjecie_path')";
                        $wynik = mysqli_query($polaczenie, $zapytanie);
                    }
        
                    $zapytanie = "SELECT * FROM komentarze WHERE wpis_id = $id ORDER BY id DESC;";
                    $wynik = mysqli_query($polaczenie, $zapytanie);
        
                    while($rekord = mysqli_fetch_array($wynik)){
                        print("<div class='komentarz'>");
                        print("<img class='profilowe' src='./img/$rekord[4]'>");
                        print("<p>$rekord[2]</p>");
                        print("<p>$rekord[3]</p>");
                        if($permisje==1 || $login == $rekord[2]){
                            print("<form method='POST'>
                                    <div class='delete-box'>
                                        <img class='smietnik' src='smiec2.jpg'>
                                        <input class='delete' type='submit' value='$rekord[0]' name=komentid >
                                        
                                    </div>
                                </form>"
                                );
        
                        }
        
                        print("</div>");
        
                    }
                    @$id=$_POST["komentid"];
                    if(!empty($id) || ($permisje==1 && !empty($id))){

                        $zapytanie = "DELETE FROM komentarze WHERE id = $id;";
                        
                        mysqli_query($polaczenie, $zapytanie);
                        header("Refresh:0");
                    }
        
        
                    print("
                            </div>
                            
                        </div>
            
                 ");
        
                
        
                }else if($view == 'shownews'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }




                if($view=='edit'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];
                    @$id=$_GET["id"];
                    $zapytanie = "SELECT * FROM wpisy WHERE id = '$id';"; 

                    $wynik = mysqli_query($polaczenie,$zapytanie);
                    $rekord=mysqli_fetch_array($wynik); 
                    print("<div class='main'>
                            <div class='edycja'>
                                <form method='POST'>
                                    <h4>EDYTOWANIE WPISOW</h4>
                                    <br>
                                    Tytuł wpisu: 
                                    <br>
                                    <textarea class='edycja-text' name='edit-tytul'>$rekord[2]</textarea>
                                    <br>
                                    Treść wpisu:
                                    <br>
                                    <textarea class='edycja-text' name='edit-tekst''>$rekord[3]</textarea>
                                    <br>
                                    <label for='usun'>Usunąć wpis: </label>
                                    <input type='checkbox'name='usun' value='usun'/>
                                    <br>
                                    <input type='submit'>
                                    <input type='reset'>
                                    
                                <form>

                            </div>

                        </div>"
                    );

                    @$edit_tytul=$_POST["edit-tytul"];
                    @$edit_tekst=$_POST["edit-tekst"];
                    @$usuwac = $_POST["usun"];
                    if(($edit_tytul != $rekord[2] || $edit_tekst!=$rekord[3]) && !empty($edit_tytul) && !empty($edit_tekst)){
                        $zapytanie = "UPDATE wpisy SET tytul = '$edit_tytul', tekst = '$edit_tekst' WHERE id = $id";  
                        mysqli_query($polaczenie, $zapytanie);
                        header("Location: index.php?view=main");
                    }else if($usuwac=='usun'){
                        $zapytanie = "DELETE FROM wpisy WHERE id = $id";

                        mysqli_query($polaczenie, $zapytanie);
                        $zapytanie = "DELETE FROM komentarze WHERE wpis_id = $id";

                        mysqli_query($polaczenie, $zapytanie);

                        header("Location: index.php?view=main");
                    }
        
        
                }else if($view == 'edit'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }


                if($view=='addwpis'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];
                    
                    
                    $zapytanie = "SELECT * FROM konta WHERE nazwa = '$login';";
                    $wynik = mysqli_query($polaczenie, $zapytanie);
        
                    $rekord = mysqli_fetch_array($wynik);


                    print("<div class='main'>
                            <div class='edycja'>
                                <form method='POST'>
                                    <h4>DODAWANIE WPISOW</h4>
                                    <br>
                                    Tytuł wpisu: 
                                    <br>
                                    <textarea class='edycja-text' placeholder='Wpisz tytul' name='edit-tytul'></textarea>
                                    <br>
                                    Treść wpisu:
                                    <br>
                                    <textarea class='edycja-text' placeholder='Wpisz tytul' name='edit-tekst''></textarea>
                                    <br>
                                    <input type='submit'>
                                    <input type='reset'>
                                    
                                <form>

                            </div>

                        </div>"
                    );

                    @$tytul=$_POST["edit-tytul"];
                    @$tekst=$_POST["edit-tekst"];
                    
                    if(!empty($tytul) && !empty($tekst)){
                        $zapytanie = "INSERT INTO wpisy (nazwa,tytul,tekst,zdjecie) VALUES('$login', '$tytul', '$tekst', '$rekord[3]')";
                        mysqli_query($polaczenie, $zapytanie);
                        header("Location: index.php?view=main");
                    }

        
        
                }else if($view == 'edit'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }

                
                if($view=='kontakt'&&!empty($_SESSION['login'])){
                    $login = $_SESSION['login'];
                    $zdjecie_path = $_SESSION['zdj'];
                    $permisje = $_SESSION['perm'];

                    $zapytanie = "SELECT * FROM konta WHERE nazwa = '$login';"; 
                    $wynik = mysqli_query($polaczenie,$zapytanie);
                    $rekord=mysqli_fetch_array($wynik); 

                    print("<div class='main'>

                            <div class='pomoc'>
                                <div class='informacje-konta'>
                                    <h4 style='padding-bottom: 40px'>DANE KONTAKTOWE</h4>
                                    <p>Adres mailowy: stepaxszymon@gmail.com</p>
                                    <p>Numer Telefonu: +48 533463968</p>
                                    <p>Adres: pod blokiem</p>

                                </div>
                             </div>

                        </div>"
                    );
        
        
                }else if($view == 'kontakt'&&empty($_SESSION['login'])){
                    header("Location: index.php");
                }


            }           


        mysqli_close($polaczenie);
    ?>

    <div class="stopka">
                                    
    </div>
    
</body>
</html>