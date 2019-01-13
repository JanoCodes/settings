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

		// Provide a shortcut to the ArraySettingStore for injecting into classes.
		$this->app->bind('Jano\Settings\ArraySettingStore', function($app) {
			return $app->make('Jano\Settings\SettingsManager')->driver();
		});

                // Provide a shortcut to the ObjectSettingStore for injecting into classes.
                $this->app->bind('Jano\Settings\ObjectSettingStore', function($app) {
                        return $app->make('Jano\Settings\SettingsManager')->driver();
                });

		$this->app->alias('Jano\Settings\ObjectSettingStore', 'setting');

		if (version_compare(Application::VERSION, '5.0', '>=')) {
			$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'settings');
		}
	}

	/**
	 * Boot the package.
	 */
	public function boot()
	{
		if (version_compare(Application::VERSION, '5.0', '>=')) {
			$this->publishes([
				__DIR__.'/config/config.php' => config_path('settings.php')
			], 'config');
			$this->publishes([
				__DIR__.'/migrations/2015_08_25_172600_create_settings_table.php' => database_path('migrations/'.date('Y_m_d_His').'_create_settings_table.php')
			], 'migrations');
		} else {
			$this->app['config']->package(
				'anlutro/l4-settings', __DIR__ . '/config', 'anlutro/l4-settings'
			);
		}
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
                        'Jano\Settings\ArraySettingStore',
			'Jano\Settings\ObjectSettingStore',
			'setting'
		);
	}
}
