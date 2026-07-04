<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acknowledgement Slip</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .slip-container {
            border: 2px solid #0b3d91;
            padding: 30px;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #f8b400;
            padding-bottom: 20px;
            margin-bottom: 30px;
            background-color: #0b3d91;
            color: #fff;
            padding: 20px;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 16px;
            color: #f8b400;
        }
        .header h3 {
            margin: 10px 0 0;
            font-size: 14px;
            font-weight: normal;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th, .details-table td {
            padding: 12px;
            border-bottom: 1px dashed #ccc;
            text-align: left;
        }
        .details-table th {
            width: 35%;
            color: #0b3d91;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f8b400;
            color: #0b3d91;
            font-weight: bold;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <h1>Africa Centre of Excellence on Technology Enhanced Learning</h1>
            <h2>(ACETEL)</h2>
            <h3>Progress Report Presentation - Acknowledgement Slip</h3>
        </div>

    <table class="details-table">
        <tr>
            <th>Full Name:</th>
            <td>{{ $student->user->name }}</td>
        </tr>
        <tr>
            <th>Matric Number:</th>
            <td>{{ $student->matric_number }}</td>
        </tr>
        <tr>
            <th>Programme:</th>
            <td>{{ $student->programme->name }} - {{ $student->department->name }}</td>
        </tr>
        <tr>
            <th>Research Title:</th>
            <td>{{ $student->research_title }}</td>
        </tr>
        <tr>
            <th>Supervisor:</th>
            <td>{{ $student->supervisor_name }}</td>
        </tr>
        <tr>
            <th>Presentation Date:</th>
            <td>{{ $schedule ? $schedule->presentation_date->format('l, d F Y') : 'Not scheduled yet' }}</td>
        </tr>
        <tr>
            <th>Presentation Time:</th>
            <td>{{ $schedule ? $schedule->start_time->format('h:i A') . ' - ' . $schedule->end_time->format('h:i A') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Venue:</th>
            <td>{{ $schedule ? $schedule->venue : 'N/A' }}</td>
        </tr>
    </table>

        <div class="qr-code">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(120)->generate($student->matric_number . ' - ' . $student->user->name)) !!}" alt="QR Code">
            <p style="font-size: 12px; color: #555; margin-top: 5px;">Scan to verify registration</p>
        </div>

        <div class="footer">
            <p><strong>IMPORTANT:</strong> This is a computer-generated slip. Please bring this slip to the presentation venue.</p>
            <p>Generated on {{ now()->format('d M Y, h:i A') }} | ACETEL APRMS</p>
        </div>
    </div>
</body>
</html>
