<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>User Dashboard</h4>
        </div>

        <div class="card-body">

            <h5 class="mb-4">Welcome, {{ $user->name }}</h5>

            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>Department</th>
                    <td>{{ $user->userRole->department->name ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Position</th>
                    <td>{{ $user->userRole->position->name ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td>{{ $user->userRole->role->name ?? 'N/A' }}</td>
                </tr>
            </table>

            <a href="{{ url('/logout') }}" class="btn btn-danger">Logout</a>

        </div>
    </div>

</div>

</body>
</html>