<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password — Cross Rivers Ministries</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
            -webkit-text-size-adjust: 100%;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e6e6e9;
        }
        .header {
            background-color: #0c1230;
            padding: 30px 36px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        .header p {
            color: #75C32C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin: 6px 0 0;
            font-weight: 600;
        }
        .content {
            padding: 36px 36px 30px;
            line-height: 1.65;
            font-size: 15px;
            color: #4e5768;
        }
        .greeting {
            font-size: 17px;
            color: #0c1230;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4054B2;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 3px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .notice {
            background-color: #f8fafc;
            border-left: 3px solid #4054B2;
            padding: 14px 18px;
            font-size: 13px;
            color: #64748b;
            margin: 24px 0;
            border-radius: 2px;
        }
        .url-box {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #eef0f4;
            font-size: 12px;
            color: #94a3b8;
            word-break: break-all;
        }
        .url-box a {
            color: #4054B2;
            text-decoration: underline;
        }
        .footer {
            padding: 20px 36px;
            background-color: #fafbfc;
            border-top: 1px solid #eef0f4;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cross Rivers Ministries</h1>
            <p>Administrator Account Security</p>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $user->name }},</div>

            <p>We received a request to reset the password for your Cross Rivers Ministries administrator account (<strong>{{ $user->email }}</strong>).</p>

            <p>Click the button below to choose a new password:</p>

            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn" target="_blank">Reset Password</a>
            </div>

            <div class="notice">
                This password reset link is valid for <strong>60 minutes</strong>. If you did not request a password reset, you can safely ignore this email—your password will remain unchanged.
            </div>

            <div class="url-box">
                If the button above doesn't work, copy and paste this link into your web browser:<br>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Cross Rivers Ministries &middot; Secure Admin Authentication
        </div>
    </div>
</body>
</html>
