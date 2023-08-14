<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #3490dc;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #888;
        }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h2>Reset Your Password</h2>
            </div>
            <div class="content">
                <p>Hello,</p>
                <p>We received a request to reset your password. Click the button below to set a new password:</p>
                <a href="{{ url('api/v1/password/reset-password-form', $token) }}" class="button">Reset
                    Password</a>
                <p>If you didn't request a password reset, please ignore this email or contact support at
                    gamepawbuddy@gmail.com if you have any
                    concerns.</p>
            </div>
            <div class="footer">
                <p>Thank you,<br>Gamepawbuddy support Team</p>
            </div>
        </div>
    </body>

</html>