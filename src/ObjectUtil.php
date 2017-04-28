<?php
/**
 * Jano Ticketing System
 * Copyright (C) 2016-2017 Andrew Ying
 *
 * This file is part of Jano Ticketing System.
 *
 * Jano Ticketing System is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v3.0 as
 * published by the Free Software Foundation.
 *
 * Jano Ticketing System is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Jano\Settings;

/**
 * Object utility functions.
 */
class ObjectUtil
{
	/**
	 * This class is a static class and should not be instantiated.
	 */
	private function __construct()
	{
		//
	}

	/**
	 * Get an element from an object.
	 *
	 * @param  $data
	 * @param  string $key     Specify a nested element by separating keys with full stops.
	 * @param  mixed  $default If the element is not found, return this.
	 *
	 * @return mixed
	 */
	public static function get($data, $key, $default = null)
	{
		if ($key === null) {
			return $data;
		}

		if (is_array($key)) {
			return static::getArray($data, $key, $default);
		}

		foreach (explode('.', $key) as $segment) {
			if (!is_object($data)) {
				return $default;
			}

			if (!property_exists($data, $segment)) {
				return $default;
			}

			$data = $data->{$segment};
		}

		return $data;
	}

	protected static function getArray($input, $keys, $default = null)
	{
		$output = new \stdClass;

		foreach ($keys as $key) {
			static::set($output, $key, static::get($input, $key, $default));
		}

		return $output;
	}

	/**
	 * Determine if an object has a given property.
	 *
	 * @param  $data
	 * @param  string  $key
	 *
	 * @return boolean
	 */
	public static function has($data, $key)
	{
		foreach (explode('.', $key) as $segment) {
			if (!is_object($data) || !property_exists($data, $segment)) {
				return false;
			}

			$data = $data->{$segment};
		}

		return true;
	}

	/**
	 * Set an element of an array.
	 *
	 * @param $data
	 * @param string $key   Specify a nested element by separating keys with full stops.
	 * @param mixed  $value
	 */
	public static function set(&$data, $key, $value)
	{
		$segments = explode('.', $key);

		$key = array_pop($segments);

		// iterate through all of $segments except the last one
		foreach ($segments as $segment) {
			if (!property_exists($data, $segment)) {
				$data->{$segment} = new \stdClass;
			} else if (!is_object($data->{$segment})) {
				throw new \UnexpectedValueException('Non-object segment encountered');
			}

			$data =& $data->{$segment};
		}

		$data->{$key} = $value;
	}

	/**
	 * Unset an element from an array.
	 *
	 * @param  &$data
	 * @param  string $key   Specify a nested element by separating keys with full stops.
	 */
	public static function forget(&$data, $key)
	{
		$segments = explode('.', $key);

		$key = array_pop($segments);

		// iterate through all of $segments except the last one
		foreach ($segments as $segment) {
			if (!property_exists($data, $segment)) {
				return;
			} else if (!is_object($data->{$segment})) {
				throw new \UnexpectedValueException('Non-object segment encountered');
			}

			$data =& $data->{$segment};
		}

		unset($data->{$key});
	}
}
