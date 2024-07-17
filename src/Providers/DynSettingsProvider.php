<?php

namespace Fantismic\DynSettings\Providers;

use Livewire\Livewire;
use Fantismic\DynSettings\DynSettings;
use Illuminate\Support\ServiceProvider;
use Fantismic\DynSettings\Livewire\DynamicSettingsComponent;

class DynSettingsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

      $this->loadViewsFrom(__DIR__.'/../resources/views/livewire', 'DynSettingsPackage');

      if ($this->app->runningInConsole()) {
        // Export the migration
        if (! class_exists('create_dynamic_settings_table')) {
          $this->publishes([
            __DIR__ . '/../database/migrations/create_dynamic_settings_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_dynamic_settings_table.php'),
          ], 'migrations');
        }

        $this->publishes([
          __DIR__.'/../Config/config.php' => config_path('dynsettings.php'),
        ], 'config');
      }
    }

    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'dynsettings');

        $this->app->bind('DynSettings', function($app)
        {
            return new DynSettings();
        });

        Livewire::component('DynamicSettings', DynamicSettingsComponent::class);
    }
}