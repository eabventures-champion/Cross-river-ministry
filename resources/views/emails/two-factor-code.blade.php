<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Code</title>
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
            max-width: 540px;
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
        .code-box {
            margin: 28px 0;
            padding: 20px;
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 6px;
            text-align: center;
        }
        .code-number {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 0.25em;
            color: #4054B2;
            padding-left: 0.25em;
        }
        .notice {
            font-size: 13px;
            color: #64748b;
            margin-top: 10px;
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
            <p>Admin Security Verification</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>A sign-in attempt was initiated for your Cross Rivers Ministries Administrator account.</p>

            <p>Please enter the following verification code to complete your login:</p>

            <div class="code-box">
                <div class="code-number">{{ $code }}</div>
                <div class="notice">This code is valid for <strong>10 minutes</strong>.</div>
            </div>

            <p style="font-size: 13px; color: #64748b;">
                If you did not request this login code, someone may be trying to access your administrator account. Please immediately review your password security.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Cross Rivers Ministries &middot; Secure Admin Authentication
        </div>
    </div>
</body>
</html>
