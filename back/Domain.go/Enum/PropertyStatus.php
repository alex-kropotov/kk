<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum PropertyStatus: string
{
    case READY = 'ready';
    case NEW = 'new';
    case SHARED = 'shared';

    public function label(string $locale = 'sr'): string
    {
        return match($locale) {
            'en' => match($this) {
                self::READY => 'Ready',
                self::NEW => 'New Construction',
                self::SHARED => 'Shared',
            },
            'ru' => match($this) {
                self::READY => 'Готово',
                self::NEW => 'Новостройка',
                self::SHARED => 'Совместное',
            },
            default => match($this) {
                self::READY => 'Useljivo',
                self::NEW => 'Novogradnja',
                self::SHARED => 'Deljeno',
            },
        };
    }
}
