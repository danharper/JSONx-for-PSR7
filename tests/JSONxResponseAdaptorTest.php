<?php

namespace danharper\Psr7JSONx\Tests;

use danharper\JSONx\JSONx;
use danharper\Psr7JSONx\JSONxResponseAdaptor;
use danharper\Psr7JSONx\StreamFactories\DiactorosStreamFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

class JSONxResponseAdaptorTest extends TestCase {

	public function testItDoesNothingForResponsesNotRequestedToBeXml()
	{
		$request = (new Request)->withHeader('Accept', 'application/json');

		$response = new Response;

		$this->assertSame($response, $this->go($request, $response));
	}

	public function testItConvertsJsonResponseToXml()
	{
		$request = (new Request)->withHeader('Accept', 'application/xml');

		$response = (new Response)
			->withHeader('Content-Type', 'application/json')
			->withBody((new DiactorosStreamFactory)->make('{"foo": false}'));

		$expectedXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<json:boolean name="foo">false</json:boolean>
</json:object>
XML;

		$this->assertXmlStringEqualsXmlString($expectedXml, $this->go($request, $response)->getBody());
	}

	public function testItDoesNotConvertNonJsonResponsesToXml()
	{
		$request = (new Request)->withHeader('Accept', 'application/xml');

		$response = (new Response)->withHeader('Content-Type', 'foo');

		$this->assertSame($response, $this->go($request, $response));
	}

	private function go(RequestInterface $request, ResponseInterface $response)
	{
		return (new JSONxResponseAdaptor(new JSONx, new DiactorosStreamFactory))->handle($request, $response);
	}

}