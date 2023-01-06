<?php

namespace CourseLink\Omnipay;

use Omnipay\Common\Message\RequestInterface;

interface OAuth2TokenInterface
{
    public function getClientId(): string;

    public function setClientId(string $value): self;

    public function getClientSecret(): string;

    public function setClientSecret(string $value): self;

    public function getToken(bool $createIfNeeded = true): string;

    public function setToken(string $value): self;

    public function getTokenExpires(): ?int;

    public function setTokenExpires(int $value): self;

    public function hasToken(): bool;

    public function createToken(): RequestInterface;
}