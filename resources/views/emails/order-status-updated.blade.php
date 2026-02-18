<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated - #{{ $order->order_number }}</title>
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

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            margin: 10px 0;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background-color: #e0f2fe;
            color: #075985;
        }

        .status-processing {
            background-color: #e0f2fe;
            color: #075985;
        }

        .status-dispatched {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .order-meta {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
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
            margin-top: 20px;
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
                    <td class="content ">
                        <h1>Order Status Update</h1>
                        <p style="text-align: center;">Hi {{ $order->customer_name }}, the status of your order
                            <b>#{{ $order->order_number }}</b>
                            has been updated.
                        </p>

                        <div style="text-align: center;">
                            <p style="font-size: 12px; color: #64748b; margin-bottom: 5px;">New Status:</p>
                            <span class="status-badge status-{{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>

                        <div class="order-meta" style="margin-top: 20px;">
                            <p style="margin-bottom: 10px; font-weight: 600; color: #1e293b;">Order Summary</p>
                            <table width="100%" style="font-size: 13px; color: #64748b;">
                                <tr>
                                    <td>Items:</td>
                                    <td align="right">{{ $order->items->sum('quantity') }} items</td>
                                </tr>
                                <tr>
                                    <td>Payment Method:</td>
                                    <td align="right">{{ strtoupper($order->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td>Grand Total:</td>
                                    <td align="right"><b>£{{ number_format($order->grand_total, 2) }}</b></td>
                                </tr>
                            </table>
                        </div>

                        <div style="text-align: center; margin-top: 10px;">
                            <a href="{{ config('app.frontend_url') }}/orders/{{ $order->id }}" class="btn">Track
                                Order
                                Progress</a>
                        </div>

                        <p style="margin-top: 30px; font-size: 13px; color: #94a3b8; text-align: center;">
                            If you have any questions about your order, please contact our support team.
                        </p>
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
