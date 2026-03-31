@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h5 class="mb-4 fw-bold">📊 Admin Dashboard</h5>

    <!-- STATS CARDS -->
    <div class="row stats-row">

        <!-- TOTAL -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card total-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Total Applications</h6>
                        <h2 class="counter" data-target="{{ $total }}">0</h2>
                        <span class="badge badge-dark">All Records</span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-collection"></i>
                    </div>

                </div>
            </div>
        </div>

        <!-- PENDING -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card pending-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Pending</h6>
                        <h2 class="counter text-success" data-target="{{ $pending }}">0</h2>
                        <span class="badge badge-success">
                            {{ $total > 0 ? round(($pending / $total) * 100) : 0 }}%
                        </span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>
            </div>
        </div>

        <!-- DRAFT -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card draft-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Incomplete Information</h6>
                        <h2 class="counter text-danger" data-target="{{ $draft }}">0</h2>
                        <span class="badge badge-danger">
                            {{ $total > 0 ? round(($draft / $total) * 100) : 0 }}%
                        </span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                </div>
            </div>
        </div>
        <!-- EVALUATED -->
<div class="col-md-3 mb-3">
    <div class="card stat-card evaluated-card">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h6>Evaluated</h6>
                <h2 class="counter text-primary" data-target="{{ $evaluated }}">0</h2>
                <span class="badge badge-primary">
                    {{ $total > 0 ? round(($evaluated / $total) * 100) : 0 }}%
                </span>
            </div>

            <div class="icon-box">
                <i class="bi bi-clipboard-check"></i>
            </div>

        </div>
    </div>
</div>

    </div>

    <!-- POSITION CHART -->
    <div class="card shadow-sm border-0 p-4 mt-4">
        <h5 class="mb-1">👨‍🏫 Applicants per Position</h5>
        <small class="text-muted">Teacher vs Master Teacher distribution</small>

        <div style="position: relative; height: 350px;">
            <canvas id="positionChart"></canvas>
        </div>
    </div>

    <!-- MAIN CHART -->
    <div class="card shadow-sm border-0 p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">📈 Applications Overview</h5>
        </div>

        <canvas id="myChart" height="100"></canvas>
    </div>

</div>
@endsection

@push('scripts')

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // COUNTER ANIMATION
    // =========================
    // =========================
// COUNTER ANIMATION (SLOWER + SMOOTHER)
// =========================
const counters = document.querySelectorAll('.counter');

counters.forEach(counter => {
    counter.innerText = '0';

    const target = +counter.getAttribute('data-target');
    const duration = 1200; // ⬅️ mas mabagal (1.2s animation)
    const stepTime = 15;

    const steps = duration / stepTime;
    const increment = target / steps;

    let current = 0;

    const update = () => {
        current += increment;

        if (current < target) {
            counter.innerText = Math.floor(current);
            setTimeout(update, stepTime);
        } else {
            counter.innerText = target;
        }
    };

    update();
});

    // =========================
    // MAIN CHART
    // =========================
    const ctx = document.getElementById('myChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Pending', 'Draft', 'Evaluated'],
                datasets: [{
                    label: 'Applications',
                    data: [{{ $total }}, {{ $pending }}, {{ $draft }}, {{ $evaluated }}],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // =========================
    // POSITION LINE CHART
    // =========================
    const positionCtx = document.getElementById('positionChart');

    if (positionCtx) {

        setTimeout(() => {

            const teacherTotal = {{ $teacherTotal }};
            const masterTotal = {{ $masterTotal }};

            const teacherData = {!! json_encode($teacherCounts) !!};
            const masterData = {!! json_encode($masterCounts) !!};

            const teacherLabels = {!! json_encode($teacherPositions) !!};
            const masterLabels = {!! json_encode($masterPositions) !!};

            new Chart(positionCtx, {
                type: 'line',
                data: {
                    labels: [...teacherLabels, ...masterLabels],

                    datasets: [
                        {
                            label: 'Teacher',
                            data: [
                                ...teacherData,
                                ...Array(masterData.length).fill(null)
                            ],
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.3,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Master Teacher',
                            data: [
                                ...Array(teacherData.length).fill(null),
                                ...masterData
                            ],
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            tension: 0.3,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        x: {
                            ticks: {
                                autoSkip: false
                            }
                        }
                    },

                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const total = context.dataset.label === 'Teacher'
                                        ? teacherTotal
                                        : masterTotal;

                                    const percent = total ? ((value / total) * 100).toFixed(1) : 0;

                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });

        }, 300);
    }

});
</script>

@endpush