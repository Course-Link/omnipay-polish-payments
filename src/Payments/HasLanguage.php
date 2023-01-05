<?php

namespace CourseLink\Payments;

trait HasLanguage
{
    public function setLanguage(string $value): self
    {
        return $this->setParameter('language', $value);
    }

    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }
}