<?php

class HjsonTest extends AbstractFunctionalTest
{
	protected function createStore(array $data = null)
	{
		$path = dirname(__DIR__).'/tmp/store.hjson';
		$stringifier = new \HJSON\HJSONStringifier;

		if ($data !== null) {
			if ($data) {
				$json = $stringifier->stringifyWsc($data);
			} else {
				$json = '{}';
			}

			file_put_contents($path, $json);
		}

		return new \Jano\Settings\HjsonSettingStore(
			new \Illuminate\Filesystem\Filesystem, new HJSON\HJSONParser, $stringifier, $path
		);
	}

	public function tearDown()
	{
		$path = dirname(__DIR__).'/tmp/store.hjson';
		unlink($path);
	}
}
