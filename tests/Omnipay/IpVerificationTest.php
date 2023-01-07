<?php

use CourseLink\Omnipay\HasNotificationIPVerification;
use CourseLink\Omnipay\NotificationIPVerificationInterface;

it('returns valid ip array from cidr', function () {
    $validator = new class implements NotificationIPVerificationInterface {
        use HasNotificationIPVerification;

        public function getDefaultNotificationIpAddresses(): array
        {
            return $this->cidrToArray('5.196.116.32/28');
        }
    };

    expect($validator->getDefaultNotificationIpAddresses())->toEqual([
        '5.196.116.32',
        '5.196.116.33',
        '5.196.116.34',
        '5.196.116.35',
        '5.196.116.36',
        '5.196.116.37',
        '5.196.116.38',
        '5.196.116.39',
        '5.196.116.40',
        '5.196.116.41',
        '5.196.116.42',
        '5.196.116.43',
        '5.196.116.44',
        '5.196.116.45',
        '5.196.116.46',
    ]);
});