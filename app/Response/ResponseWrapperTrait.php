<?php

namespace App\Response;

use Psr\Http\Message\StreamInterface;

trait ResponseWrapperTrait {

    private $res;

    public function getStatusCode() 
    {
        return $this->res->getStatusCode();
    }

    public function withStatus(int $code, string $reasonPhrase = '') 
    {
        return $this->res->withStatus($code, $reasonPhrase);
    }

    public function getReasonPhrase() 
    {
        return $this->res->getReasonPhrase();
    }

    public function getProtocolVersion() 
    {
        return $this->res->getProtocolVersion();
    }

    public function withProtocolVersion(string $version) 
    {
        return $this->res->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->res->getHeaders();
    }

    public function hasHeader(string $name)
    {
        return $this->res->hasHeader($name);
    }

    public function getHeader(string $name)
    {
        return $this->res->getHeader($name);
    }

    public function getHeaderLine(string $name)
    {
        return $this->res->getHeaderLine($name);
    }

    public function withHeader(string $name, $value)
    {
        return $this->res->withHeader($name, $value);
    }

    public function withAddedHeader(string $name, $value) 
    {
        return $this->res->withAddedHeader($name, $value);
    }

    public function withoutHeader(string $name)
    {
        return $this->res->withoutHeader($name);
    }

    public function getBody() 
    {
        return $this->res->getBody();
    }

    public function withBody(StreamInterface $body) 
    {
        return $this->res->withBody($body);
    }
}