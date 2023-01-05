<?php

namespace CourseLink\Payments;

interface LanguageInterface
{
    public function getSupportedLanguages(): array;

    public function setLanguage(string $value): self;

    public function getLanguage(): string;
}