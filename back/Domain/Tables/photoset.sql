create table photoset
(
    id_photoset   int auto_increment primary key,
    name_photoset varchar(100)      null,
    id_property   int               not null,
    at_created    datetime          not null,
    at_changed    datetime          null,
    is_active     tinyint default 1 not null,
    constraint photoset_properties_id_property_fk foreign key (id_property) references properties (id_property)
);

create index photoset_id_property_index on photoset (id_property);

