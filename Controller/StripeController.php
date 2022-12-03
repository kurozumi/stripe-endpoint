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

namespace Plugin\StripeEndpoint\Controller;

use Eccube\Controller\AbstractController;
use Plugin\StripeEndpoint\Event\StripeEvent;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StripeController
 *
 * @Route("/stripe")
 */
class StripeController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/webhook/endpoint", name="stripe_webhook_endpoint")
     */
    public function endpoint(Request $request)
    {
        $signature = $request->server->get('HTTP_STRIPE_SIGNATURE');

        try {
            $event = new StripeEvent(Webhook::constructEvent($request->getContent(), $signature, $this->getParameter('stripe_signing_secret')));
        } catch (\UnexpectedValueException $exception) {
            throw new BadRequestHttpException('Invalid Stripe payload');
        } catch (SignatureVerificationException $exception) {
            throw new BadRequestHttpException('Invalid Stripe signature');
        }

        $this->eventDispatcher->dispatch($event->getName(), $event);

        return new Response();
    }
}
