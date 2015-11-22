<?php

namespace danharper\Psr7JSONx\Tests\StreamFactories;

use danharper\Psr7JSONx\StreamFactories\DiactorosStreamFactory;
use danharper\Psr7JSONx\Tests\TestCase;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Stream;

class DiactorosStreamFactoryTest extends TestCase {

	public function testItWorks()
	{
		$stream = (new DiactorosStreamFactory)->make('foo bar');

		$this->assertInstanceOf(Stream::class, $stream);
		$this->assertInstanceOf(StreamInterface::class, $stream);
		$this->assertEquals('foo bar', (string) $stream);
	}

}