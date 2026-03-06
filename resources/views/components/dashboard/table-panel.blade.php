@props(['dailyPulse'])

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
