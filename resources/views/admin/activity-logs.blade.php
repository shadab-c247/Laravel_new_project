@extends('layouts.panel', ['title' => 'Activity Logs'])

@section('body')
<div class="panel-shell">
    @include('admin.partials.sidebar')

    <main class="panel-main">
        <header class="topbar">
            <div>
                <h1>Activity Logs</h1>
                <p>Track login, switching and admin actions performed inside the system.</p>
            </div>
            
            <!-- TODO: Uncomment when admin panel is ready -->
            <!-- <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Admin Panel</a> -->

            <button onclick="openExportModal()" class="btn btn-secondary">Export Activity Logs</button>
        </header>

        <section class="content-area">
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activityLogs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->user?->email ?? 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="muted">No activity recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                
            </div>
        </section>
    </main>
</div>

<!-- Activity Export Modal -->
<div id="exportModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <h3>Export Activities</h3>

        <form method="GET" action="{{ route('admin.activities.export') }}" class="form-grid">
            
            <!-- Step 1: Format -->
            <label>Export Format</label>
            <select name="format" id="format" required onchange="toggleDateFields()">
                <option value="">Select Format</option>
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
            </select>

            <!-- Step 2: Date Range -->
            <div id="dateRangeFields" style="display: none;">
                <label>From Date</label>
                <input type="date" name="from_date">

                <label>To Date</label>
                <input type="date" name="to_date">
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeExportModal()" class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary" type="submit">Export</button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
function toggleDateFields() {
    let format = document.getElementById('format').value;
    let dateFields = document.getElementById('dateRangeFields');

    if (format) {
        dateFields.style.display = 'block';
    } else {
        dateFields.style.display = 'none';
    }
}

function openExportModal() {
    document.getElementById('exportModal').style.display = 'flex';
}

function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}

// Handle form submission with success message
document.querySelector('#exportModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'GET',
        body: new URLSearchParams(formData)
    })
    .then(response => {
        if (response.ok) {
            // Trigger file download
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = blob.type.includes('pdf') ? 'activities.pdf' : 'activities.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Show success message
        alert('File download successful');
        closeExportModal();
    })
    .catch(error => {
        alert('Export failed. Please try again.');
    });
});
</script>

