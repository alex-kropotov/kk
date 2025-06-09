<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum DealType: string
{
    case SALE = 'sale';
    case RENT = 'rent';

    public function label(string $locale = 'sr'): string
    {
        return match($locale) {
            'en' => match($this) {
                self::SALE => 'For Sale',
                self::RENT => 'For Rent',
            },
            'ru' => match($this) {
                self::SALE => 'Продажа',
                self::RENT => 'Аренда',
            },
            default => match($this) {
                self::SALE => 'Prodaja',
                self::RENT => 'Izdavanje',
            },
        };
    }
}
