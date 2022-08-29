<?php

namespace App\Service;


use DateTimeImmutable;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function bin2hex;
use function random_bytes;


class CookieGeneratorService
{

    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var ParameterBagInterface
     */

    private  ParameterBagInterface $params;
   
    public function __construct(Configuration $config, ParameterBagInterface $params)
    {
        $this->config = $config;
        $this->params = $params;
    }

    public function issueToken(): Token
    {
    return $this->config->builder()
         ->identifiedBy(bin2hex(random_bytes(16)))
        ->getToken($this->config->signer(), $this->config->signingKey());
    }
     
    public function config(string $username)
    {
        $now = new DateTimeImmutable();
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->params->get('mercure_secret_key')));
        
        // $username = $this->getUser()->getUserIdentifier();
        $token = $config->builder()
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken($config->signer(), $config->signingKey());


    }

           
}
