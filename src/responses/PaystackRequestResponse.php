<?php
namespace mutuasm\commercepaystack\responses;

use craft\commerce\base\RequestResponseInterface;

class PaystackRequestResponse implements RequestResponseInterface
{
    private array $data;
    private string $redirectUrl = '';
    private bool $processing = false;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function setRedirectUrl(string $url): void
    {
        $this->redirectUrl = $url;
    }

    public function setProcessing(bool $processing): void
    {
        $this->processing = $processing;
    }

    public function isSuccessful(): bool
    {
        if (isset($this->data['data']['status'])) {
            return $this->data['data']['status'] === 'success';
        }

        return (bool)($this->data['status'] ?? false);
    }

    public function isProcessing(): bool
    {
        return $this->processing;
    }

    public function isRedirect(): bool
    {
        return $this->redirectUrl !== '';
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): array
    {
        return [];
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getTransactionReference(): string
    {
        return (string)($this->data['data']['reference'] ?? $this->data['reference'] ?? '');
    }

    public function getCode(): string
    {
        return (string)($this->data['data']['gateway_response'] ?? '');
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return (string)($this->data['message'] ?? '');
    }

    public function redirect(): void
    {
    }
}
