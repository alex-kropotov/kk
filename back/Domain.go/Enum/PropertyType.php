<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum PropertyType: string
{
    case HOUSE = 'house';
    case APARTMENT = 'apartment';
    case OFFICE = 'office';

    public function label(string $locale = 'sr'): string
    {
        return match($locale) {
            'en' => match($this) {
                self::HOUSE => 'House',
                self::APARTMENT => 'Apartment',
                self::OFFICE => 'Office',
            },
            'ru' => match($this) {
                self::HOUSE => 'Дом',
                self::APARTMENT => 'Квартира',
                self::OFFICE => 'Офис',
            },
            default => match($this) {
                self::HOUSE => 'Kuća',
                self::APARTMENT => 'Stan',
                self::OFFICE => 'Kancelarija',
            },
        };
    }
}
