create table property_owners
(
    id_property_owner int auto_increment  primary key,
    id_property       int      not null,
    id_owner          int      not null,
    at_started        datetime not null,
    at_canceled       datetime null,
    is_active         tinyint  null,
    constraint property_owners_owners_id_owner_fk  foreign key (id_owner) references owners (id_owner),
    constraint property_owners_properties_id_property_fk foreign key (id_property) references properties (id_property)
);

create index property_owners_id_owner_index  on property_owners (id_owner);

create index property_owners_id_property_index  on property_owners (id_property);

