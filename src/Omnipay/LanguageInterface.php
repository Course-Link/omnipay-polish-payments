<?php

namespace CourseLink\Omnipay;

interface LanguageInterface
{
    public function getSupportedLanguages(): array;

    public function setLanguage(string $value): self;

    public function getLanguage(): string;
}