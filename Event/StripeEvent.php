<?php

/**
 * This file is part of StripeEndpoint
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\StripeEndpoint\Event;

use Stripe\ApiResource;
use Stripe\Event;

class StripeEvent extends \Symfony\Contracts\EventDispatcher\Event
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->event->type;
    }

    /**
     * @return ApiResource
     */
    public function getResource(): ApiResource
    {
        return $this->event->data->object;
    }
}
