<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>سند صرف - {{ $transaction->supplier->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
            text-align: right;
            background: #fff;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            border: 2px solid #000;
            padding: 30px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .value {
            border-bottom: 1px dotted #000;
            padding-bottom: 5px;
            display: inline-block;
            min-width: 200px;
        }

        .amount-box {
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            background: #f9f9f9;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .signature {
            border-top: 1px solid #000;
            width: 200px;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
            .container {
                border: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">طباعة</button>
    </div>

    <div class="container">
        <div class="header">
            <img src="{{ asset('uploads/settings/logo.png') }}" class="logo" alt="Logo">

            <div class="title">سند صرف (Payment Voucher)</div>
            <div>التاريخ: {{ $transaction->created_at->format('Y-m-d') }}</div>
            <div>رقم السند: {{ $transaction->id }}</div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="label">صرفنا للسيد:</span>
                <span class="value">{{ $transaction->supplier->name }}</span>
            </div>
            <div class="info-item">
                <span class="label">طريقة الدفع:</span>
                <span class="value">نقداً ({{ $transaction->safe->name ?? 'غير محدد' }})</span>
            </div>
        </div>

        <div class="amount-box">
             المبلغ: {{ number_format($transaction->amount, 2) }} د.ل
        </div>

        <div class="info-item">
            <span class="label">وذلك عن:</span>
            <span class="value" style="width: 80%;">{{ $transaction->description }}</span>
        </div>

        <div class="footer">
            <div>
                <div class="signature">التوقيع</div>
            </div>
            <div>
                <div class="signature">المحاسب</div>
            </div>
        </div>
    </div>
</body>

</html>
