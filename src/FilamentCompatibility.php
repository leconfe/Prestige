<?php

namespace Prestige;

use RuntimeException;

final class FilamentCompatibility
{
    /**
     * @return class-string
     */
    public static function tabs(): string
    {
        return self::resolve(
            'Filament\Schemas\Components\Tabs',
            'Filament\Forms\Components\Tabs',
        );
    }

    /**
     * @return class-string
     */
    public static function tab(): string
    {
        return self::resolve(
            'Filament\Schemas\Components\Tabs\Tab',
            'Filament\Forms\Components\Tabs\Tab',
        );
    }

    /**
     * @return class-string
     */
    public static function grid(): string
    {
        return self::resolve(
            'Filament\Schemas\Components\Grid',
            'Filament\Forms\Components\Grid',
        );
    }

    /**
     * @return class-string
     */
    private static function resolve(string $filamentFiveClass, string $filamentThreeClass): string
    {
        if (class_exists($filamentFiveClass)) {
            return $filamentFiveClass;
        }

        if (class_exists($filamentThreeClass)) {
            return $filamentThreeClass;
        }

        throw new RuntimeException(
            "Prestige requires a compatible Filament installation; neither [{$filamentFiveClass}] nor [{$filamentThreeClass}] is available.",
        );
    }
}
