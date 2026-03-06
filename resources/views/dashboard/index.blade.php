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
                        <h4>Tendencia diaria de eficiencia por linha</h4>
                        <span>{{ $periodLabel }}</span>
                    </div>

                    @if ($dailyTrendByLine->isNotEmpty())
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

                    <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                        <table class="table lg-data-table mb-0">
                            <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
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
    @if ($dailyTrendByLine->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('efficiencyChart');
            if (!ctx) return;

            // Cores para cada linha de produto
            const lineColors = [
                { border: '#a70077', bg: 'rgba(167, 0, 119, 0.1)' },  // Magenta LG
                { border: '#0066cc', bg: 'rgba(0, 102, 204, 0.1)' },  // Azul
                { border: '#ff6b35', bg: 'rgba(255, 107, 53, 0.1)' }, // Laranja
                { border: '#00a86b', bg: 'rgba(0, 168, 107, 0.1)' },  // Verde
            ];

            const trendData = {!! json_encode($dailyTrendByLine) !!};
            
            // Extrair todos os dias únicos (labels do eixo X)
            const allDays = [];
            trendData.forEach(line => {
                line.data.forEach(point => {
                    if (!allDays.includes(point.day)) {
                        allDays.push(point.day);
                    }
                });
            });

            // Criar um dataset para cada linha de produto
            const datasets = trendData.map((line, index) => {
                const colorIndex = index % lineColors.length;
                const color = lineColors[colorIndex];
                
                // Mapear os dados para garantir que todos os dias estão presentes
                const dataPoints = allDays.map(day => {
                    const point = line.data.find(p => p.day === day);
                    return point ? point.efficiency : null;
                });

                return {
                    label: line.name,
                    data: dataPoints,
                    borderColor: color.border,
                    backgroundColor: color.bg,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: color.border,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: color.border,
                    pointHoverBorderColor: '#fff',
                };
            });

            const chartData = {
                labels: allDays,
                datasets: datasets
            };

            const config = {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
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
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y !== null ? context.parsed.y.toFixed(2) + '%' : 'N/A';
                                    return label + ': ' + value;
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
