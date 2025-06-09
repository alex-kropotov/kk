create table buildings
(
    id_building          int auto_increment         primary key,
    id_street            int           not null,
    building_number      int           not null,
    building_letter      varchar(10)   null,
    building_stage       int           null comment 'old, new, construction',
    building_description varchar(2000) null,
    constraint buildings_streets_id_street_fk  foreign key (id_street) references streets (id_street)
);

create index buildings_id_street_index  on buildings (id_street);



