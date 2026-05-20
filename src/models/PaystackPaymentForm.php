<?php
namespace mutuasm\commercepaystack\models;

use craft\commerce\models\payments\BasePaymentForm;

class PaystackPaymentForm extends BasePaymentForm
{
    public $reference;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['reference'], 'string'],
        ]);
    }
}
