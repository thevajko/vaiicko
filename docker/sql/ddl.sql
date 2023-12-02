CREATE TABLE logins
(
    id       int(11)      NOT NULL AUTO_INCREMENT,
    login    varchar(254) NOT NULL UNIQUE,
    password varchar(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE personaldetails
(
    id         int(11)      NOT NULL AUTO_INCREMENT,
    email      varchar(254) NOT NULL UNIQUE,
    name       varchar(35)  NOT NULL,
    surname    varchar(35)  NOT NULL,
    gender     varchar(10)  NOT NULL,
    birthDate  datetime     NOT NULL,
    street     varchar(95)  NOT NULL,
    city       varchar(35)  NOT NULL,
    postalCode varchar(20)  NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE runners
(
    id                 int(11) NOT NULL AUTO_INCREMENT,
    logins_id          int(11) NOT NULL,
    personalDetails_id int(11) NOT NULL,

    PRIMARY KEY (id),

    foreign key (logins_id)
        references logins (id),
    foreign key (personalDetails_id)
        references personaldetails (id)
);

CREATE TABLE admins
(
    id        int(11) NOT NULL AUTO_INCREMENT,
    logins_id int(11) NOT NULL,

    PRIMARY KEY (id),

    foreign key (logins_id)
        references logins (id)
);

# insert into logins(login, password)
# values ('admin', 'admin');
#
# insert into admins(logins_id)
# values (1);

# drop table admins;
# drop table runners;
# drop table personaldetails;
# drop table logins;