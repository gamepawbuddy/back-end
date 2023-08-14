<!DOCTYPE html>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
        /* Add your custom email styling here */
        /* ... (other styles) ... */
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Welcome to Gamepawbuddy</h1>
            </div>
            <div class="content">
                <p>Hello,</p>
                <p>Thank you for joining us! We're excited to have you as part of our community.</p>
                <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
                <p>
                    To get started, you can log in to your account using the button below:
                </p>
                <p>
                    <a class="button" href="{{ url('/login') }}">Log In</a>
                </p>
            </div>
            <div class="footer">
                <p>&copy; 2023 Our Website. All rights reserved.</p>
            </div>
        </div>
    </body>

</html>