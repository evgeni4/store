<?php


namespace App\Service\EncryptionServiceInterface;


interface EncryptionServiceInterface
{
    public function hash(string $password);
    public function verify(string $password,string $hash);
}