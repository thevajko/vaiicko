# About

This framework was created to support the teaching of the subject Development of intranet and intranet applications 
(VAII) at the [Faculty of Management Science and Informatics](https://www.fri.uniza.sk/) of
[University of Žilina](https://www.uniza.sk/). Framework demonstrates how the MVC architecture works.

# Instructions and documentation 

The framework source code is fully commented. In case you need additional information to understand,
visit the [WIKI stránky](https://github.com/thevajko/vaiicko/wiki/00-%C3%9Avodn%C3%A9-inform%C3%A1cie) (only in Slovak).

# Docker configuration

The Framework has a basic configuration for running and debugging web applications in the `<root>/docker` directory. 
All necessary services are set in `docker-compose.yml` file. After starting them, it creates the following services:

- web server (Apache) with the __PHP 8.3__ 
- MariaDB database server with a created _database_ named according `MYSQL_DATABASE` environment variable
- Adminer application for MariaDB administration

## Other notes:

- __WWW document root__ is set to the `public` in the project directory.
- The website is available at [http://localhost/](http://localhost/).
- The server includes an extension for PHP code debugging [__Xdebug 3__](https://xdebug.org/), uses the  
  port __9003__ and works in "auto-start" mode.
- PHP contains the __PDO__ extension.
- The database server is available locally on the port __3306__. The default login details can be found in `.env` file.
- Adminer is available at [http://localhost:8080/](http://localhost:8080/)

