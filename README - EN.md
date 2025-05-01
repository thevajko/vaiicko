# About This Framework

This framework was created to support the teaching of the course "Development of Applications for Intranet and Internet" (VAII) at the [Faculty of Management and Informatics](https://www.fri.uniza.sk/) of the [University of Žilina](https://www.uniza.sk/). The framework is designed to be as minimal and simple as possible.

## Guide and Documentation

The framework code is fully commented. If you need additional information to understand it, visit the [WIKI pages](https://github.com/thevajko/vaiicko/wiki).

## Docker

The framework includes a basic configuration for running and debugging a web application in the `<root>/docker` directory. All necessary services are defined in `docker-compose.yml`. Once launched, the following will be set up:

- **WWW document root**  
  Set to the solution directory, meaning the web will be accessible at [http://localhost/](http://localhost/). The server includes a debugging module (`xdebug.start_with_request=yes`).

- **Web server**  
  Runs on PHP 8.3 with [Xdebug 3](https://xdebug.org/) configured on port **9003** in "auto-start" mode.

- **PHP extensions**  
  Comes with the PDO extension pre-installed.

- **Database server**  
  Preconfigured database with tables `messages` and `users` on port **3306** (accessible at `localhost:3306`).  
  Credentials:
  - `MYSQL_ROOT_PASSWORD`: db_user_pass
  - `MYSQL_DATABASE`: databaza
  - `MYSQL_USER`: db_user
  - `MYSQL_PASSWORD`: db_user_pass

- **adminer**  
  Available on port **8080** at [http://localhost:8080/](http://localhost:8080/), automatically connected to the database server.