# O tomto frameworku

Tento framework vznikol na podporu výučby predmetu Vývoj aplikácií pre intranet a intrenate (VAII) na [Fakulte informatiky a riadenia](https://www.fri.uniza.sk/) [Žilinskej univerzity v Žiline](https://www.uniza.sk/). Framework je navrhnutý tak aby bol čo najmenší a najjednoduchší. 

# Návod a dokumentácia

Kód frameworku je kompletne okomentovaný. V prípade, že na pochopenie potrebujete dodatočné informácie, navštívte [WIKI stránky](https://github.com/thevajko/vaiicko/wiki/00-%C3%9Avodn%C3%A9-inform%C3%A1cie).

# Docker

Framework ma v adresári `<root>/docker` základnú konfiguráciu pre spustenie a debug web aplikácie. Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:

 - __WWW document root__ je nastavený adresár riešenia, čiže web bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie móde" (`xdebug.start_with_request=yes`).
 - webový server beží na __PHP 8.0__ s [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__ v "auto-štart" móde
 - PHP ma doinštalované rozšírenie __PDO__
 - databázový server s vytvorenou _databázou_ a tabuľkami `messages` a `users` na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
   - MYSQL_ROOT_PASSWORD: db_user_pass
   - MYSQL_DATABASE: databaza
   - MYSQL_USER: db_user
   - MYSQL_PASSWORD: db_user_pass
 - phpmyadmin server, ktorý sa automatický nastavený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

