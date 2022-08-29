<?php

namespace App\Controller;

use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;
use function bin2hex;
use DateTimeImmutable; 
use Lcobucci\JWT\Token;
use function random_bytes;
use Lcobucci\JWT\Configuration;
use App\Service\CookieGeneratorService;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;


use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexController extends AbstractController
{

    // private $config;
   
    // public function __construct(Configuration $config)
    // {
    //     $this->config = $config;
    // }

    // public function issueToken(): Token
    // {
    // return $this->config->builder()
    //      ->identifiedBy(bin2hex(random_bytes(16)))
    //     ->getToken($this->config->signer(), $this->config->signingKey());
    // }
    /**
     * @Route("/", name="index")
     */

    public function index(CookieGeneratorService $cookie)
    {
        $username = $this->getUser()->getUserIdentifier();
        $cookie->config($username);
        // $now = new DateTimeImmutable();
        // $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('mercure_secret_key'));
        
        // $token = $config->builder()
        //     ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
        //     ->getToken($config->signer(), $config->signingKey());

        $response = $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict',
            )
        );
            return $response;
    }
}
