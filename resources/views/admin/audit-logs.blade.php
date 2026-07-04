<x-app-layout>
    <x-slot name="header">
        System Audit Trail
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="auditTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Target Entity</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($log->user)
                                    <strong>{{ $log->user->name }}</strong><br>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                    <span class="text-muted">System/Guest</span>
                                @endif
                            </td>
                            <td>{{ $log->action }}</td>
                            <td>
                                @if($log->model_type)
                                    <span class="badge bg-secondary">{{ $log->model_type }}</span>
                                    @if($log->model_id) #{{ $log->model_id }} @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
