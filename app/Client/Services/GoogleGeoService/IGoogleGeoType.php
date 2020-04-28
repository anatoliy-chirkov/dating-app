<?php

namespace Client\Services\GoogleGeoService;

interface IGoogleGeoType
{
    public const
        COUNTRY = 'country',
        REGION  = 'administrative_area_level_1',
        CITY    = 'locality'
    ;
}
