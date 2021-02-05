<?php

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Timothyasp\Color\Color;

return [
    'available_fields' => [
        Boolean::class => 'Boolean',
        BooleanGroup::class => 'BooleanGroup',
        Date::class => 'Date',
        Number::class => 'Number',
        Select::class => 'Select',
        Text::class => 'Text',
        Textarea::class => 'Textarea',
        Images::class => 'Images',
        Color::class => 'Color',
    ],
    
    'validators' => [
        'required',
        'string',
        'integer',
        'email',
        'array',
        'boolean',
        'date',
        'nullable',
    ],
    
    'relations' => [
        'types_list' => [],
    ],
];
