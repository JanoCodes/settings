<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;

abstract class ObjectAbstractFunctionalTest extends TestCase
{
	protected abstract function createStore($data = NULL);

	protected function assertStoreEquals($store, $expected, $message = null)
	{
		$this->assertEquals($expected, $store->all(), $message);
		$store->save();
		$store = $this->createStore();
		$this->assertEquals($expected, $store->all(), $message);
	}

	protected function assertStoreKeyEquals($store, $key, $expected, $message = null)
	{
		$this->assertEquals($expected, $store->get($key), $message);
		$store->save();
		$store = $this->createStore();
		$this->assertEquals($expected, $store->get($key), $message);
	}

	/** @test */
	public function store_is_initially_empty()
	{
		$store = $this->createStore();

        $output = $store->all();
		$this->assertEquals(new \stdClass, $output);
	}

	/** @test */
	public function written_changes_are_saved()
	{
		$store = $this->createStore();
		$store->set('foo', 'bar');
		$this->assertStoreKeyEquals($store, 'foo', 'bar');
	}

	/** @test */
	public function nested_keys_are_nested()
	{
		$store = $this->createStore();
		$store->set('foo.bar', 'baz');

		$expected = new \stdClass;
		$daughter = new \StdClass;
		$daughter->bar = 'baz';
		$expected->foo = $daughter;
		$this->assertStoreEquals($store, $expected);
	}

	/**
     * @test
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Non-object segment encountered
     */
	public function cannot_set_nested_key_on_non_object_member()
	{
		$store = $this->createStore();
		$store->set('foo', 'bar');
		$store->set('foo.bar', 'baz');
	}

	/** @test */
	public function can_forget_key()
	{
		$store = $this->createStore();
		$store->set('foo', 'bar');
		$store->set('bar', 'baz');

		$expected = new \stdClass;
		$expected->foo = 'bar';
		$expected->bar = 'baz';

		$this->assertStoreEquals($store, $expected);
		
		$store->forget('foo');

		$expected = new \stdClass;
		$expected->bar = 'baz';
		$this->assertStoreEquals($store, $expected);
	}

	/** @test */
	public function can_forget_nested_key()
	{
		$store = $this->createStore();
		$store->set('foo.bar', 'baz');
		$store->set('foo.baz', 'bar');
		$store->set('bar.foo', 'baz');

		$expected = new \stdClass;
		$daughter_1 = new \stdClass;
		$daughter_1->bar = 'baz';
		$daughter_1->baz = 'bar';
		$daughter_2 = new \stdClass;
		$daughter_2->foo = 'baz';
		$expected->foo = $daughter_1;
		$expected->bar = $daughter_2;

 		$this->assertStoreEquals($store, $expected);
		
		$store->forget('foo.bar');

        $expected = new \stdClass;
        $daughter_1 = new \stdClass;
        $daughter_1->baz = 'bar';
        $daughter_2 = new \stdClass;
        $daughter_2->foo = 'baz';
        $expected->foo = $daughter_1;
        $expected->bar = $daughter_2;

		$this->assertStoreEquals($store, $expected);

		$store->forget('bar.foo');

        $expected = new \stdClass;
        $daughter_1 = new \stdClass;
        $daughter_1->baz = 'bar';
        $daughter_2 = new \stdClass;
        $expected->foo = $daughter_1;
        $expected->bar = $daughter_2;

		$this->assertStoreEquals($store, $expected);
	}

	/** @test */
	public function can_forget_all()
	{
	    $data = new \stdClass;
	    $data->foo = 'bar';
		$store = $this->createStore($data);

		$expected = new \stdClass;
		$expected->foo = 'bar';

		$this->assertStoreEquals($store, $expected);
		$store->forgetAll();
		$this->assertStoreEquals($store, new \stdClass());
	}
}
