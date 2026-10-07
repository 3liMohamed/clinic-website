<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IconSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->native(false);
        $this->searchable();
        $this->allowHtml();
        $this->selectablePlaceholder(false);

        $this->options($this->getIcons());
        $this->optionsLimit(110);
    }

    public function getIcons(): array
    {
        return collect($this->availableIcons())
            ->mapWithKeys(fn (string $label, string $class) => [
                $class => $this->renderOption($class, $label),
            ])
            ->toArray();
    }

    protected function renderOption(string $class, string $label): string
    {
        return "<span style='display: flex; align-items: center;'>
                    <i class='{$class} text-primary-600'
                    style='font-size: 1.5rem; width: 2rem; display: inline-block; text-align: center;'></i>

                    <span style='margin-inline-start: 0.5rem;'>{$label}</span>
                </span>";
    }

    protected function availableIcons(): array
    {
        $keywords = $this->clinicRelevantKeywords();

        return $this->extractIcofontIcons()
            ->merge($this->extractFontAwesomeIcons())
            ->filter(
                fn (string $label, string $class) => collect($keywords)->contains(fn ($keyword) => str_contains($class, $keyword))
            )
            ->toArray();
    }

    protected function clinicRelevantKeywords(): array
    {
        return [
            'kid',
            'tick',
            'ui',
            'listening',
            'smile',
            'simple',
            'table',
            'home',
            'ambulance',
            'hospital',
            'clinic',
            'medical',
            'medic',
            'doctor',
            'nurse',
            'surgeon',
            'surgery',
            'operation',
            'patient',
            'health',
            'healthcare',
            'stethoscope',
            'first-aid',
            'firstaid',
            'bandage',
            'plaster',
            'syringe',
            'injection',
            'needle',
            'medicine',
            'medication',
            'drug',
            'pill',
            'pills',
            'capsule',
            'tablet',
            'tablets',
            'prescription',

            'heart',
            'heartbeat',
            'heart-beat',
            'pulse',
            'blood',
            'blood-drop',
            'blood-test',
            'brain',
            'lungs',
            'lung',
            'kidney',
            'liver',
            'bone',
            'tooth',
            'teeth',
            'eye',
            'ear',
            'nose',
            'skin',
            'stomach',
            'body',

            'lab',
            'laboratory',
            'test',
            'test-tube',
            'testtube',
            'vial',
            'microscope',
            'xray',
            'x-ray',
            'scan',
            'diagnos',
            'dna',

            'thermometer',
            'stretcher',
            'crutch',
            'wheelchair',
            'bed',
            'patient-bed',
            'medical-sign',
            'medical-bag',
            'medical-kit',
            'medkit',
            'monitor',
            'ecg',
            'oxygen',
            'ventilator',

            'icu',
            'emergency',
            'pharmacy',
            'pharmac',
            'nursing',
            'nursing-home',
            'operation-theater',
            'operating',
            'theater',

            'aids',
            'autism',
            'allerg',
            'paralysis',
            'disability',
            'disabled',

            'dental',
            'dentist',
            'tooth',
            'teeth',

            'herbal',

            'calendar',
            'clock',
            'phone',
            'envelope',
            'location',
            'map',
            'marker',
            'user',
            'users',
            'comment',
            'info',
            'check',
            'shield',

            // Social
            'facebook',
            'twitter',
            'x-twitter',
            'instagram',
            'linkedin',
            'youtube',
            'google',
            'google-plus',
            'google-plus-g',
            'whatsapp',
            'telegram',
            'tiktok',
            'snapchat',
            'pinterest',
            'pinterest-p',
            'reddit',
            'reddit-alien',
            'github',
            'gitlab',
            'bitbucket',
            'stackoverflow',
            'skype',
            'vimeo',
            'vimeo-v',
            'flickr',
            'tumblr',
            'tumblr-square',
            'dribbble',
            'behance',
            'behance-square',
            'medium',
            'medium-m',
            'soundcloud',
            'spotify',
            'twitch',
            'discord',
            'vk',
            'weibo',
            'xing',
            'yelp',
            'qq',
            'wechat',
            'line',
            'threads',
            'mastodon',
            'odnoklassniki',
            'stumbleupon',
            'delicious',
            'digg',
            'rss',
            'feed',
        ];
    }

    protected function extractIcofontIcons(): Collection
    {
        $path = public_path('assets/css/icofont.css');

        if (! File::exists($path)) {
            return collect();
        }

        preg_match_all('/\.icofont-([a-zA-Z0-9_-]+):before/', File::get($path), $matches);

        return collect($matches[1])
            ->unique()
            ->mapWithKeys(fn (string $name) => [
                "icofont-{$name}" => Str::headline($name),
            ]);
    }

    protected function extractFontAwesomeIcons(): Collection
    {
        $path = public_path('assets/css/font-awesome.min.css');

        if (! File::exists($path)) {
            return collect();
        }

        preg_match_all('/\.fa-([a-zA-Z0-9_-]+):before/', File::get($path), $matches);

        $utilityClasses = ['lg', '2x', '3x', '4x', '5x', 'fw', 'ul', 'li', 'border', 'pull-left', 'pull-right', 'spin', 'stack', 'stack-1x', 'stack-2x', 'inverse', 'rotate-90', 'rotate-180', 'rotate-270', 'flip-horizontal', 'flip-vertical'];

        return collect($matches[1])
            ->unique()
            ->reject(fn (string $name) => in_array($name, $utilityClasses))
            ->mapWithKeys(fn (string $name) => [
                "fa fa-{$name}" => Str::headline($name),
            ]);
    }
}
