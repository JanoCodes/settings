<?php

namespace Tests\Functional;

use HJSON\HJSONParser;
use HJSON\HJSONStringifier;
use Illuminate\Filesystem\Filesystem;
use Jano\Settings\HjsonSettingStore;

class HjsonTest extends ObjectAbstractFunctionalTest
{
	protected function createStore($data = null)
	{
		$path = dirname(__DIR__).'/tmp/store.hjson';
		$stringifier = new HJSONStringifier();

		if ($data !== null) {
			if ($data) {
				$json = $stringifier->stringify($data);
			} else {
				$json = '{}';
			}

			file_put_contents($path, $json);
		}

		return new HjsonSettingStore(
			new Filesystem, new HJSONParser, $stringifier, $path, false
		);
	}

	public function tearDown()
	{
		$path = dirname(__DIR__).'/tmp/store.hjson';
		unlink($path);
	}
}
