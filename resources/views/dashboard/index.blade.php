@extends('layouts.app')

@section('title', 'Dashboard geral | LG Fullstack Dashboard')
@section('page_title', 'Dashboard geral')
@section('page_subtitle', 'Indicadores consolidados de producao da Planta A')

@section('content')
    @php
        $lineMetrics = [
            [
                'line' => 'Geladeira',
                'produced' => 1840,
                'defective' => 77,
                'efficiency' => 95.82,
                'trend' => '+1.2%'
            ],
            [
                'line' => 'Maquina de lavar',
                'produced' => 1620,
                'defective' => 94,
                'efficiency' => 94.20,
                'trend' => '+0.4%'
            ],
            [
                'line' => 'TV',
                'produced' => 2100,
                'defective' => 123,
                'efficiency' => 94.14,
                'trend' => '-0.3%'
            ],
            [
                'line' => 'Ar-condicionado',
                'produced' => 1480,
                'defective' => 62,
                'efficiency' => 95.81,
                'trend' => '+0.8%'
            ],
        ];

        $dailyPulse = [
            ['day' => '01/01', 'produced' => 6420, 'defective' => 356, 'efficiency' => 94.46],
            ['day' => '02/01', 'produced' => 6550, 'defective' => 301, 'efficiency' => 95.40],
            ['day' => '03/01', 'produced' => 6380, 'defective' => 327, 'efficiency' => 94.87],
            ['day' => '04/01', 'produced' => 6705, 'defective' => 312, 'efficiency' => 95.35],
        ];
    @endphp

    <section class="lg-hero card">
        <div class="card-body">
            <div class="lg-hero__head">
                <div>
                    <p class="lg-hero__eyebrow">Painel principal</p>
                    <h3 class="lg-hero__title">Performance de producao em janeiro/2026</h3>
                </div>
                <span class="lg-status-chip">4 linhas monitoradas</span>
            </div>

            <p class="lg-hero__summary">
                A operacao iniciou o mes com eficiencia media acima de 94%, com estabilidade nas perdas
                e ganho de produtividade nas linhas de geladeira e ar-condicionado.
            </p>

            <div class="lg-filter-row">
                <button type="button" class="btn btn-sm lg-filter-btn is-active">Todas as linhas</button>
                <button type="button" class="btn btn-sm lg-filter-btn">Ultimos 7 dias</button>
                <button type="button" class="btn btn-sm lg-filter-btn">Janeiro/2026</button>
            </div>
        </div>
    </section>

    <section class="row mt-4">
        @foreach ($lineMetrics as $metric)
            <div class="col-12 col-md-6 col-xl-3 mb-4">
                <article class="lg-metric card h-100">
                    <div class="card-body">
                        <p class="lg-metric__label">{{ $metric['line'] }}</p>
                        <p class="lg-metric__value">{{ number_format($metric['efficiency'], 2) }}%</p>
                        <p class="lg-metric__delta {{ str_contains($metric['trend'], '-') ? 'is-down' : 'is-up' }}">
                            {{ $metric['trend'] }}
                        </p>

                        <div class="lg-metric__stats">
                            <span>Produzidas: <strong>{{ number_format($metric['produced'], 0, ',', '.') }}</strong></span>
                            <span>Defeituosas: <strong>{{ number_format($metric['defective'], 0, ',', '.') }}</strong></span>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </section>

    <section class="row">
        <div class="col-12 col-xl-8 mb-4">
            <article class="lg-panel card h-100">
                <div class="card-body">
                    <div class="lg-panel__head">
                        <h4>Tendencia diaria de eficiencia</h4>
                        <span>Preview visual</span>
                    </div>

                    <div class="lg-chart-placeholder" role="img" aria-label="Grafico placeholder de eficiencia diaria">
                        <div class="lg-chart-placeholder__line"></div>
                        <div class="lg-chart-placeholder__line"></div>
                        <div class="lg-chart-placeholder__line"></div>
                        <div class="lg-chart-placeholder__line"></div>
                        <div class="lg-chart-placeholder__line"></div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-12 col-xl-4 mb-4">
            <article class="lg-panel card h-100">
                <div class="card-body">
                    <div class="lg-panel__head">
                        <h4>Pulse diario</h4>
                        <span>Primeiros 4 dias</span>
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
                                @foreach ($dailyPulse as $pulse)
                                    <tr>
                                        <td>{{ $pulse['day'] }}</td>
                                        <td>{{ number_format($pulse['produced'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($pulse['defective'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($pulse['efficiency'], 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
