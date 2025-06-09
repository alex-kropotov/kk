create table properties
(
    id_property               int auto_increment   primary key,
    id_place                  int               null,
    id_property_type          int               null,
    id_district               int               null,
    id_district_sub           int               null,
    id_street                 int               null,
    name_street               varchar(250)      null,
    is_name_street_recognized tinyint default 0 null,
    house_number              varchar(5)        null,
    id_building               int               null,
    house_number_modifier     varchar(5)        null,
    floor_type_enum           tinyint           null,
    floor_number              tinyint           null,
    floors_total              tinyint           null,
    area_property             float             null,
    area_land                 float             null,
    property_condition        tinyint           null,
    heating_type_enum         tinyint           null,
    room_structure_enum       int               null,
    room_count                tinyint           null,
    bedroom_count             tinyint           null,
    bathroom_count            tinyint           null,
    furnishing_status_enum    tinyint           null,
    constraint properties_places_id_place_fk  foreign key (id_place) references places (id_place),
    constraint properties_streets_id_street_fk  foreign key (id_street) references streets (id_street)
);

