<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ACETEL Presentation Schedule Confirmation</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #bfdbfe;
            margin: 10px 0 0;
            font-size: 15px;
        }
        .content {
            padding: 40px 30px;
            color: #334155;
            line-height: 1.6;
            font-size: 16px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #0f172a;
        }
        .details-card {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 20px 25px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .detail-row {
            margin-bottom: 15px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #0f172a;
            font-size: 16px;
            font-weight: 500;
        }
        .action-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
            transition: all 0.3s ease;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }
        .alert-box {
            background-color: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>ACETEL Progress Report</h1>
            <p>Presentation Schedule Confirmation</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">Dear {{ $user->name }},</div>
            
            <p>We are writing to officially notify you that your ACETEL Progress Report presentation has been successfully scheduled. Please review your presentation details below carefully.</p>
            
            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $schedule->presentation_date->format('l, d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value">{{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }} (WAT)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Venue</span>
                    <span class="detail-value">
                        @if(filter_var($schedule->venue, FILTER_VALIDATE_URL))
                            <a href="{{ $schedule->venue }}" style="color:#3b82f6;">Join Virtual Meeting</a>
                        @else
                            {{ $schedule->venue }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="action-container">
                <a href="{{ url('/student/download-slip') }}" class="btn">Download Acknowledgement Slip</a>
            </div>

            <div class="alert-box">
                <strong>Important Notice:</strong> Please ensure you join the virtual meeting room or arrive at the venue at least <strong>15 minutes</strong> before your scheduled time.
            </div>
            
            <p style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                Best regards,<br>
                <strong>ACETEL APRMS Administration</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Africa Centre of Excellence on Technology Enhanced Learning (ACETEL).</p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
