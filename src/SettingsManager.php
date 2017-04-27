<?php
/**
 * Laravel 4 - Persistent Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace Jano\Settings;

use Illuminate\Support\Manager;
use Illuminate\Foundation\Application;

class SettingsManager extends Manager
{
	public function getDefaultDriver()
	{
		return $this->getConfig('jano-may-ball/settings::store');
	}

	public function createJsonDriver()
	{
		$path = $this->getConfig('jano-may-ball/settings::path');

		return new JsonSettingStore($this->app['files'], $path);
	}

	public function createMemoryDriver()
	{
		return new MemorySettingStore();
	}

	public function createArrayDriver()
	{
		return $this->createMemoryDriver();
	}

	protected function getConfig($key)
	{
		$key = str_replace('jano-may-ball/settings::', 'settings.', $key);

		return $this->app['config']->get($key);
	}
}
