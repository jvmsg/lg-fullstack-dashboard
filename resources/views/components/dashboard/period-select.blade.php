@props(['periods', 'selectedPeriod'])

<div class="form-group mb-0 mr-2">
    <label for="period" class="sr-only">Periodo</label>
    <select id="period" name="period" class="form-control form-control-sm lg-period-select">
        <option value="" disabled>Selecione um período</option>
        @forelse ($periods as $period)
            <option
                value="{{ $period['value'] }}"
                {{ $selectedPeriod === $period['value'] ? 'selected' : '' }}
            >
                {{ $period['label'] }}
            </option>
        @empty
            <option value="" disabled>Nenhum período disponível</option>
        @endforelse
    </select>
</div>
