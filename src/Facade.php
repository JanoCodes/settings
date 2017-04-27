<?php
/**
 * Laravel 4 - Persistent Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace Jano\Settings;

class Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return 'Jano\Settings\SettingsManager';
	}
}
