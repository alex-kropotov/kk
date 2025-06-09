<?php

namespace App\Domain\Enum;

enum PlaceTypeEnum: int
{
    case UNKNOWN = 0;
    case COUNTRY = 10;
    case REGION = 20;
    case COUNTY = 30;
    case MUNICIPALITY = 40; // опщина
    case CITY = 50; // город
    case DISTRICT = 60; // район города
    case SUBDISTRICT = 70; // часть района
    case SETTLEMENT = 80; // населенный пункт
    case VILLAGE = 90; // село
}
