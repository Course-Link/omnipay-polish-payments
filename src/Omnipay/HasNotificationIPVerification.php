<?php

namespace CourseLink\Omnipay;

trait HasNotificationIPVerification
{
    public function getVerifyIpAddress(): bool
    {
        return $this->getParameter('verifyIpAddress');
    }

    public function setVerifyIpAddress(bool $value): self
    {
        return $this->setParameter('verifyIpAddress', $value);
    }

    public function getNotificationIpAddresses(): array
    {
        return $this->getParameter('notificationIpAddresses');
    }

    public function setNotificationIpAddresses($value): self
    {
        return $this->setParameter('notificationIpAddresses', $value);
    }

    public function checkNotificationIPAddress(): bool
    {
        if (!$this->getVerifyIpAddress()) {
            return true;
        }

        $ip = $this->httpRequest->getClientIp();

        return in_array($ip, $this->getNotificationIpAddresses());
    }

    /**
     * @see https://gist.github.com/jonavon/2028872
     */
    protected function cidrToArray(string $cidr): array
    {
        $cidr = explode('/', $cidr);
        $first = (ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1])));
        $last = (ip2long(long2ip($first))) + pow(2, (32 - (int)$cidr[1])) - 1;
        $array = [];
        for ($i = $first; $i < $last; $i++) {
            $array[] = long2ip($i);
        }

        return $array;
    }
}