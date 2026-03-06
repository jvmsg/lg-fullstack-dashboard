<div
    data-dashboard-meta
    data-page-subtitle="{{ $selectedProductTypeName ? 'Indicadores da linha ' . $selectedProductTypeName . ' no periodo ' . $periodLabel : 'Indicadores consolidados de producao da Planta A no periodo ' . $periodLabel }}"
></div>

<x-dashboard.hero
    :summary="$summary"
    :periodLabel="$periodLabel"
    :selectedProductTypeName="$selectedProductTypeName"
    :productTypes="$productTypes"
    :selectedProductTypeId="$selectedProductTypeId"
    :selectedPeriod="$selectedPeriod"
    :periods="$periods"
    :dataReady="$dataReady"
/>

<section class="row mt-4">
    @forelse ($lineMetrics as $metric)
        @include('components.dashboard.metric-card', [
            'line' => $metric['line'],
            'efficiency' => $metric['efficiency'],
            'trend' => $metric['trend'],
            'trendClass' => $metric['trend_class'],
            'produced' => $metric['produced'],
            'defective' => $metric['defective'],
        ])
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0" role="alert">
                Nenhum dado encontrado para os filtros selecionados.
            </div>
        </div>
    @endforelse
</section>

<section class="row">
    <x-dashboard.chart-panel :dailyTrendByLine="$dailyTrendByLine" :periodLabel="$periodLabel" />
    <x-dashboard.table-panel :dailyPulse="$dailyPulse" />
</section>
