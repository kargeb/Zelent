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

if ( ( isset( $_SESSION['zalogowany'] )) && ( $_SESSION['zalogowany'] == true ) ){  // << 2 >>
    header('Location: gra.php');   // << 3 >>
    exit(); // << 4 >>
}

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

<< 2 >> dzieki zmiennej $_SESSION['zaloguj'] utworzonej w zaloguj.php ustawiamy tutaj warunek
    czy osoba powinna widziec panel logowania czy nie w zaleznosci od tego czy jest aktualnie zalogowana

 << 3 >>   UWAGA ! instrukcja    header('Location: gra.php');  NIE POWODUJE NATYCHMIASTOWEGO PRZEKIEROWANIA,
        Ona czeka az caly kod sie wykona I DOPIERO PO TYM nastepuje przekierowanie !

<< 4 >> DLATEGO KORZYSTAMY Z TAKIEJ FUNKCJI która natychmiast konczy wykonywanie pliku !!!        

    UWAGA ! powstaje pytanie czemu nie uzylismy exit() w pliku zaloguj.php ??
    DLATEGO ZE NIE WYKONALABY SIE WTEDY JEDNA Z OSTATNICH LINIJEK KODU
        $polaczenie->close();   !!!
    Czyli raz to exit() jest uzasadnione a raz NIE WOLNO !

Przekierowanie swietnie dziala, nie ma mozliwosci nawet wejsc na strone logowania

NO ALE TERAZ oczywiscie trzeba dac mozliwosc WYLGOGOWANIA
ABY wylogowac uzytkoniwka, najlepiej jest po prostu ZNISZCZYC SESJE i wszystkie dane ktore przechowywala
robimy to w nowym pliku  logout.php

-->