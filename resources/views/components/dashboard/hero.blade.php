@props(['summary', 'periodLabel', 'selectedProductTypeName', 'productTypes', 'selectedProductTypeId', 'selectedPeriod', 'dataReady'])

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

        <form method="GET" action="{{ route('dashboard.index') }}" class="lg-filter-row" data-dashboard-filter-form>
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
                <label for="period" class="sr-only">Periodo</label>
                <input
                    id="period"
                    name="period"
                    type="month"
                    class="form-control form-control-sm lg-month-input"
                    value="{{ $selectedPeriod }}"
                    onkeydown="return false;"
                >
            </div>

            <button type="submit" class="btn btn-sm lg-filter-btn is-active">Aplicar filtros</button>
            <a href="{{ route('dashboard.index') }}" class="btn btn-sm lg-filter-btn" data-dashboard-clear>Limpar</a>
        </form>

        @if (!$dataReady)
            <div class="alert alert-warning mt-3 mb-0" role="alert">
                Tabelas de metricas ainda nao encontradas. Execute <code>php artisan migrate --seed</code>.
            </div>
        @endif
    </div>
</section>
