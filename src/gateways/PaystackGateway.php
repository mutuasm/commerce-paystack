<?php
namespace mutuasm\commercepaystack\gateways;

use Craft;
use craft\commerce\base\Gateway as BaseGateway;
use craft\commerce\base\RequestResponseInterface;
use craft\commerce\errors\NotImplementedException;
use craft\commerce\errors\PaymentException;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\PaymentSource;
use craft\commerce\models\Transaction;
use craft\helpers\UrlHelper;
use craft\web\Response as WebResponse;
use mutuasm\commercepaystack\models\PaystackPaymentForm;
use mutuasm\commercepaystack\responses\PaystackRequestResponse;

class PaystackGateway extends BaseGateway
{
    public $secretKey;
    public $publicKey;
    public $testMode = false;

    public static function displayName(): string
    {
        return Craft::t('commerce-paystack', 'Paystack');
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('commerce-paystack/gatewaySettings', [
            'gateway' => $this,
        ]);
    }

    public function getPaymentFormHtml(array $params): ?string
    {
        return '';
    }

    public function getPaymentFormModel(): BasePaymentForm
    {
        return new PaystackPaymentForm();
    }

    public function purchase(Transaction $transaction, BasePaymentForm $form): RequestResponseInterface
    {
        $order = $transaction->getOrder();

        $callbackUrl = UrlHelper::actionUrl('commerce/payments/complete-payment', [
            'commerceTransactionHash' => $transaction->hash,
        ]);

        $url = 'https://api.paystack.co/transaction/initialize';
        $fields = [
            'email' => $order->getEmail(),
            'amount' => (int)round($transaction->paymentAmount * 100),
            'currency' => $transaction->paymentCurrency,
            'reference' => $transaction->hash,
            'callback_url' => $callbackUrl,
        ];

        try {
            $client = Craft::createGuzzleClient();
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $fields,
            ]);

            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            $paymentResponse = new PaystackRequestResponse($data);

            if (($data['status'] ?? false) === true) {
                $paymentResponse->setRedirectUrl($data['data']['authorization_url'] ?? '');
                $paymentResponse->setProcessing(true);
                return $paymentResponse;
            }

            throw new PaymentException($data['message'] ?? 'Paystack initialization failed.');
        } catch (\Exception $e) {
            throw new PaymentException($e->getMessage());
        }
    }

    public function completePurchase(Transaction $transaction): RequestResponseInterface
    {
        $reference = Craft::$app->getRequest()->getParam('reference');
        $url = 'https://api.paystack.co/transaction/verify/' . urlencode((string)$reference);

        try {
            $client = Craft::createGuzzleClient();
            $response = $client->get($url, [
                'headers' => ['Authorization' => 'Bearer ' . $this->secretKey],
            ]);

            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            return new PaystackRequestResponse($data);
        } catch (\Exception $e) {
            throw new PaymentException($e->getMessage());
        }
    }

    public function authorize(Transaction $transaction, BasePaymentForm $form): RequestResponseInterface
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function capture(Transaction $transaction, string $reference): RequestResponseInterface
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function completeAuthorize(Transaction $transaction): RequestResponseInterface
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function createPaymentSource(BasePaymentForm $sourceData, int $customerId): PaymentSource
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function deletePaymentSource(string $token): bool
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function refund(Transaction $transaction): RequestResponseInterface
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function processWebHook(): WebResponse
    {
        throw new NotImplementedException(Craft::t('commerce', 'This gateway does not support that functionality.'));
    }

    public function supportsAuthorize(): bool
    {
        return false;
    }

    public function supportsCapture(): bool
    {
        return false;
    }

    public function supportsCompleteAuthorize(): bool
    {
        return false;
    }

    public function supportsCompletePurchase(): bool
    {
        return true;
    }

    public function supportsPaymentSources(): bool
    {
        return false;
    }

    public function supportsPurchase(): bool
    {
        return true;
    }

    public function supportsRefund(): bool
    {
        return false;
    }

    public function supportsPartialRefund(): bool
    {
        return false;
    }

    public function supportsWebhooks(): bool
    {
        return false;
    }
}
