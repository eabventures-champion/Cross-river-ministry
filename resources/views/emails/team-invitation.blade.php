<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to Cross Rivers Ministries</title>
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
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e6e6e9;
        }
        .header {
            background-color: #0c1230;
            padding: 32px 36px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 600;
            margin: 14px 0 0;
            letter-spacing: -0.02em;
        }
        .header p {
            color: #75C32C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin: 4px 0 0;
            font-weight: 600;
        }
        .content {
            padding: 36px 36px 30px;
            line-height: 1.65;
            font-size: 15px;
            color: #4e5768;
        }
        .greeting {
            font-size: 18px;
            color: #030c1d;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .badge {
            display: inline-block;
            background-color: #dde1f3;
            color: #4054B2;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .card {
            background-color: #f7f7f8;
            border-left: 3px solid #23A455;
            padding: 16px 20px;
            margin: 24px 0;
            border-radius: 0 4px 4px 0;
        }
        .card-title {
            font-size: 13px;
            font-weight: 700;
            color: #030c1d;
            margin: 0 0 4px;
        }
        .card-desc {
            font-size: 13px;
            color: #505b50;
            margin: 0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0 24px;
        }
        .btn {
            display: inline-block;
            background-color: #4054B2;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(64, 84, 178, 0.35);
        }
        .expiry {
            font-size: 12px;
            color: #888899;
            text-align: center;
            margin-top: 12px;
        }
        .url-box {
            margin-top: 24px;
            padding: 14px;
            background-color: #fafafa;
            border: 1px dashed #d1d1db;
            border-radius: 3px;
            font-size: 11px;
            color: #666677;
            word-break: break-all;
        }
        .footer {
            background-color: #f7f7f8;
            padding: 24px 36px;
            text-align: center;
            font-size: 12px;
            color: #888899;
            border-top: 1px solid #e6e6e9;
        }
        .footer a {
            color: #4054B2;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Brand Header -->
        <div class="header">
            <p>Cross Rivers Ministries</p>
            <h1>Staff & Ministry Portal</h1>
        </div>

        <!-- Body -->
        <div class="content">
            <div class="greeting">
                Hello {{ $invitation->name ?: 'there' }},
            </div>

            <p>
                <strong>{{ $inviterName }}</strong> has invited you to join the <strong>Cross Rivers Ministries</strong> administration team with the following role:
            </p>

            <div class="card">
                <div class="badge">{{ $roleDetails['label'] ?? 'Staff Member' }}</div>
                <p class="card-desc" style="margin-top: 8px;">
                    {{ $roleDetails['description'] ?? 'Collaborate and manage Cross Rivers Ministries operations.' }}
                </p>
            </div>

            <p>
                To accept this invitation and configure your secure account credentials, click the button below:
            </p>

            <div class="btn-wrapper">
                <a href="{{ $acceptUrl }}" class="btn" target="_blank">
                    Accept Invitation & Set Up Account
                </a>
            </div>

            <p class="expiry">
                This invitation link will expire in <strong>7 days</strong> (on {{ $invitation->expires_at->toFormattedDateString() }}).
            </p>

            <div class="url-box">
                If the button above does not work, copy and paste this link into your browser:<br>
                <a href="{{ $acceptUrl }}" style="color: #4054B2;">{{ $acceptUrl }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Cross Rivers Ministries · Christ-centered purpose &amp; life coaching.<br>
            If you were not expecting this invitation, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
