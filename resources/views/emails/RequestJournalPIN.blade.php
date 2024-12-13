<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Access Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 26px;
            color: #4a90e2;
            font-family: 'Georgia', serif;
            margin: 0;
        }
        .content p {
            font-size: 16px;
            margin: 15px 0;
            color: #555;
        }
        .highlight {
            font-weight: bold;
            color: #333;
        }
        .important {
            color: #4a90e2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Journal Access Request</h1>
        </div>
        <div class="content">
            <p>Hi <span class="highlight">Accountant</span>,</p>
            <p>The bookkeeper <span class="highlight important">{{ $requestorFN }} {{ $requestorLN }}</span> is seeking your PIN to access the journal file of the following client:</p>
            {{-- <p><strong>Client:</strong> <span class="highlight important">{{ $client->CompanyName }}</span></p> --}}
            <p><strong>CEO:</strong> <span class="highlight">{{ $client->CEO }}</span></p>
            <p><strong>Journal ID:</strong> <span class="highlight">{{ $journal->journal_id }}</span></p>
            <p>Please provide your PIN to assist in securely generating or downloading this journal file.</p>
            <p>Thank you for your support!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} RAM Law Firm. All rights reserved. <br>
            Powered by <span class="important">BMS</span>
        </div>
    </div>
</body>
</html>
