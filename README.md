# Stripeエンドポイント for EC-CUBE4

EC-CUBE4でStripeのWebhookに登録するエンドポイントを構築するためのプラグインです。

## Stripeライブラリをインストール

Stripeのライブラリが必須なのでcomposerでインストールしてください。

```bash
composer require stripe/stripe-php
```

## Webhookの署名シークレットを設定

.envにWebhookの署名シークレットを設定してください。

```text
STRIPE_SIGNING_SECRET=whsec_t6KFvU8...
```

## プラグインをインストール

StripeEndpointプラグインをEC-CUBE4にインストール・有効化してください。

```bash
cd app/Plugin
git clone git@github.com:kurozumi/StripeEndpoint.git
bin/console e:p:i --code StripeEndpoint
bin/console e:p:e --code StripeEndpoint
```

## エンドポイント

下記のエンドポイントをStripeのWebhookに登録してください。

```text
https://my-domain/stripe/webhook/endpoint
```

## 支払いが完了したときや定期支払いがキャンセルされたときに何かする処理を実装する方法

```php
<?php
namespace Customize\EventListener;


use Plugin\StripeEndpoint\Event\StripeEvent;
use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\Subscription;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StripeEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Event::PAYMENT_INTENT_SUCCEEDED => ['onPaymentIntentSucceeded'],
            Event::CUSTOMER_SUBSCRIPTION_DELETED => ['onCustomerSubscriptionDeleted']
        ];
    }

    /**
     * 支払いが完了したときに何かする
     *
     * @param StripeEvent $event
     */
    public function onPaymentIntentSucceeded(StripeEvent $event)
    {
        /** @var PaymentIntent $paymentIntent */
        $paymentIntent = $event->getResource();
    }

    /**
     * 定期支払がキャンセルされたときに何かする
     *
     * @param StripeEvent $event
     */
    public function onCustomerSubscriptionDeleted(StripeEvent $event)
    {
        /** @var Subscription $subscriptions */
        $subscription = $event->getResource();
    }
}

```

## ご注意

カスタマイズ、または他社プラグインとの競合による動作不良つきましてはサポート対象外です。

本プラグインを導入したことによる不具合や被った不利益につきましては一切責任を負いません。 ご理解の程よろしくお願いいたします。
