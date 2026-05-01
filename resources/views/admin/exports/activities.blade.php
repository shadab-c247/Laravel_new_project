<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        h1 {
            color: #333;
        }
        .header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Activity Logs Report</h1>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
        @if(request()->from_date && request()->to_date)
            <p>Date Range: {{ request()->from_date }} to {{ request()->to_date }}</p>
        @endif
        <p>Total Records: {{ $data->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->user?->email ?? 'System' }}</td>
                    <td>{{ $row->action }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
