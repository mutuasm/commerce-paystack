# Paystack for Craft Commerce

A Paystack payment gateway plugin for [Craft Commerce](https://craftcms.com/commerce).

## Requirements

- Craft CMS 4.0.0 or 5.0.0+
- Craft Commerce 4.0.0 or 5.0.0+
- PHP 8.0.2+
- A [Paystack](https://paystack.com/) account with API keys

## Installation

### Via Composer

From your Craft project root, run:

```bash
composer require mutuasm/commerce-paystack
```

Then install the plugin via the Craft CLI:

```bash
php craft plugin/install commerce-paystack
```

Alternatively, you can install it from the Craft Control Panel under **Settings → Plugins**.

### Via the Plugin Store

1. In the Craft Control Panel, go to **Settings → Plugins**.
2. Search for "Paystack for Craft Commerce".
3. Click **Install**.

## Configuration

1. In the Craft Control Panel, go to **Commerce → System Settings → Gateways**.
2. Click **New gateway**.
3. Set the **Gateway type** to **Paystack**.
4. Enter a **Name** and **Handle** for the gateway.
5. Fill in your Paystack API credentials:
   - **Public Key** — found in your Paystack dashboard under Settings → API Keys & Webhooks.
   - **Secret Key** — found in the same place.
6. Save the gateway.

It is recommended to store your API keys in your `.env` file and reference them using Craft's environment variable syntax (e.g. `$PAYSTACK_PUBLIC_KEY`).

```env
PAYSTACK_PUBLIC_KEY="pk_test_xxxxxxxxxxxx"
PAYSTACK_SECRET_KEY="sk_test_xxxxxxxxxxxx"
```

## License

MIT
