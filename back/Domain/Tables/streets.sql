create table streets
(
    id_street      int auto_increment  primary key,
    id_place       int           not null,
    id_district    int default 0 not null,
    name_street_rs varchar(500)  null,
    name_street_ru varchar(500)  null,
    constraint street_places_id_place_fk foreign key (id_place) references places (id_place)
);

create index street_id_district_index
    on street (id_district);

create index street_id_place_index
    on street (id_place);

create index street_name_street_rs_index
    on street (name_street_rs);

create index street_name_street_ru_index
    on street (name_street_ru);



