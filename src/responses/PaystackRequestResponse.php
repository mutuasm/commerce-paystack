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


    public function isSuccessful(): bool
    {
        // Paystack returns top-level status=true for BOTH initialize and verify.
        // We must check data.status === 'success' to know if MONEY was actually received.
        $status = $this->data['data']['status'] ?? null;
        return ($this->data['status'] ?? false) === true && $status === 'success';
    }

    public function isProcessing(): bool
    {
        // During purchase() initialization, there's no data.status yet, or it's pending
        $status = $this->data['data']['status'] ?? null;
        return ($this->data['status'] ?? false) === true && $status !== 'success';
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
