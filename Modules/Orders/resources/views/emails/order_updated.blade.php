<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('orders::t.mail.title') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            padding: 20px 0;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            background: #007bff;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .order-details,
        .items,
        .shipping {
            margin-bottom: 20px;
        }

        .order-details h2,
        .items h2,
        .shipping h2 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #f8f9fa;
            color: #333;
        }

        .total {
            font-weight: bold;
            font-size: 16px;
            text-align: right;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }

        .status {
            text-transform: capitalize;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ frontUrl('logo.png') }}" alt="{{ config('app.name') }} Logo">
        </div>
        <div class="header">
            <h1>{{ __('orders::t.mail.header') }}</h1>
        </div>
        <div class="content">
            <div class="order-details">
                <h2>{{ __('orders::t.mail.order_details') }} #{{ $order->id }}</h2>
                <p>{{ __('orders::t.mail.title_message') }}</p>
                <p>{{ __('orders::t.mail.thank_you') }}</p>
                <p><strong>{{ __('orders::t.mail.order_status') }}:</strong> <span
                        class="status">{{ $order->status->localized() }}</span></p>
                <p><strong>{{ __('orders::t.mail.order_date') }}:</strong>
                    {{ $order->created_at->format('F j, Y, g:i a') }}</p>
            </div>

            <div class="items">
                <h2>{{ __('orders::t.mail.order_items') }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('orders::t.mail.product') }}</th>
                            <th>{{ __('orders::t.mail.quantity') }}</th>
                            <th>{{ __('orders::t.mail.price') }}</th>
                            <th>{{ __('orders::t.mail.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="{{ $item->product->images[0] ?? '' }}"
                                                        alt="Product Image" style="max-width: 50px;" />
                                                </td>
                                                <td>{{ $item->product->title }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->totals->subtotal, 2) }} EGP</td>
                                <td>{{ number_format($item->totals->total, 2) }} EGP</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="total">{{ __('orders::t.mail.grand_total') }}:
                    {{ number_format($order->totals->total, 2) }} EGP</p>
                <p class="total">{{ __('orders::t.mail.payment_method') }}: {{ $order->paymentMethod }}
                </p>
            </div>

            <div class="shipping">
                <h2>{{ __('orders::t.mail.shipping_address') }}</h2>
                <p>{{ $order->shippingAddress->name }}</p>
                <p>{{ $order->shippingAddress->address }}</p>
                <p>{{ $order->shippingAddress->city->title }}</p>
                <p>{{ $order->shippingAddress->country ?? '' }}</p>
                <p>{{ $order->shippingAddress->phone }}</p>
            </div>

            <p>{{ __('orders::t.mail.contact_us') }} <a
                    href="{{ settings('contact')?->email }}">{{ __('orders::t.mail.support_email') }}</a>.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. {{ __('orders::t.mail.all_rights_reserved') }}</p>
        </div>
    </div>
</body>

</html>
