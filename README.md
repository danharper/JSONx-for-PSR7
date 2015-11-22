# JSONx for PSR-7

An incoming Request object with a `Content-Type: application/xml` header, will be converted to a JSON Request.

An outgoing Response object with a `Content-Type: application/json` header with a corresponding Request object with a `Accept: application/xml` header, will be converted to an XML Response.

It does this using IBM's standard for representing JSON as XML: [JSONx](https://tools.ietf.org/html/draft-rsalz-jsonx-00).

> **Notice** The library changes the body of HTTP Messages, and so it needs an implementation of `Psr\Http\Message\StreamInterface` to work. Or, I might be missing something.
> Either way, there's a `StreamFactoryInterface` you can implement to do it, or there's two implementations: `DiactorosStreamFactory` (for the [`zend/diactoros`](https://github.com/zendframework/zend-diactoros) PSR-7 implementation) and a `CallableStreamFactory` which you provide a function which will be given a string and you must return a stream containing it.

```php
$jsonxAdaptor = new JSONxPSR7(new DiactorosStreamFactory);

// or instead of DiactorosStreamFactory, implement your own, or use:

$streamFactory = new CallableStreamFactory(function($body) { return MyPsr7Stream::fromString($body); });
$jsonxAdaptor = new JSONxPSR7($streamFactory);
```

## Installation

```
composer require danharper/psr7-jsonx
```

## Example

Convert Requests like so:

```php
$request = $jsonxAdaptor->request($request);
```

Convert Responses like so:

```php
$response = $jsonxAdaptor->response($request, $response);
```
