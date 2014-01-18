<?php
/**
 * Laravel 4 - Persistant Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/MIT
 * @package  l4-settings
 */

namespace anlutro\LaravelSettings;

class Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return 'anlutro\LaravelSettings\SettingStore';
	}
}
