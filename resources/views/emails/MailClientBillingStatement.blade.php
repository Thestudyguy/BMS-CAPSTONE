<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .billing-title {
            color: #063D58;
            font-size: 24px;
            font-weight: bold;
        }
        .contact-info {
            font-size: 14px;
            color: #333333;
        }
        .billing-info {
            background: #eff7fe;
            padding: 10px;
            margin: 10px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            text-align: right;
            padding-top: 10px;
        }
        .footer {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/Rams_logo.png') }}" alt="RAM'S ACCOUNTING FIRM" width="130">
            <div class="billing-title">RAM'S ACCOUNTING FIRM</div>
        </div>
        <div class="contact-info">
            @foreach ($systemProfile as $sp)
                <div><i class="fas fa-phone"></i> {{$sp->PhoneNumber}} | <i class="fas fa-envelope"></i> {{$sp->Email}} | <i class="fas fa-map-marker"></i> {{$sp->Address}}</div>
            @endforeach
        </div>
        <div class="billing-info">
            Billed to: <strong>{{$client->CEO}} - {{$client->CompanyName}}</strong><br>
            <span>Date: {{$currentDate}}</span><br>
            <span>Billing ID: #000-001</span>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Services</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp <!-- Initialize total price -->
                @if(isset($result[$clientId]['Service']) && count($result[$clientId]['Service']) > 0)
                    @foreach($result[$clientId]['Service'] as $serviceName => $serviceData)
                        <tr>
                            <td><strong>{{ $serviceName }}</strong></td>
                        </tr>
                        @if(count($serviceData['sub_service']) > 0)
                            @foreach($serviceData['sub_service'] as $subServiceName => $subServiceData)
                                <tr>
                                    <td><strong>{{ $subServiceName }}</strong></td>
                                </tr>
                                @if(count($subServiceData['account_descriptions']) > 0)
                                    @foreach($subServiceData['account_descriptions'] as $accountDescription)
                                        <tr>
                                            <td>{{ $accountDescription['Category'] }} - {{ $accountDescription['Description'] }}: ₱{{ number_format($accountDescription['Price'], 2) }}</td>
                                        </tr>
                                        @php $totalPrice += $accountDescription['Price']; @endphp <!-- Add price to total -->
                                    @endforeach
                                @else
                                    <tr>
                                        <td>No account descriptions available.</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td>No sub-services available.</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td>No services available for this client.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="total">Overall Total: ₱<span>{{ number_format($totalPrice, 2) }}</span></div>
        <div class="footer">
            Prepared By: <strong>THERESA B. ELLOREN, LPT</strong><br>
            Collected By: <strong>REYMAR A. VILLEGAS</strong><br>
            Approved By: <strong>RYAN T. RAMAL</strong><br>
            RAM'S ACCOUNTING FIRM<br>
            <span class="text-danger">Proprietor</span><br>
            345-980-034-000<br>
            Purok Narra, Briz District,<br>
            Magugpo East Tagum City
        </div>
    </div>
</body>
</html>
