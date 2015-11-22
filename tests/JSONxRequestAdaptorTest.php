<?php

namespace danharper\Psr7JSONx\Tests;

use danharper\JSONx\JSONx;
use danharper\JSONx\ToJSONx;
use danharper\Psr7JSONx\JSONxRequestAdaptor;
use danharper\Psr7JSONx\StreamFactories\DiactorosStreamFactory;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;

class JSONxRequestAdaptorTest extends TestCase {

	public function testItDoesNothingForNonXmlRequests()
	{
		$request = (new Request)->withHeader('Content-Type', 'application/json');

		$this->assertSame($request, $this->go($request));
	}

	public function testItConvertsXmlRequests()
	{
		$request = $this->makeXmlRequest(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<json:array name="foo">
 <json:string>bar</json:string>
 <json:string>baz</json:string>
</json:array>
</json:object>
XML
		);

		$expectedBody = json_encode(['foo' => ['bar', 'baz']]);

		$this->assertEquals($expectedBody, $this->go($request)->getBody());
	}

	public function testConvertedXmlRequestsNowGetJsonContentTypeHeader()
	{
		$request = $this->makeXmlRequest(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>
XML
		);

		$this->assertEquals(['application/json'], $this->go($request)->getHeader('Content-Type'));
	}

	private function go(RequestInterface $request)
	{
		return (new JSONxRequestAdaptor(new JSONx, new DiactorosStreamFactory))->handle($request);
	}

	/**
	 * @param $body
	 * @return Request
	 */
	private function makeXmlRequest($body)
	{
		return (new Request)
			->withHeader('Content-Type', 'application/xml')
			->withBody((new DiactorosStreamFactory)->make($body));
	}

}