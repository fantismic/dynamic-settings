<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Component blade
    |--------------------------------------------------------------------------
    |
    | Options: 
    |   'auto':     use wire ui if installed, fallback to normal blade
    |   'wireui':  always use wire ui blade
    |   'blade':    always use normal blade
    |
    */
    'component_blade' => 'wireui',

    /*
    |--------------------------------------------------------------------------
    | Component layout mode
    |--------------------------------------------------------------------------
    |
    | Options: 
    |   'fullpage':   Adds layout to render the component in full-page mode: https://livewire.laravel.com/docs/components#full-page-components
    |   'component':  Component must be added inside a blade: <livewire:DynamicSettings /> 
    |
    */
    'layout_mode' => 'component',

    /*
    |--------------------------------------------------------------------------
    | Component layout for full-page
    |--------------------------------------------------------------------------
    |
    |   Value must be the layout for the component to render: Ex: 'layouts.app'
    |
    */
    'layout_path' => null,


    /*
    |--------------------------------------------------------------------------
    |   Alert array format
    |--------------------------------------------------------------------------
    |
    | When setting is array type the built-in component expects values ​​to be comma separated
    | If true, a message will be preppend to the setting description in the views
    |
    */
    'alert_array_format' => true
];