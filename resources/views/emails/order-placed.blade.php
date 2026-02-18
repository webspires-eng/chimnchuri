<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - #{{ $order->order_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #f8fafc;
            font-family: 'Instrument Sans', -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 20px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .content {
            padding: 20px;
        }

        h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px;
            color: #0f172a;
            text-align: center;
        }

        p {
            font-size: 14px;
            line-height: 1.5;
            margin: 0 0 15px;
            color: #475569;
        }

        .order-meta {
            background-color: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .order-meta td {
            font-size: 12px;
            color: #64748b;
        }

        .order-meta b {
            color: #0f172a;
        }

        .item-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .item-table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            border-bottom: 1px solid #f1f5f9;
            padding: 8px 0;
        }

        .item-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            vertical-align: top;
        }

        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .item-desc {
            font-size: 12px;
            color: #64748b;
            margin: 2px 0 0;
        }

        .addon-list {
            font-size: 11px;
            color: #64748b;
            margin: 5px 0 0;
            padding-left: 10px;
            border-left: 2px solid #e2e8f0;
        }

        .price {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            text-align: right;
        }

        .summary-table {
            width: 100%;
            margin-top: 10px;
            border-top: 2px solid #f1f5f9;
            padding-top: 10px;
        }

        .summary-table td {
            font-size: 13px;
            color: #64748b;
            padding: 4px 0;
        }

        .summary-table .total {
            font-size: 16px;
            font-weight: 700;
            color: #396430;
        }

        .address-box {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
            background-color: #f8fafc;
            padding: 12px;
            border-radius: 8px;
        }

        .btn {
            background-color: #396430;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <center>
            <table class="main" width="100%">
                <tr>
                    <td class="header">
                        <img src="{{ $message->embed(public_path('admin/assets/images/chimnchurri.png')) }}"
                            height="80" alt="{{ config('app.name') }}">
                    </td>
                </tr>
                <tr>
                    <td class="content">
                        <h1>Thank you for your order!</h1>
                        <p>Hi {{ $order->customer_name }}, your order has been received and is being processed.</p>

                        <table class="order-meta" width="100%">
                            <tr>
                                <td>Order: <b>#{{ $order->order_number }}</b></td>
                                <td align="right">Date: <b>{{ $order->created_at->format('M d, Y') }}</b></td>
                            </tr>
                            <tr>
                                <td>Payment: <b>{{ ucfirst($order->payment_status) }}</b></td>
                                <td align="right">Method: <b>{{ strtoupper($order->payment_method) }}</b></td>
                            </tr>
                            <tr>
                                <td>Type: <b>{{ ucfirst($order->order_type ?? 'Delivery') }}</b></td>
                                <td align="right">Time: <b>{{ $order->time_slot ?? 'As soon as possible' }}</b></td>
                            </tr>
                        </table>

                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th align="center" width="40">Qty</th>
                                    <th align="right" width="70">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <p class="item-name">{{ $item->item_name }}</p>
                                            @if ($item->size_name)
                                                <p class="item-desc">Size: {{ $item->size_name }}</p>
                                            @endif
                                            @if ($item->addons->count() > 0)
                                                <div class="addon-list">
                                                    @foreach ($item->addons as $addon)
                                                        <div>+ {{ $addon->name }} (x{{ $addon->quantity }})</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td align="center" style="font-size: 13px; color: #475569;">
                                            {{ $item->quantity }}</td>
                                        <td class="price">£{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <table class="summary-table">
                            <tr>
                                <td>Subtotal</td>
                                <td align="right">£{{ number_format($order->sub_total, 2) }}</td>
                            </tr>
                            @if ($order->discount_total > 0)
                                <tr>
                                    <td>Discount</td>
                                    <td align="right">-£{{ number_format($order->discount_total, 2) }}</td>
                                </tr>
                            @endif
                            @if ($order->delivery_charges > 0)
                                <tr>
                                    <td>Delivery Fee</td>
                                    <td align="right">£{{ number_format($order->delivery_charges, 2) }}</td>
                                </tr>
                            @endif
                            @if ($order->tax_total > 0)
                                <tr>
                                    <td>Tax</td>
                                    <td align="right">£{{ number_format($order->tax_total, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="total">Total</td>
                                <td align="right" class="total">£{{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        </table>

                        <div style="margin-top: 20px;">
                            <p style="font-weight: 700; color: #0f172a; margin-bottom: 8px;">Delivery Address</p>
                            <div class="address-box">
                                {{ $order->customer_name }}<br>
                                {{ $order->customer_phone }}<br>
                                {{ $order->delivery_address }}
                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <a href="{{ config('app.frontend_url') }}/orders/{{ $order->id }}" class="btn">View
                                Order
                                Details</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="footer">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p>Chimnchurri - Steak, Sides, Sauce.</p>
                    </td>
                </tr>
            </table>
        </center>
    </div>
</body>

</html>
