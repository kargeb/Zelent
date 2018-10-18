<?php

require_once "connect.php"; //<< 2 >>

$polaczenie = @new MySQLi($host, $db_user, $db_password, $db_name); // << 3 >>

if($polaczenie->connect_errno!=0){  //<< 4 >>
    echo "Error: ".$polaczenie->connect_errno ." Opis: ".$polaczenie->connect_error;
} else {      //  << 5 >>
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    echo "It works";

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


 -->