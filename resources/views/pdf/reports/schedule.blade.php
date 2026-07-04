<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Presentation Schedule Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; color: #0b3d91; margin: 5px 0; }
        .date { text-align: right; margin-bottom: 20px; font-size: 10px; color: #555; }
        .day-group { margin-bottom: 30px; }
        .day-header { background-color: #f4f4f4; padding: 10px; font-weight: bold; font-size: 14px; border-left: 4px solid #0b3d91; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #0b3d91; color: white; font-size: 11px; }
        td { font-size: 11px; }
    </style>
</head>
<body>
    <h2>Africa Centre of Excellence on Technology Enhanced Learning (ACETEL)</h2>
    <h3>Progress Report Presentation Schedule</h3>
    <div class="date">Generated on: {{ now()->format('d M Y, h:i A') }}</div>

    @forelse($groupedSchedules as $date => $schedules)
        <div class="day-group">
            <div class="day-header">
                {{ \Carbon\Carbon::parse($date)->format('l, jS F Y') }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Time</th>
                        <th width="20%">Matric Number</th>
                        <th width="30%">Student Name</th>
                        <th width="20%">Programme</th>
                        <th width="15%">Venue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                        <td>{{ $schedule->student->matric_number }}</td>
                        <td>{{ $schedule->student->user->name }}</td>
                        <td>{{ $schedule->student->programme->name }}</td>
                        <td>{{ $schedule->venue }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align: center; color: #777;">No schedules generated yet.</p>
    @endforelse
</body>
</html>
