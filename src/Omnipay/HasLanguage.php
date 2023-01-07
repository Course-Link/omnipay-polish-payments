<?php

namespace CourseLink\Omnipay;

use Omnipay\Common\Exception\InvalidRequestException;

trait HasLanguage
{
    public function setLanguage(string $value): self
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @throws InvalidRequestException
     */
    public function getLanguage(): string
    {
        $language = $this->getParameter('language');

        if (!in_array($language, $this->getSupportedLanguages())) {
            throw new InvalidRequestException;
        }

        return $language;
    }

    /**
     * @throws InvalidRequestException
     */
    public function getLanguageISO639_1(): string
    {
        return $this->getLanguage();
    }

    /**
     * @throws InvalidRequestException
     */
    public function getLanguageBCP47(): string
    {
        return match ($this->getLanguage()) {
            'pl' => 'pl-PL',
            'en' => 'en-GB',
            'uk' => 'uk-UK',
        };
    }
}