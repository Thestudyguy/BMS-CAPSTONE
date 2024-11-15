<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement</title>
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        Hey user {{ $requestorFN }}, {{ $requestorLN }} <br>
        Want to print client {{ $client->CompanyName }} - {{ $client->CEO }} <br>
        Journal {{ $journal->journal_id }}
    </div>
</body>
</html>
