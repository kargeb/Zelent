<?php

session_start();    // << 15 >>

if ( ( !isset($_POST['login'] )) || ( !isset($_POST['haslo'] )) ){  // << 22 >>
    header('Location: index.php');
    exit();
}

require_once "connect.php"; //<< 2 >>

$polaczenie = @new MySQLi($host, $db_user, $db_password, $db_name); // << 3 >>

if($polaczenie->connect_errno!=0){  //<< 4 >>
    echo "Error: ".$polaczenie->connect_errno; // ." Opis: ".$polaczenie->connect_error; << 7 >>
} else {      //  << 5 >>
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    // echo "It works";

    $sql = " SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo' "; // << 8 >>

    if( $rezultat = @$polaczenie->query($sql) ) {

        $ilu_userow = $rezultat->num_rows;  //  << 10 >>

        if($ilu_userow>0) {// w zasadzie moze byc tez == 1

            $_SESSION['zalogowany'] = true; // << 19 >> 
            
            $wiersz = $rezultat->fetch_assoc(); // << 11 >>

            $_SESSION['id'] = $wiersz['id'];    // << 20 >>
            // $user = $wiersz['user']; - test przed wprowadzeniem do sesji
            $_SESSION['user'] = $wiersz['user']; // Taki sam zapis jak $_POST !!  // << 14 >>
            $_SESSION['drewno'] = $wiersz['drewno'];    // << 16 >>
            $_SESSION['kamien'] = $wiersz['kamien'];
            $_SESSION['zboze'] = $wiersz['zboze'];
            $_SESSION['email'] = $wiersz['email'];
            $_SESSION['dnipremium'] = $wiersz['dnipremium'];

            unset($_SESSION['blad']);    // << 18 >>
            
            $rezultat->close(); // $rezultat->free();,  $rezultat->free_result();   << 12 >>

            header('Location: gra.php');  //  << 13 >>
            
        } else {

            $_SESSION['blad'] = '<span style="color:red">Spierdalaj!</span>'; //  << 17 >>
            header('Location: index.php');
        }
    }

    $polaczenie->close(); // << 6 >>
}


// echo $login."<br>".$haslo;  //<< 1 >>

?>

<!-- 
Po przeslaniu danych logowania i sprawdzeniu czy dochodzą << 1 >>
Trzeba polaczyc sie z baza danych i sparwdzic czy dany uzytkownik istnieje

...

Tworzymy baze danych w phpmyadmin
importujemy plik osadnicy.php z jego ztrony do bazy danych "osadnicy"

plik bazy danych do phpmyadmin jest na jego blogu
http://miroslawzelent.pl/kurs-php/logowanie-do-strony-sesja-wstrzykiwanie-sql/

dni konta premim sa w liczbach ale w realnym serwisie powinna byc data 
i powinny byc obliczane

---------KODUJEMY POŁĄCZENIE-------
Dane które MUSZĄ BYĆ PODANE:
1 - Adres serwera MySQL
2 - Login do MySQL
3 - Haslo do SQL
4 - Nazwa bazy danych

UWAGA! Nie wpisaujemy tch danych do kazdego pliku php z osobna, tylko
tworzy sie osobny plik z nimi ktory includuje sie pozniej gdzuie chce.
Mozemy zawsze w jednym miejsci te dane poprawiac jak cos

Robimy to w "connect.php"
Zmiany w takim pliku wbrew pozorom beda czeste bo albo moezmy zmienic 
hosting albo prewencyjnie zmieniac haslo do bazy co miesiac.
To jest bardzo dobry powszechny nawyk

 ... tworzymy plik connect.php ...
 Dołączanie pliku 

 są 4 sposoby na to:
 include    -  (włącz plik do źródła)  gdy nie uda sie otworzyc zalaczonego pliku
    inluce wywala tylko ostrzezenie ale skrypt wykonuje sie dalej

 include_once   -   rozni sie to tym od zwyklego ze PHP sprawdza
    czy przypadkiem nie jest juz wczesniej ten plik dolaczony, i jesli 
    tak faktycznie jest no to nie bedzie dolaczany juz drugi raz

 require    -   (WYMAGAJ pliku w kodzie) gdy nie uda sie otworzyc załączonego pliku
    require wywala blad krytyczny i caly skrypt zostaje wstrzymany

 require_once   -   identyczna sytuacja jak w przypadku include_once

 No więc wybieramy REQUIRE_ONCE << 2 >>

.... otwarcie połączenia z bazą danych ....
UWAGA ! Dawniej korzystalo sie z funkcji:
mysql_connect oraz mysql_query

Ale obecnie są już zdeprecjonowane czyli są stare i niewspierane
od wersji 7 zostaną całkowicie wyjebane

Zamiast nich używać należy:
MySQLi oraz PDO::query - której z nich dokladnie to juz kwestia gustu
(mysql"i" to poprostu "i"mprooved)

<< 3 >> Teoretycznie połączeń może być kilka w jednym dokumencie więc trzeba je nazwać
    oto cala filozofia 
    @ - OZNACZA ZE BLEDY Z PHP NIE ZOSTAJĄ WYPISANE NA STRONIE,
        po prostu bierzemy na siebie ich obsluhe - jest to tzw operator kontroli błędów

<< 4 >> obsluga bledu logowania - jesli polaczenie sie nie wykona,
    to wartosc bedzie rozna od 0 I WTEDY IF SIĘ WYKONA

$polaczenie->connect_errno => w tym wyraznieniu $polaczenie TO OBIEKT a connect_errno TO WLASCIWOSC 
 
 << 5 >> if u gory byl na wypadel bledu z polaczenie wiec caly wlascuwy skrypt pakujemy do ELSE
 << 6 >> OD RAZU ZAMYKAMY POLACZENIE - PAMIETAJ O TYM
    robimy to w else, nie poza ifem ! No bo zamkniecie polaczenia nieotwartego generuje blad

<< 7 >> usuwamy opis bledu bo widac nazwe uzytkownika bazy danych a po ciula komus postronnemu ta informacja !    
    ktos zobaczy jaki mamy login admina "root" i juz bedzie wenszyl chuj

<< 8 >> Tworzymy pierwsze zapytanie ! Jest to lanuch wiec musi byc w cudzyslowiu
    no i zapamiętaj 
    ZAŁE ZAPYTANIE ZAPISUJEM W CUDZYSLOWIE A ZMIENNE W APOSTROFACH!
        $sql = " SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo' ";

<< 9 >> Wysylyamy zapytanie!
    robimy to METODĄ query w obiekcie $polacznie 
        if( $rezultat = @$polaczenie->query($sql) )
    ROBIMY TO W IFIE!
    No bo jak bedzie cos nie tak z polaczniem to meoda query ZWRÓCI FALSE i po prostu if sie nie wykona,
    a bedziemy w nim wyciagac rozne dane wiec nalepiej zeby faktycznie sie nie wykonaywal
    No i nie chodzi o czywiscie o brak uzytkownika tylko np literowke w zaptyaniu]

<< 10 >> Tworzymy zmienna w ktorej zapiszemy wynik naszego wyszukiwania ktory moze byc 1 lub 0
    $ilu_userow = $rezultat->num_rows;
        oczywiscie to my musimy w procesie rejestarcji zadbac o to zeby 
        nikt nie zalozyl dwuch identycnzych nazw uzytkownika

<< 11 >> Tworzymy tablice ASOCJACYJNA ktora bedzie miala takie same zmienne jak nazwy kolumn w bazie
        $wiersz = $rezultat->fetch_assoc();

    TABLICA ASOCJACYJNA to taka tablica która zamiast przypisywać wartości do numerów komórek
    PRZYPISUJE JE DO NAZW KOLUMN Z KTRÓYCH JE POBRAŁA
    A wiec INDEXAMI SA KONKRETNE WARTOSCI A NIE SUCHE NUMERY KOMÓREK

    No i teraz zeby odczytac np haslo nie musimy sie glowic pod jaka komorka ono jest w tabeli,
    tylko odczytujemy je za pomoca $wiersz[pass] !! (zmiast $wiersz[2])

<< 12 >> Juz teraz ustawiamy linijke ktora WYCZYSCI NAM CALA PAMIEC o wszystkich pobranych danych 
    gdy juz wyciagniemy wszystko co nas interesuje    
        $rezultat->close(); // $rezultat->free();,  $rezultat->free_result();

    Te pozostale 2 metody TO JEST DOKLADNIE TO SAMO tylko pod innym aliasem

    MUSISZ TO ROBIC ZAWSZE !!!!!!!
    Jesli tego nie zrobisz to za kazdym razem na swiecie umiera maly kotek

<< 13 >>    UWAGA ! wyników wyszukwiania nie chcemy miec na stronie logowania ale w nowym pliku juz z wlasciwa gra
    takze trzeba zrobic PRZEKIEROWANIE !
    a robi sie to jedną konkretną linijką 
        header('Location: gra.php');

    No i musimy oczywiscie sworzyc ten nowy plik "gra.php"

<< 14>> TWORZYMY SESJE
     $_SESSION['user'] = $wiersz['user'];

<< 15 >> aby w ogole sesja mogla dzialaj musimy ja aktywowac NA SAMYM POCZATKU DOKUMENTU
    session_start();
 
    UWAGA! Nie bedzie pozniej juz zadnego session_end ! 
    Po prostu trzeba zapamietac ze w kazdym dokumencie korzystajacym z sesji
    trzeba NA SAMEJ GORZE taka sesje zainicjowac I TYLE
    wiec to samo robimy we wszystkich plikach gdzie ta sesja bedzie potrzebna ("gra.php", "index.php")

<< 16 >> Wkladamy wszystkie potrzebne nam dane
<< 17 >> obsługujemy nieudane logowanie, co sie tu dzieje to juz wiesz (przekierowanie na strone logowania)
    Wiec w index.php TEZ DODAJEMY OSBLUGE SESJI 
<< 18 >>  USUWAMY zmienna 
        unset($_SESSION['blad'])    
        po chuj nam ona tutaj, nie trza jej tutaj

<< 19 >>  Ustawiamy zmienna ktora da znac ze uzytkownik JEST juz zalogowany, informacja przeznaczona dla strony golwnej
        JEST TO TZW FLAGA
        FLAGA - jest to symbol bool (true/false) który ustawia sie jako symbol ZAJŚCIA CZEGOŚ w kodzie

<< 20 >>    Dodamy sobie do tego zmienna z ID uzytkownika dzieki temu bedzie mozliwosc zmiany jego danych
        w dowolnym momencie programu - czy to zmiany surowców czy tez emaila        

        Obslugujemy teraz sytuacje w index.php gdzie ktos sie tam dostal a jest juz zalogowany

        OBSLUGUJEMY WYLOGOWANIE UZYTKOWNIKA czyli tworzymy nowy plik logout.php ktory zniszczy sesje i wszystkie jej dane

<< 21 >> Jeszce ustwaiamy ze NIE ZALOGOWANY CHUJ nie mogl sie dostac dp gra.php wpisujac adresz palca         

<< 22 >> i tutaj tez bronimy sie przed tym zeby nikt niezalogowany po prostu nie wpisal sobie w adresei zaloguj.php
 
 ---------------- NA TEN MOMENT MAMY OGARNIETY CAŁY WORKFLOW APLIKACJI !!! ------------------------

 MUSIMY SIE JESZCZE OBRONIC prze
    WSTRZYKIWANIEM KODU MYSQL 

        1.    LOGOWANIE NA UZYTKONIWKA NIE ZNAJAC JEGO HASLA

UWAGA ! Znając login kogoś można odjebać coś takiego:
    logując się podajesz:   "marek' -- "  (słownie: marek, apostrof, spacja, dwa mysliniki, spacja)

" -- " dwa myślniki to jest właśnie ZNAK KOMENTRAZA W MYSQL !!!     

Czyli zapystanie wyglądające tak:
    SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo'
Zmieniliśmy na 
     SELECT * FROM uzytkownicy WHERE user='marek' -- AND pass='$haslo'   

     USUNĘLIŚMY CAŁĄ CZĘŚĆ PO LOGINIE !!!


        2.    LOGOWANIE SIE NA PIERWSZEGO UZYTKONIWKA W BAZIE BEZ ZNAJOMOSCI NICZEGO 

login: aaaa
haslo:  ' OR 1=1 -- '

Czyli zapystanie wyglądające tak:
    SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo'
Zmieniliśmy na 
     SELECT * FROM uzytkownicy WHERE user='aaa' AND pass="OR 1=1 -- '        

    OR 1-1 jest to tzw TAUTOLOGIA
    TAUTOLOGIA - ZDANIE ZAWSZE PRAWDZIWE 

Dzięki OR warunek przejdzie bo jest spelniony warunek.
Test logowania dal wartosc TRUE

MINUTA 1:26

 -->