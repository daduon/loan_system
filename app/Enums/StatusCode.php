<?php

namespace App\Enums;

enum StatusCode: string
{
    case ACTIVE = '01';
    case DISACTIVE = '02';

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::DISACTIVE => 'disactive',
        };
    }
}
