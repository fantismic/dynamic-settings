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
    'component_blade' => 'auto',

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
    'layout_mode' => 'Component',

    /*
    |--------------------------------------------------------------------------
    | Component layout for full-page
    |--------------------------------------------------------------------------
    |
    |   Value must be the layout for the component to render: Ex: 'layouts.app'
    |
    */
    'layout_path' => null,
];