<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="salesReport">
        <div class="text-center mb-4" style="display: flex; flex-direction: column; align-items: center;">
            <img src="{{ asset('storage/SYNKRONE.svg') }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
            <h2 class="mt-2">Sales Report</h2>
            <p>
                Period:
                {{ $startDate ? $startDate : '—' }}
                to
                {{ $endDate ? $endDate : '—' }}
            </p>
        </div>

        <table class="table table-bordered text-white align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
            </thead>
            <tbody>
                @foreach ($sales as $row)
                    <tr>
                        <td>{{ $row->order_id }}</td>
                        <td>{{ $row->order_date }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>${{ number_format($row->price, 2) }}</td>
                        <td>${{ number_format($row->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3 text-end">
            <h5>Total Sales: ${{ number_format($totalSales, 2) }}</h5>
        </div>

        <div class="mt-5">
            <p>Prepared by:</p>
            <p style="margin-top: 60px;">_______________________</p>
            <p><strong>{{ session('username') }}</strong><br>Manager</p>
        </div>
    </div>

    <style>
        body {
            background: white !important;
            color: black !important;
            font-family: Arial, sans-serif;
        }

        nav,
        header,
        footer,
        .no-print,
        .sidebar {
            display: none !important;
        }

        #salesReport {
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            color: black !important;
        }

        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            color: black !important;
        }

        h2,
        h5 {
            margin: 0;
            color: black !important;
        }

        p {
            color: black !important;
        }

        img {
            display: block;
            margin: 0 auto 10px auto;
        }
    </style>
</body>

</html>
