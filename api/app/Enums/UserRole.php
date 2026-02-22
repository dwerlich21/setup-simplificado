<?php

namespace App\Enums;

enum UserRole: string
{
    case Master = 'master';
    case Manager = 'manager';
    case ProductOwner = 'product_owner';
    case Monitor = 'monitor';

    public function label(): string
    {
        return match ($this) {
            self::Master => 'Master',
            self::Manager => 'Gerente',
            self::ProductOwner => 'Product Owner',
            self::Monitor => 'Monitor',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
