<?php

namespace Financas\Auth;

use Financas\Auth\JasnyAuth;
use Financas\Auth\AuthInterface;
use Financas\Models\UserInterface;

class Auth implements AuthInterface
{

    /**
     * @var JasnyAuth
     */
    private $JasnyAuth;

    public function __construct(JasnyAuth $jasnyAuth)
    {
        $this->JasnyAuth = $jasnyAuth;
        $this->sessionStart();
    }

    public function login(array $credentials): bool
    {
        list('email' => $email, 'password' => $password) = $credentials;
        return $this->JasnyAuth->login($email,$password) !== null;
    }

    public function check():bool
    {
        return $this->user() !== null; 
    }

    public function logout(): void
    {
        $this->JasnyAuth->logout();
    }
    public function hashPassword(string $password):string
    {
        return $this->JasnyAuth->hashPassword($password);
    }

    protected function sessionStart()
    {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public function user(): ?UserInterface
    {
        return $this->JasnyAuth->user();
    }
}