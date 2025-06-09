create table ads
(
    id_ads                 int auto_increment primary key,
    id_ads_type            int            not null,
    id_property            int            not null,
    ads_text               varchar(1000)  null,
    ads_description        varchar(5000)  null,
    id_photoset            int            null,
    price                  decimal(12, 2) null,
    agency_fee             int            null,
    utilities_payment_type int            null,
    at_start_show          datetime       null,
    at_stop_show           datetime       not null,
    is_active              tinyint        null,
    constraint ads_properties_id_property_fk foreign key (id_property) references properties (id_property)
);

create index ads_id_ads_type_index on ads (id_ads_type);

create index ads_id_photoset_index on ads (id_photoset);

create index ads_id_property_index on ads (id_property);

