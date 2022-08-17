<?php

namespace Hitbtc;


use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    protected $publicKey;
    protected $secretKey;

    public function __construct($publicKey, $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use (&$handler) {
            $request = $request
                ->withAddedHeader('Authorization', 'Basic ' . base64_encode($this->publicKey . ':' . $this->secretKey))
                ->withAddedHeader('User-Agent', 'Hitbtc PHP Client');

            return $handler($request, $options);
        };
    }

    protected function getNonce()
    {
        return intval(microtime(true) * 1000);
    }
}
