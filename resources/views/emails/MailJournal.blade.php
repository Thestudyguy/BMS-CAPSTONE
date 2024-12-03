<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header, footer {
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
        }
        header {
            font-size: 1.5rem;
            font-weight: bold;
        }
        footer {
            font-size: 0.9rem;
            margin-top: auto;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto;
            width: 90%;
            max-width: 500px;
        }
        .message {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background: #3498db;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }
        .button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        RAM LAW FIRM
    </header>
    <div class="container">
        <div class="message">Your Journal Entry has been {{$status}}</div>
    </div>
    <footer>
        &copy; 2024 RAM LAW FIRM. All rights reserved. <br>
        Contact us at <a href="mailto:support@yourcompany.com" style="color: #fff; text-decoration: underline;">RAM@gmail.com</a>
    </footer>
</body>
</html>
