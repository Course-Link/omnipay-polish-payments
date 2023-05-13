<?php

namespace CourseLink\Omnipay;

use Omnipay\Common\Message\NotificationInterface;

enum TransactionStatus: string
{
    /**
     * Payment is currently being processed.
     */
    case PENDING = 'pending';

    /**
     * Payment has been successfully processed.
     */
    case COMPLETED = 'completed';

    /**
     * Payment has been canceled by the customer.
     */
    case CANCELED = 'canceled';

    /**
     * Payment has been rejected by the payment gateway.
     */
    case ERROR = 'error';

    public function toNotificationStatus(): string
    {
        return match ($this) {
            self::PENDING => NotificationInterface::STATUS_PENDING,
            self::COMPLETED => NotificationInterface::STATUS_COMPLETED,
            self::CANCELED, self::ERROR => NotificationInterface::STATUS_FAILED,
        };
    }
}
