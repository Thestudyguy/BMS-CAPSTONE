{{-- resources/views/emails/client_services.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h3>Hello {{ $clientName }},</h3>
    <p>This is your list of services. Would you like to proceed with all the services listed below? Please reach out to us if you want to continue with these services.</p>
    
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>${{ $service->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total: ${{ $totalPrice }}</h4>
    <p>Thank you!</p>
</body>
</html>
