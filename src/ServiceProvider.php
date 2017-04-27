<?php
/**
 * Laravel 4 - Persistent Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace Jano\Settings;

use Illuminate\Foundation\Application;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * This provider is deferred and should be lazy loaded.
	 *
	 * @var boolean
	 */
	protected $defer = true;

	/**
	 * Register IoC bindings.
	 */
	public function register()
	{
		$method = 'singleton';

		// Bind the manager as a singleton on the container.
		$this->app->$method('Jano\Settings\SettingsManager', function($app) {
			return new SettingsManager($app);
		});

		// Provide a shortcut to the SettingStore for injecting into classes.
		$this->app->bind('Jano\Settings\SettingStore', function($app) {
			return $app->make('Jano\Settings\SettingsManager')->driver();
		});

		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'settings');
	}

	/**
	 * Boot the package.
	 */
	public function boot()
	{
	    $this->publishes([
            __DIR__.'/config/config.php' => config_path('settings.php')
        ], 'config');
	}

	/**
	 * Which IoC bindings the provider provides.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'Jano\Settings\SettingsManager',
			'Jano\Settings\SettingStore',
		);
	}
}
