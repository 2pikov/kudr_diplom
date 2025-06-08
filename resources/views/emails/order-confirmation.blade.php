<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение заказа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Подтверждение заказа</h1>
        </div>

        <p>Здравствуйте!</p>
        
        <p>Для подтверждения вашего заказа, пожалуйста, введите следующий код на странице подтверждения:</p>
        
        <div class="code">
            {{ $code }}
        </div>

        <p>Код действителен в течение 24 часов.</p>

        <p>Если вы не оформляли заказ, проигнорируйте это письмо.</p>

        <div class="footer">
            <p>Это письмо сформировано автоматически, пожалуйста, не отвечайте на него.</p>
        </div>
    </div>
</body>
</html> 