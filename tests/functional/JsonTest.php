<?php

namespace Tests\Functional;

use Illuminate\Filesystem\Filesystem;
use Jano\Settings\JsonSettingStore;

class JsonTest extends ArrayAbstractFunctionalTest
{
	protected function createStore(array $data = null)
	{
		$path = dirname(__DIR__).'/tmp/store.json';

		if ($data !== null) {
			if ($data) {
				$json = json_encode($data);
			} else {
				$json = '{}';
			}

			file_put_contents($path, $json);
		}

		return new JsonSettingStore(
			new Filesystem, $path
		);
	}

	public function tearDown()
	{
		$path = dirname(__DIR__).'/tmp/store.json';
		unlink($path);
	}
}
