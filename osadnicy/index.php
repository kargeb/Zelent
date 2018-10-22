<!--

LOGOWANIE

Maksymalny czas bezczynnosci uzytkownika bez wylogowania autmatycznego 
ustwaiamy w php.ini na serwerze w opcji
session.gc_maxlifetime=1440  // zwykle ustawione na tyle sekund wlasnie

typ hasla jako "password" !

jak dziala logowanie 10:00 MINUTA 

od razu trzeba sobie powiedziec przed czym trzeba sie zabezpieczyc:
        -np jak po zalgowaniu sie, uzytkownic wpisze w pasku adresu strone logowania
            no to ona nie moze mu sie wyswietlic - trzeba wtedy przekierowac na plik gry
        -no i gdyby ktos bez logowania wpisal wdres gra.php - tez trzeba zabezpieczyc przed takim czyms
            trzeba przekierowac na strone logowania

        "W KAŻDYM pliku php który ma być dostępny jedynie dla zalogowanych, umiescimy na poczatku ifa sprawdzajacego
        czy faktycznie ktos jest zalogowany a jesli nie to przekierowujemy go do index.php"
        "w index.php dokladnie odwrotnie, czyli sprawdadzmy czy ktos juz przypadkiem nie jest zalogowany na stronie,
        wtedy przekierowujemy go na gra.php. Czyli gra.php staje sie NOWA STRONA GLOWNA dla kogos zalogowanego"    

        - no i jeszcze sytuacja gdy ktos wejdzie do pliku zaloguj.php prosto z przegladarki,
        czyli bez wartosci loginu i hasla - wyjebie wtedy blad krytyczny - tutaj tez trzeba sparwdzic 
        czy te zmienne w goole istnieja jako POST



 -->
<?php
session_start();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Osadnicy - gra</title>
</head>
<body>

Tylko martwi ujrzeli koniec wojny - Platon <br> <br>

<form action="zaloguj.php" method="post" >

    Login: <br>
    <input type="text" name="login"> <br>
    Hasło: <br>
    <input type="password" name="haslo"> <br> <br>
    <input type="submit" value="Zaloguj się">

</form>

<?php
    if( isset($_SESSION['blad']) ){     // << 1 >>
        echo $_SESSION['blad'];
    }
?>
    
</body>
</html>

<!-- 
<< 1 >>    isset sprawdza czy w ogole jest taka zmienna utwrzona
robimy to po to ZEBY BLAD NIE POKAZYWAL SIE JUZ NA SAMYM STARCIE APLIKACJI 
-->