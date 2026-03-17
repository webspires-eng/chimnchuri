<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders for {{ $printDate }}</title>
    <style>
        /* ========== BASE ========== */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: #1a1a1a;
            background: #f5f5f5;
            padding: 20px;
        }

        /* ========== SCREEN-ONLY TOOLBAR ========== */
        .toolbar {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar h1 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .toolbar .badge {
            background: #396430;
            color: #fff;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 600;
        }

        .btn-print {
            background: #396430;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 18px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-print:hover {
            background: #2d5226;
        }

        .btn-back {
            background: #f0f0f0;
            color: #555;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #e0e0e0;
        }

        /* ========== PRINT DOCUMENT ========== */
        .print-doc {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
            padding: 28px 32px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ---- Document Header ---- */
        .doc-header {
            border-bottom: 3px solid #396430;
            padding-bottom: 16px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .doc-header .biz-name {
            font-size: 18px;
            font-weight: 800;
            color: #396430;
            letter-spacing: -0.5px;
        }

        .doc-header .doc-title {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .doc-header .doc-meta {
            text-align: right;
            color: #555;
            font-size: 9px;
            line-height: 1.6;
        }

        .doc-header .doc-date {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
        }

        /* ---- Summary Bar ---- */
        .summary-bar {
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 10px 18px;
            margin-bottom: 22px;
            display: flex;
            gap: 32px;
        }

        .summary-bar .stat label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            display: block;
        }

        .summary-bar .stat span {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
        }

        /* ---- No orders ---- */
        .no-orders {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }

        .no-orders svg {
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .no-orders p {
            font-size: 11px;
        }

        /* ---- Order Card ---- */
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 18px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .order-card-header {
            background: #396430;
            color: #fff;
            padding: 8px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-card-header .order-num {
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.3px;
        }

        .order-card-header .order-meta {
            font-size: 9px;
            opacity: 0.85;
            display: flex;
            gap: 10px;
        }

        .order-card-header .tag {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .tag-paid {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        .tag-unpaid {
            background: rgba(255, 100, 100, 0.5) !important;
        }

        .tag-cod {
            background: rgba(255, 200, 0, 0.35) !important;
        }

        .order-card-body {
            padding: 12px 14px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 18px;
        }

        /* ---- Sections inside card ---- */
        .section-title {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #396430;
            margin-bottom: 3px;
            padding-bottom: 2px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 90px 1fr;
            row-gap: 3px;
        }

        .info-grid .lbl {
            color: #888;
            font-size: 9px;
        }

        .info-grid .val {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 9px;
        }

        /* ---- Items table ---- */
        .items-section {
            grid-column: 1 / -1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .items-table thead th {
            background: #f0f0f0;
            padding: 4px 6px;
            text-align: left;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #555;
        }

        .items-table thead th:last-child {
            text-align: right;
        }

        .items-table tbody td {
            padding: 3px 6px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table .qty {
            text-align: center;
        }

        .items-table .price {
            text-align: right;
        }

        .items-table .addon-row td {
            padding: 1px 6px 1px 14px;
            color: #777;
            font-size: 8px;
            border-bottom: none;
            font-style: italic;
        }

        /* ---- Totals row ---- */
        .totals-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 2px solid #396430;
        }

        .totals-row .total-label {
            margin-right: 10px;
            color: #555;
            font-size: 9px;
        }

        .totals-row .total-amount {
            font-weight: 800;
            font-size: 11px;
            color: #396430;
        }

        /* ---- Time slots ---- */
        .timeslots-section .slots-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 4px;
        }

        .slot-pill {
            background: #eaf4e7;
            color: #396430;
            border: 1px solid #c3dfc0;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 600;
        }

        /* ---- Instruction ---- */
        .instruction-box {
            grid-column: 1 / -1;
            background: #fffbf0;
            border: 1px solid #f0e5c0;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 9px;
            color: #7a5c00;
            font-style: italic;
        }

        .instruction-box strong {
            font-style: normal;
        }

        /* ---- Grand total footer ---- */
        .grand-total-bar {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 3px double #396430;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
        }

        .grand-total-bar .gt-label {
            font-size: 11px;
            color: #555;
        }

        .grand-total-bar .gt-amount {
            font-size: 16px;
            font-weight: 800;
            color: #396430;
        }

        /* ---- Page footer ---- */
        .page-footer {
            margin-top: 18px;
            text-align: center;
            color: #bbb;
            font-size: 10px;
        }

        /* ========== PRINT OVERRIDES ========== */
        @media print {
            body {
                background: #fff;
                padding: 0;
                font-size: 9px;
            }

            .toolbar {
                display: none !important;
            }

            .print-doc {
                box-shadow: none;
                border-radius: 0;
                padding: 16px;
                max-width: 100%;
            }

            .order-card {
                page-break-inside: avoid;
            }

            @page {
                margin: 12mm 14mm;
                size: A4;
            }
        }
    </style>
</head>

<body>

    {{-- ============ SCREEN TOOLBAR ============ --}}
    <div class="toolbar" style="@media print { display: none; }">
        <div class="toolbar-left">
            <a href="{{ route('admin.orders') }}" class="btn-back">
                ← Back to Orders
            </a>
            <h1>Day Sheet</h1>
            <span class="badge">{{ $printDate }}</span>
            <span class="badge" style="background:#555;">{{ $orders->count() }}
                {{ Str::plural('Order', $orders->count()) }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <form action="{{ route('admin.orders.print') }}" method="GET"
                style="display:flex;align-items:center;gap:8px;">
                <label style="font-size:11px;font-weight:600;color:#555;white-space:nowrap;">📅 Change Date:</label>
                <input type="date" name="date" value="{{ $date }}"
                    style="border:1px solid #ddd;border-radius:6px;padding:5px 10px;font-size:11px;color:#1a1a1a;outline:none;"
                    required>
                <button type="submit"
                    style="background:#396430;color:#fff;border:none;border-radius:6px;padding:6px 14px;font-size:11px;font-weight:600;cursor:pointer;">
                    Go
                </button>
            </form>
            <button onclick="window.print()" class="btn-print">
                🖨 Print / Save PDF
            </button>
        </div>
    </div>

    {{-- ============ PRINTABLE DOCUMENT ============ --}}
    <div class="print-doc" id="printArea">

        {{-- --- Header --- --}}
        <div class="doc-header">
            <div>
                <div class="biz-name">Chimnchuri</div>
                <div class="doc-title">Daily Order Sheet</div>
            </div>
            <div class="doc-meta">
                <div class="doc-date">{{ $printDate }}</div>
                <div>Printed: {{ now()->format('d M Y, h:i A') }}</div>
                <div>Total Orders: <strong>{{ $orders->count() }}</strong></div>
            </div>
        </div>

        @if ($orders->isEmpty())
            {{-- ---- No Orders ---- --}}
            <div class="no-orders">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#396430"
                    stroke-width="1.5">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p>No orders found for <strong>{{ $printDate }}</strong></p>
            </div>
        @else
            {{-- ---- Summary Bar ---- --}}
            <div class="summary-bar">
                <div class="stat">
                    <label>Total Orders</label>
                    <span>{{ $orders->count() }}</span>
                </div>
                <div class="stat">
                    <label>Delivery</label>
                    <span>{{ $orders->where('order_type', 'delivery')->count() }}</span>
                </div>
                <div class="stat">
                    <label>Collection</label>
                    <span>{{ $orders->where('order_type', 'collection')->count() }}</span>
                </div>
                <div class="stat">
                    <label>Total Revenue</label>
                    <span>£{{ number_format($orders->sum('grand_total'), 2) }}</span>
                </div>
                <div class="stat">
                    <label>Paid Online</label>
                    <span>{{ $orders->where('payment_method', 'online')->where('payment_status', 'paid')->count() }}</span>
                </div>
                <div class="stat">
                    <label>Pay on Collect/Del</label>
                    <span>{{ $orders->where('payment_method', 'cod')->count() }}</span>
                </div>
            </div>

            {{-- ---- Orders ---- --}}
            @foreach ($orders as $index => $order)
                <div class="order-card">

                    {{-- Card Header --}}
                    <div class="order-card-header">
                        <div class="order-num">
                            #{{ $order->order_number }} — {{ $order->customer_name }}
                        </div>
                        <div class="order-meta">
                            <span>{{ $order->created_at->format('h:i A') }}</span>
                            <span class="tag {{ $order->order_type == 'delivery' ? '' : '' }} text-capitalize">
                                {{ ucfirst($order->order_type) }}
                            </span>
                            @if ($order->payment_method == 'cod')
                                <span class="tag tag-cod">Pay on
                                    {{ $order->order_type == 'collection' ? 'Collection' : 'Delivery' }}</span>
                            @elseif($order->payment_status == 'paid')
                                <span class="tag tag-paid">Paid Online</span>
                            @else
                                <span class="tag tag-unpaid">Unpaid</span>
                            @endif
                            <span class="tag text-capitalize">{{ ucfirst($order->order_status) }}</span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="order-card-body">

                        {{-- Customer Info --}}
                        <div class="customer-section">
                            <div class="section-title">Customer</div>
                            <div class="info-grid">
                                <span class="lbl">Name</span>
                                <span class="val">{{ $order->customer_name }}</span>
                                <span class="lbl">Phone</span>
                                <span class="val">{{ $order->customer_phone ?? '—' }}</span>
                                <span class="lbl">Email</span>
                                <span class="val">{{ $order->customer_email ?? '—' }}</span>
                                @if ($order->order_type == 'delivery' && $order->delivery_address)
                                    <span class="lbl">Address</span>
                                    <span
                                        class="val">{{ $order->delivery_address }}{{ $order->city ? ', ' . $order->city : '' }}{{ $order->postal_code ? ' ' . $order->postal_code : '' }}</span>
                                @endif
                                @if ($order->order_type == 'collection' && $order->car_registration)
                                    <span class="lbl">Car Reg</span>
                                    <span class="val"
                                        style="text-transform:uppercase;letter-spacing:1px;font-size:9px;">{{ $order->car_registration }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Time Slots --}}
                        <div class="timeslots-section">
                            <div class="section-title">Time Slot(s)</div>
                            <div class="slots-wrap">
                                @forelse($order->time_slots as $slot)
                                    <span class="slot-pill">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                        <span style="font-weight:700;">({{ $slot->capacity ?? 0 }} qty)</span>
                                    </span>
                                @empty
                                    <span style="color:#aaa;font-size:11px;">No time slot assigned</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="items-section">
                            <div class="section-title">Items Ordered</div>
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Size</th>
                                        <th class="qty">Qty</th>
                                        <th class="price">Unit Price</th>
                                        <th class="price">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $item)
                                        <tr>
                                            <td><strong>{{ $item->item_name }}</strong></td>
                                            <td>{{ $item->size_name ?? '—' }}</td>
                                            <td class="qty">{{ $item->quantity }}</td>
                                            <td class="price">£{{ number_format($item->price, 2) }}</td>
                                            <td class="price">£{{ number_format($item->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                        @foreach ($item->addons->groupBy('category_name') as $catName => $addons)
                                            <tr class="addon-row">
                                                <td colspan="4" style="padding-top:4px;">
                                                    <strong style="color:#1a1a1a;font-style:normal;font-size:8.5px;">{{ $catName }}</strong>
                                                </td>
                                                <td></td>
                                            </tr>
                                            @foreach ($addons as $addon)
                                                <tr class="addon-row">
                                                    <td colspan="4" style="padding-left:20px;">
                                                        + {{ $addon->name }} ({{ $addon->quantity }} x £{{ number_format($addon->price, 2) }})
                                                    </td>
                                                    <td class="price">£{{ number_format($addon->price * $addon->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="5" style="color:#aaa;text-align:center;padding:10px;">No
                                                items</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Totals --}}
                            <div class="totals-row">
                                @if ($order->discount_total > 0)
                                    <span class="total-label" style="font-size:11px;">Subtotal:
                                        £{{ number_format($order->sub_total, 2) }} &nbsp;|&nbsp; Discount:
                                        -£{{ number_format($order->discount_total, 2) }}</span>
                                @endif
                                @if ($order->delivery_charges > 0)
                                    <span class="total-label" style="font-size:11px;">Delivery:
                                        £{{ number_format($order->delivery_charges, 2) }} &nbsp;|&nbsp;</span>
                                @endif
                                <span class="total-label">Total:</span>
                                <span class="total-amount">£{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>

                        {{-- Instructions (if any) --}}
                        @if ($order->order_instruction)
                            <div class="instruction-box">
                                <strong>⚠ Customer Note:</strong> {{ $order->order_instruction }}
                            </div>
                        @endif

                    </div>{{-- end card-body --}}
                </div>{{-- end order-card --}}
            @endforeach

            {{-- ---- Grand Total Footer ---- --}}
            <div class="grand-total-bar">
                <span class="gt-label">Grand Total for {{ $printDate }}:</span>
                <span class="gt-amount">£{{ number_format($orders->sum('grand_total'), 2) }}</span>
            </div>

        @endif

        <div class="page-footer">
            Chimnchuri Admin · Daily Order Sheet · Generated {{ now()->format('d M Y, h:i A') }}
        </div>

    </div>{{-- end print-doc --}}

</body>

</html>
