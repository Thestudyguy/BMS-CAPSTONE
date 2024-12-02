<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Services Added</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px 0;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .content {
            padding: 20px;
            color: #333;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .services-table th, .services-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .services-table th {
            background-color: #007BFF;
            color: white;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Services Added to Your Account</h1>
        </div>
        <div class="content">
            <p>Dear {{ $client['CEO'] }},</p>
            <p>We are pleased to inform you that the following service(s) have been successfully added to your account under {{ $client['CompanyName'] }}:</p>
            
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Service Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service['serviceName'] }}</td>
                            <td>{{ number_format($service['servicePrice'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>If you have any questions or need further assistance, feel free to contact us.</p>
            <p>Thank you for choosing RAM Law Firm.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }}. All rights reserved.</p>
            <p>Contact us at RAM.business@gmail.com | (123) 456-7890</p>
        </div>
    </div>
</body>
</html>
