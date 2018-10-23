<?php
    session_start();    // << 1 >>

    if( !isset($_SESSION['zalogowany'])) {  // << 3 >>
        header('Location: index.php');   
        exit(); 
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

<?php

echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się!</a>]</p> ';
echo "<p><b>Drewno</b>:".$_SESSION['drewno'];
echo "|<b>Kamień</b>:".$_SESSION['kamien'];
echo "|<b>Zboże</b>:".$_SESSION['zboze']."</p>";

echo "<p><b>E-mail</b>:".$_SESSION['email'];
echo "<br><b>Dni premium</b>:".$_SESSION['dnipremium']."</p>";
?>

</body>
</html>

<!--
     Aby przeslac $user nie mozemy skorzystac ani z $_GET bo to widac w pasku adresu
ani z $_POST no bo to sluzy do obslugi formularzy
Musimy miec mozliwosc NIEJAWNEGO przesylania danych
czyli
SESJA czyli GLOBALNA TABLICA ASOCJACYJNA
SESJA jest to pojemnik na zmienne ale dostepny tylko dla tworyc serwisu
Jest ona przechowywana na serwerze I TYLKO PROGRAMISTA MOZE DO NIEJ ZAJRZEC

<< 1 >> Inicjujemy mozliwosc korzystania z sesji
<< 2 >> Mając SESJE mozemy juz bez przeszkod korzystac z jej danych

    JAK SESJA DZIALA - serwer przy pierwszym logowaniu uzytkownika
        nadaje mu PHPSESSID, i za kazdym razem jest on przesylany
        miedyz serwerem a poszczegolnymi stronami 
        ALBO JAKO CIASTECZKA albo jak ZASZYFROWANBY GET w adresie

        Kazda zmiana jakihs danych ROWNIEZ ZAPISYWANA JEST W TYM PLIKU
        I tak sobie to lata, bez magii

        Z sesja jest taki problem ze jak ktos przechwuci twoj PHPSESSID
        to siedzacv nawet na hawajach moze sobie buszowac po serwisie
        tak jak gdyby bylbys to ty
        Metody to kradniosci to np "session fixation" lub "session hijacking"
        M.in. dltaego W SESJI NIE POWINNO SIE PRZECHOWYWAC HASEL ! a co najwyzej
        login uzytkownika

Przycisk wylogowania wstawiamy zaraz obok przywitania  
    echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się!</a>]</p> ';

<< 3 >> Ustawiamy jeszcze linijki ktore zabronią niezalogowanej osobie przejsc do gra.php
    UWAGA ! Zwróć uwagę że jest tutaj operator "!" przed isset() !! Czy "JESLI NIE MA TEJ ZMIENNEJ"

    TAKIEGO IFA DOKLEIMY DO KAZDEJ PODSTRONY KOTRA MOZE WIDZIEC WYLACZNIE ZALOGOWANY USER

        if( !isset($_SESSION['zalogowany'])) {  // << 3 >>
            header('Location: index.php');   
            exit(); 
        }

 
-->