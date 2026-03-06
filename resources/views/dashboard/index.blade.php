@extends('layouts.app')

@section('title', 'Dashboard geral | LG Fullstack Dashboard')
@section('page_title', 'Dashboard geral')
@section('page_subtitle')
    @if ($selectedProductTypeName)
        Indicadores da linha {{ $selectedProductTypeName }} no periodo {{ $periodLabel }}
    @else
        Indicadores consolidados de producao da Planta A no periodo {{ $periodLabel }}
    @endif
@endsection

@section('content')
    <section class="lg-hero card">
        <div class="card-body">
            <div class="lg-hero__head">
                <div>
                    <p class="lg-hero__eyebrow">Painel principal</p>
                    <h3 class="lg-hero__title">Performance de producao em {{ $periodLabel }}</h3>
                </div>
                <span class="lg-status-chip">{{ $summary['lines_monitored'] }} linhas monitoradas</span>
            </div>

            <p class="lg-hero__summary">
                Eficiencia media de <strong>{{ number_format($summary['overall_efficiency'], 2) }}%</strong>,
                com <strong>{{ number_format($summary['total_produced'], 0, ',', '.') }}</strong> unidades produzidas
                e <strong>{{ number_format($summary['total_defective'], 0, ',', '.') }}</strong> defeituosas no periodo.
            </p>

            <form method="GET" action="{{ route('dashboard.index') }}" class="lg-filter-row">
                <div class="form-group mb-0 mr-2">
                    <label for="product_type" class="sr-only">Linha</label>
                    <select id="product_type" name="product_type" class="form-control form-control-sm">
                        <option value="">Todas as linhas</option>
                        @foreach ($productTypes as $productType)
                            <option value="{{ $productType->id }}" {{ (int) $selectedProductTypeId === (int) $productType->id ? 'selected' : '' }}>
                                {{ $productType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0 mr-2">
                    <label for="month" class="sr-only">Mes</label>
                    <select id="month" name="month" class="form-control form-control-sm">
                        @foreach ($availableMonths as $month)
                            <option value="{{ $month }}" {{ (int) $selectedMonth === (int) $month ? 'selected' : '' }}>
                                {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0 mr-2">
                    <label for="year" class="sr-only">Ano</label>
                    <select id="year" name="year" class="form-control form-control-sm">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-sm lg-filter-btn is-active">Aplicar filtros</button>
                <a href="{{ route('dashboard.index') }}" class="btn btn-sm lg-filter-btn">Limpar</a>
            </form>

            @if (!$dataReady)
                <div class="alert alert-warning mt-3 mb-0" role="alert">
                    Tabelas de metricas ainda nao encontradas. Execute <code>php artisan migrate --seed</code>.
                </div>
            @endif
        </div>
    </section>

    <section class="row mt-4">
        @forelse ($lineMetrics as $metric)
            <div class="col-12 col-md-6 col-xl-3 mb-4">
                <article class="lg-metric card h-100">
                    <div class="card-body">
                        <p class="lg-metric__label">{{ $metric['line'] }}</p>
                        <p class="lg-metric__value">{{ number_format($metric['efficiency'], 2) }}%</p>
                        <p class="lg-metric__delta {{ $metric['trend_class'] }}">{{ $metric['trend'] }}</p>

                        <div class="lg-metric__stats">
                            <span>Produzidas: <strong>{{ number_format($metric['produced'], 0, ',', '.') }}</strong></span>
                            <span>Defeituosas: <strong>{{ number_format($metric['defective'], 0, ',', '.') }}</strong></span>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0" role="alert">
                    Nenhum dado encontrado para os filtros selecionados.
                </div>
            </div>
        @endforelse
    </section>

    <section class="row">
        <div class="col-12 col-xl-8 mb-4">
            <article class="lg-panel card h-100">
                <div class="card-body">
                    <div class="lg-panel__head">
                        <h4>Tendencia diaria de eficiencia</h4>
                        <span>{{ $periodLabel }}</span>
                    </div>

                    @if ($dailyPulse->isNotEmpty())
                        <div style="position: relative; height: 280px;">
                            <canvas id="efficiencyChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-light text-center my-4" role="alert">
                            <p class="mb-0 text-muted">Nenhum dado disponível para gerar o gráfico no período selecionado.</p>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        <div class="col-12 col-xl-4 mb-4">
            <article class="lg-panel card h-100">
                <div class="card-body">
                    <div class="lg-panel__head">
                        <h4>Pulse diario</h4>
                        <span>{{ $dailyPulse->count() }} dias no periodo</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table lg-data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Prod.</th>
                                    <th>Def.</th>
                                    <th>Efic.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyPulse as $pulse)
                                    <tr>
                                        <td>{{ $pulse['day'] }}</td>
                                        <td>{{ number_format($pulse['produced'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($pulse['defective'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($pulse['efficiency'], 2) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Sem dados para o periodo selecionado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    @if ($dailyPulse->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('efficiencyChart');
            if (!ctx) return;

            const chartData = {
                labels: {!! json_encode($dailyPulse->pluck('day')->toArray()) !!},
                datasets: [{
                    label: 'Eficiência (%)',
                    data: {!! json_encode($dailyPulse->pluck('efficiency')->map(function($val) { return round($val, 2); })->toArray()) !!},
                    borderColor: '#a70077',
                    backgroundColor: 'rgba(167, 0, 119, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#a70077',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#a70077',
                    pointHoverBorderColor: '#fff',
                }]
            };

            const config = {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Eficiência: ' + context.parsed.y.toFixed(2) + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(1) + '%';
                                },
                                font: {
                                    size: 11
                                },
                                color: '#666'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.06)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#666',
                                maxRotation: 45,
                                minRotation: 0
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    @endif
</script>
@endpush
