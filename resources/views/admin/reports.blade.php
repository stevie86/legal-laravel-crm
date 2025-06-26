@extends('layouts.crm')

@section('title', 'Berichte')
@section('header', 'Berichte und Statistiken')

@section('content')
<div class="row">
    <!-- Sitzungen nach Status -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Sitzungen nach Status
                </h5>
            </div>
            <div class="card-body">
                @forelse($data['sessions_by_status'] as $status)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>
                            @switch($status->status)
                                @case('scheduled')
                                    <i class="bi bi-calendar-check text-primary me-2"></i>Geplant
                                    @break
                                @case('completed')
                                    <i class="bi bi-check-circle text-success me-2"></i>Abgeschlossen
                                    @break
                                @case('cancelled')
                                    <i class="bi bi-x-circle text-danger me-2"></i>Abgesagt
                                    @break
                                @case('no_show')
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Nicht erschienen
                                    @break
                                @default
                                    <i class="bi bi-question-circle text-secondary me-2"></i>{{ ucfirst($status->status) }}
                            @endswitch
                        </span>
                        <span class="badge bg-secondary">{{ $status->count }}</span>
                    </div>
                @empty
                    <p class="text-muted">Keine Daten verfügbar.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sitzungen nach Monat -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Sitzungen nach Monat ({{ date('Y') }})
                </h5>
            </div>
            <div class="card-body">
                @php
                    $months = [
                        1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April',
                        5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'
                    ];
                    $sessionsByMonth = $data['sessions_by_month']->keyBy('month');
                @endphp
                
                @foreach($months as $monthNum => $monthName)
                    @php
                        $count = $sessionsByMonth->get($monthNum)->count ?? 0;
                        $percentage = $data['sessions_by_month']->max('count') > 0 ? 
                            ($count / $data['sessions_by_month']->max('count')) * 100 : 0;
                    @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ $monthName }}</span>
                            <span class="badge bg-primary">{{ $count }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Neue Klienten nach Monat -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-plus me-2"></i>Neue Klienten nach Monat ({{ date('Y') }})
                </h5>
            </div>
            <div class="card-body">
                @php
                    $clientsByMonth = $data['clients_by_month']->keyBy('month');
                @endphp
                
                <div class="row">
                    @foreach($months as $monthNum => $monthName)
                        @php
                            $count = $clientsByMonth->get($monthNum)->count ?? 0;
                        @endphp
                        <div class="col-md-2 mb-3">
                            <div class="text-center">
                                <h6>{{ $monthName }}</h6>
                                <div class="display-6 text-primary">{{ $count }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export-Optionen -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-download me-2"></i>Export-Optionen
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-success w-100" onclick="exportData('excel')">
                            <i class="bi bi-file-earmark-excel me-2"></i>Excel Export
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-danger w-100" onclick="exportData('pdf')">
                            <i class="bi bi-file-earmark-pdf me-2"></i>PDF Export
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-info w-100" onclick="exportData('csv')">
                            <i class="bi bi-file-earmark-text me-2"></i>CSV Export
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-secondary w-100" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i>Drucken
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportData(format) {
    alert('Export-Funktion für ' + format.toUpperCase() + ' wird in einer zukünftigen Version implementiert.');
}
</script>
@endpush