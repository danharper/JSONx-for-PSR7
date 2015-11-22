<?php

namespace danharper\Psr7JSONx\Tests\StreamFactories;

use danharper\Psr7JSONx\StreamFactories\CallableStreamFactory;
use danharper\Psr7JSONx\Tests\TestCase;

class CallableStreamFactoryTest extends TestCase {

	public function testItWorks()
	{
		// it just calls a function and relies on it to return a StreamInterface.. or not..
		$stream = (new CallableStreamFactory(function($body) { return ':D' . $body; }))->make('foooobarrr');

		$this->assertEquals(':Dfoooobarrr', $stream);
	}

}