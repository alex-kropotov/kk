create table u0628699_kuc.places
(
    id_place           int auto_increment primary key,
    type_place         int          default 0                 not null,
    id_country         int          default 0                 not null,
    id_region          int          default 0                 not null,
    id_county          int          default 0                 not null,
    id_municipality    int          default 0                 not null,
    id_city            int          default 0                 not null,
    id_district        int          default 0                 not null,
    id_sub_district    int          default 0                 not null,
    id_parent_district int          default 0                 not null,
    id_settlement      int          default 0                 not null,
    id_village         int          default 0                 not null,
    name_place_rs      varchar(500) default ''                not null,
    name_place_ru      varchar(500) default ''                not null,
    dt_created         datetime     default CURRENT_TIMESTAMP null,
    dt_changed         datetime     default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create index place_id_city_index
    on u0628699_kuc.places (id_city);

create index place_id_country_index
    on u0628699_kuc.places (id_country);

create index place_id_county_index
    on u0628699_kuc.places (id_county);

create index place_id_district_index
    on u0628699_kuc.places (id_district);

create index place_id_municipality_index
    on u0628699_kuc.places (id_municipality);

create index place_id_region_index
    on u0628699_kuc.places (id_region);

create index place_id_settlement_index
    on u0628699_kuc.places (id_settlement);

create index place_id_sub_district_index
    on u0628699_kuc.places (id_sub_district);

create index place_id_village_index
    on u0628699_kuc.places (id_village);

create index place_name_place_rs_index
    on u0628699_kuc.places (name_place_rs);

create index place_name_place_ru_index
    on u0628699_kuc.places (name_place_ru);

create index place_type_place_index
    on u0628699_kuc.places (type_place);

