@props(['dailyTrendByLine', 'periodLabel'])

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
