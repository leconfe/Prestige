<?php

namespace Prestige;

use App\Classes\Theme;
use App\Facades\Hook;
use App\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Forms\Components\TinyEditor;
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use luizbills\CSS_Generator\Generator as CSSGenerator;
use matthieumastadenis\couleur\ColorFactory;
use matthieumastadenis\couleur\ColorSpace;

class PrestigeTheme extends Theme
{
    public function boot()
    {
        if (app()->getCurrentScheduledConference()?->getMeta('theme') == 'Prestige') {
            Blade::anonymousComponentPath($this->getPluginPath('resources/views/frontend/website/components'), prefix: 'website');
            Blade::anonymousComponentPath($this->getPluginPath('resources/views/frontend/scheduledConference/components'), prefix: 'scheduledConference');
        }
    }

    public function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->contained(false)
                ->tabs([
                    Tabs\Tab::make('General')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('banner_bg')
                                ->collection('prestige_banner_bg')
                                ->label('Upload Banner Images')
                                ->image()
                                ->conversion('thumb-xl')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                            ColorPicker::make('banner_text_color')
                                ->regex('/^#?(([a-f0-9]{3}){1,2})$/i')
                                ->label('Banner Text Color'),
                            Grid::make()
                                ->schema([
                                    ColorPicker::make('base_color')
                                        ->regex('/^#?(([a-f0-9]{3}){1,2})$/i')
                                        ->label('Base Color'),
                                    ColorPicker::make('base_color_content')
                                        ->regex('/^#?(([a-f0-9]{3}){1,2})$/i')
                                        ->label('Base Color Content'),
                                ]),
                            Grid::make()
                                ->schema([
                                    ColorPicker::make('primary_color')
                                        ->regex('/^#?(([a-f0-9]{3}){1,2})$/i')
                                        ->label('Primary Color'),
                                    ColorPicker::make('primary_color_content')
                                        ->regex('/^#?(([a-f0-9]{3}){1,2})$/i')
                                        ->label('Primary Color Content'),
                                ]),
                            Checkbox::make('countdown_timer')
                                ->label('Show Countdown Timer')
                                ->helperText('Enable this to show a countdown timer on the homepage'),
                            Repeater::make('hero_buttons')
                                ->schema([
                                    TextInput::make('text')->required(),
                                    TextInput::make('url')
                                        ->required()
                                        ->url(),
                                    ColorPicker::make('text_color'),
                                    ColorPicker::make('background_color'),
                                ])
                                ->columns(2),
                        ]),
                    Tabs\Tab::make('Layouts')
                        ->schema([
                            Builder::make('layouts')
                                ->collapsible()
                                ->collapsed()
                                ->cloneable()
                                ->blockNumbers(false)
                                ->reorderableWithButtons()
                                ->reorderableWithDragAndDrop(false)
                                ->blocks([
                                    Builder\Block::make('custom')
                                        ->label('Custom Content')
                                        ->icon('heroicon-m-bars-3-bottom-left')
                                        ->schema([
                                            TextInput::make('title'),
                                            Checkbox::make('show_title')
                                                ->label('Show title on page'),
                                            TinyEditor::make('content')
                                                ->profile('advanced')
                                                ->required(),
                                            Checkbox::make('full_width')
                                                ->label('Full Width'),
                                        ]),
                                    Builder\Block::make('two-column')
                                        ->label('2 Column Content')
                                        ->icon('heroicon-o-bars-2')
                                        ->schema([
                                            TextInput::make('title'),
                                            Checkbox::make('show_title')
                                                ->label('Show title on page'),
                                            TinyEditor::make('content_left')
                                                ->profile('advanced')
                                                ->required(),
                                            TinyEditor::make('content_right')
                                                ->profile('advanced')
                                                ->required(),
                                            Checkbox::make('full_width')
                                                ->label('Full Width'),
                                        ]),
                                    Builder\Block::make('three-column')
                                        ->label('3 Column Content')
                                        ->icon('heroicon-o-bars-3')
                                        ->schema([
                                            TextInput::make('title'),
                                            Checkbox::make('show_title')
                                                ->label('Show title on page'),
                                            TinyEditor::make('content_one')
                                                ->profile('advanced')
                                                ->required(),
                                            TinyEditor::make('content_two')
                                                ->profile('advanced')
                                                ->required(),
                                            TinyEditor::make('content_three')
                                                ->profile('advanced')
                                                ->required(),
                                            Checkbox::make('full_width')
                                                ->label('Full Width'),
                                        ]),
                                    Builder\Block::make('speakers')
                                        ->label('Speakers')
                                        ->icon('heroicon-o-users')
                                        ->maxItems(1),
                                    Builder\Block::make('committees')
                                        ->label('Committees')
                                        ->icon('heroicon-o-users')
                                        ->maxItems(1),
                                    Builder\Block::make('sponsors')
                                        ->label('Sponsors')
                                        ->icon('heroicon-o-building-office-2')
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Title'),
                                        ])
                                        ->maxItems(1),
                                    Builder\Block::make('partners')
                                        ->label('Partners')
                                        ->icon('heroicon-o-building-office')
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Title'),
                                        ])
                                        ->maxItems(1),
                                    Builder\Block::make('timelines')
                                        ->label('Timelines')
                                        ->icon('heroicon-o-calendar-days')
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('Title'),
                                        ])
                                        ->maxItems(1),
                                    Builder\Block::make('galleries')
                                        ->label('Gallery')
                                        ->icon('heroicon-o-photo')
                                        ->schema([
                                            Hidden::make('image_collection_id'),
                                            TextInput::make('title')
                                                ->label('Title')
                                                ->required(),
                                            SpatieMediaLibraryFileUpload::make('galleries')
                                                ->collection(function (FileUpload $component, Get $get) {
                                                    return $get('image_collection_id') ?? $component->getContainer()->getStatePath() . '.' . $component->getStatePath(false);
                                                })
                                                ->afterStateHydrated(null)
                                                ->mutateDehydratedStateUsing(null)
                                                ->image()
                                                ->multiple()
                                                ->conversion('thumb-xl')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->afterStateUpdated(function (FileUpload $component, Set $set) {
                                                    $set('image_collection_id', $component->getContainer()->getStatePath() . '.' . $component->getStatePath(false));
                                                })
                                                ->reorderable()
                                                ->live(),
                                        ]),
                                ])


                        ]),
                ]),
        ];
    }

    public function getFormData(): array
    {
        return [
            'base_color' => $this->getSetting('base_color'),
            'base_color_content' => $this->getSetting('base_color_content'),
            'banner_text_color' => $this->getSetting('banner_text_color'),
            'primary_color' => $this->getSetting('primary_color'),
            'primary_color_content' => $this->getSetting('primary_color_content'),
            'hero_buttons' => $this->getSetting('hero_buttons') ?? [],
            'layouts' => $this->getLayouts(),
            'countdown_timer' => $this->getSetting('countdown_timer'),
        ];
    }

    public function getLayouts()
    {
        return $this->getSetting('layouts') ?? $this->getDefaultLayouts();
    }

    public function getDefaultLayouts()
    {
        return [
            [
                "type" => "speakers",
                "data" => [
                    "title" => null,
                ]
            ],
            [
                "type" => "sponsors",
                "data" => [
                    "title" => null,
                ]
            ],
            [
                "type" => "partners",
                "data" => [
                    "title" => null,
                ]
            ],
        ];
    }

    public function onActivate(): void
    {
        Hook::add('Frontend::Views::Head', function ($hookName, &$output) {
            $output .= (new Vite())->useHotFile(base_path('plugins') . DIRECTORY_SEPARATOR . $this->getInfo('folder') . DIRECTORY_SEPARATOR . 'vite.hot')
                ->useBuildDirectory('plugin' . DIRECTORY_SEPARATOR . Str::lower($this->getInfo('folder')) . DIRECTORY_SEPARATOR . 'build')
                ->withEntryPoints(['resources/css/app.css', 'resources/js/app.js'])
                ->toHtml();

            $primaryColor = $this->getSetting('primary_color');
            $primaryColorContent = $this->getSetting('primary_color_content');
            $baseColor = $this->getSetting('base_color');
            $baseColorContent = $this->getSetting('base_color_content');
            $bannerTextColor = $this->getSetting('banner_text_color');

            $css = new CSSGenerator;
            if ($primaryColor) {
                $oklch = ColorFactory::new($primaryColor)->to(ColorSpace::OkLch);
                $css->root_variable('p', "{$oklch->lightness}% {$oklch->chroma} {$oklch->hue}");
            }
            if($bannerTextColor){
                $css->root_variable('text-hero', $bannerTextColor);
            }
            if ($primaryColorContent) {
                $oklch = ColorFactory::new($primaryColorContent)->to(ColorSpace::OkLch);
                $css->root_variable('pc', "{$oklch->lightness}% {$oklch->chroma} {$oklch->hue}");
            }
            if ($baseColor) {
                $oklch = ColorFactory::new($baseColor)->to(ColorSpace::OkLch);
                $css->root_variable('b1', "{$oklch->lightness}% {$oklch->chroma} {$oklch->hue}");
            }
            if ($baseColorContent) {
                $oklch = ColorFactory::new($baseColorContent)->to(ColorSpace::OkLch);
                $css->root_variable('bc', "{$oklch->lightness}% {$oklch->chroma} {$oklch->hue}");
            }

            $output .= <<<HTML
                <style>
                    {$css->get_output()}
                </style>
            HTML;
        });
    }


    function formatDateRange($startDate, $endDate)
    {
        // Check if both dates are null
        if (is_null($startDate) && is_null($endDate)) {
            return '';
        }

        // Parse the dates using Carbon (if not null)
        $start = $startDate ? Carbon::parse($startDate) : null;
        $end = $endDate ? Carbon::parse($endDate) : null;

        // Function to format the date with ordinal suffix and year
        $formatWithOrdinal = function ($date) {
            return $date->isoFormat('MMMM Do, YYYY');
        };

        // Determine the output format
        if ($start && $end) {
            if ($start->equalTo($end)) {
                // Same start and end date
                return $formatWithOrdinal($start);
            } elseif ($start->isSameMonth($end) && $start->isSameYear($end)) {
                // Same month and year
                return $start->isoFormat('MMMM Do') . '-' . $end->isoFormat('Do, YYYY');
            } else {
                // Different month or year
                return $formatWithOrdinal($start) . ' - ' . $formatWithOrdinal($end);
            }
        } elseif ($start) {
            // Only start date provided
            return $formatWithOrdinal($start);
        } else {
            // Only end date provided
            return $formatWithOrdinal($end);
        }
    }
}
