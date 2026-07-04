<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registered Students Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; color: #0b3d91; margin: 5px 0; }
        .date { text-align: right; margin-bottom: 20px; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #0b3d91; color: white; font-size: 11px; }
        td { font-size: 10px; }
        .status-uploaded { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Africa Centre of Excellence on Technology Enhanced Learning (ACETEL)</h2>
    <h3>Registered Students Report</h3>
    <div class="date">Generated on: {{ now()->format('d M Y, h:i A') }}</div>

    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Matric Number</th>
                <th>Full Name</th>
                <th>Programme</th>
                <th>Supervisor</th>
                <th>PDF Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->matric_number }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->programme->name }}</td>
                <td>{{ $student->supervisor_name }}</td>
                <td class="{{ $student->presentation && $student->presentation->file_path ? 'status-uploaded' : 'status-pending' }}">
                    {{ $student->presentation && $student->presentation->file_path ? 'Uploaded' : 'Pending' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
