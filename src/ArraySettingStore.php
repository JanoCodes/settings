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

abstract class ArraySettingStore
{
	/**
	 * The settings data.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Whether the store has changed since it was last loaded.
	 *
	 * @var boolean
	 */
	protected $unsaved = false;

	/**
	 * Whether the settings data are loaded.
	 *
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 * Get a specific key from the settings data.
	 *
	 * @param  string|array $key
	 * @param  mixed        $default Optional default value.
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		$this->load();

		return ArrayUtil::get($this->data, $key, $default);
	}

	/**
	 * Determine if a key exists in the settings data.
	 *
	 * @param  string  $key
	 *
	 * @return boolean
	 */
	public function has($key)
	{
		$this->load();

		return ArrayUtil::has($this->data, $key);
	}

	/**
	 * Set a specific key to a value in the settings data.
	 *
	 * @param string|array $key   Key string or associative array of key => value
	 * @param mixed        $value Optional only if the first argument is an array
	 */
	public function set($key, $value = null)
	{
		$this->load();
		$this->unsaved = true;
		
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				ArrayUtil::set($this->data, $k, $v);
			}
		} else {
			ArrayUtil::set($this->data, $key, $value);
		}
	}

	/**
	 * Unset a key in the settings data.
	 *
	 * @param  string $key
	 */
	public function forget($key)
	{
		$this->unsaved = true;

		if ($this->has($key)) {
			ArrayUtil::forget($this->data, $key);
		}
	}

	/**
	 * Unset all keys in the settings data.
	 *
	 * @return void
	 */
	public function forgetAll()
	{
		$this->unsaved = true;
		$this->data = array();
	}

	/**
	 * Get all settings data.
	 *
	 * @return array
	 */
	public function all()
	{
		$this->load();

		return $this->data;
	}

	/**
	 * Save any changes done to the settings data.
	 *
	 * @return void
	 */
	public function save()
	{
		if (!$this->unsaved) {
			// either nothing has been changed, or data has not been loaded, so
			// do nothing by returning early
			return;
		}

		$this->write($this->data);
		$this->unsaved = false;
	}

	/**
	 * Make sure data is loaded.
	 *
	 * @param $force Force a reload of data. Default false.
	 */
	public function load($force = false)
	{
		if (!$this->loaded || $force) {
			$this->data = $this->read();
			$this->loaded = true;
		}
	}

	/**
	 * Read the data from the store.
	 *
	 * @return array
	 */
	abstract protected function read();

	/**
	 * Write the data into the store.
	 *
	 * @param  array  $data
	 *
	 * @return void
	 */
	abstract protected function write(array $data);
}
