create table owners
(
    id_owner        int auto_increment   primary key,
    firstname_owner varchar(200)  null,
    lastname_owner  varchar(200)  not null,
    phone_owner     varchar(50)   null,
    email_owner     varchar(250)  null,
    address_owner   varchar(1000) null
);

