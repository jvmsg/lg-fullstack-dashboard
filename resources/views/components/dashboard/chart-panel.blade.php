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
                <script id="efficiencyTrendData" type="application/json">{!! json_encode($dailyTrendByLine) !!}</script>
            @else
                <div class="alert alert-light text-center my-4" role="alert">
                    <p class="mb-0 text-muted">Nenhum dado disponível para gerar o gráfico no período selecionado.</p>
                </div>
            @endif
        </div>
    </article>
</div>
