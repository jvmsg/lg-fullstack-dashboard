<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\ProductionMetric;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('product_types') || !Schema::hasTable('production_metrics')) {
            return view('dashboard.index', $this->emptyStateData());
        }

        $productTypes = ProductType::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $availableYears = ProductionMetric::query()
            ->selectRaw('YEAR(day) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(function ($year) {
                return (int) $year;
            })
            ->values();

        $selectedYear = (int) $request->query('year', 2026);
        if (!$availableYears->contains($selectedYear)) {
            $selectedYear = $availableYears->first() ?: 2026;
        }

        $availableMonths = ProductionMetric::query()
            ->whereYear('day', $selectedYear)
            ->selectRaw('MONTH(day) as month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->map(function ($month) {
                return (int) $month;
            })
            ->values();

        $selectedMonth = (int) $request->query('month', 1);
        if (!$availableMonths->contains($selectedMonth)) {
            $selectedMonth = $availableMonths->first() ?: 1;
        }

        $selectedProductTypeId = $request->query('product_type');
        $selectedProductTypeId = is_numeric($selectedProductTypeId) ? (int) $selectedProductTypeId : null;

        if ($selectedProductTypeId && !$productTypes->contains('id', $selectedProductTypeId)) {
            $selectedProductTypeId = null;
        }

        $selectedProductType = $selectedProductTypeId
            ? $productTypes->firstWhere('id', $selectedProductTypeId)
            : null;

        $periodStart = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $periodEnd = (clone $periodStart)->endOfMonth();
        $previousStart = (clone $periodStart)->subMonthNoOverflow()->startOfMonth();
        $previousEnd = (clone $previousStart)->endOfMonth();

        $lineMetricsRows = ProductionMetric::query()
            ->selectRaw('product_type_id, SUM(quantity_produced) as produced, SUM(quantity_defective) as defective')
            ->whereBetween('day', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->when($selectedProductTypeId, function ($query) use ($selectedProductTypeId) {
                $query->where('product_type_id', $selectedProductTypeId);
            })
            ->groupBy('product_type_id')
            ->get();

        $previousRows = ProductionMetric::query()
            ->selectRaw('product_type_id, SUM(quantity_produced) as produced, SUM(quantity_defective) as defective')
            ->whereBetween('day', [$previousStart->toDateString(), $previousEnd->toDateString()])
            ->when($selectedProductTypeId, function ($query) use ($selectedProductTypeId) {
                $query->where('product_type_id', $selectedProductTypeId);
            })
            ->groupBy('product_type_id')
            ->get()
            ->keyBy('product_type_id');

        $lineMetrics = $lineMetricsRows->map(function ($row) use ($previousRows, $productTypes) {
            $produced = (int) $row->produced;
            $defective = (int) $row->defective;
            $efficiency = $this->calculateEfficiency($produced, $defective);

            $previousRow = $previousRows->get($row->product_type_id);
            $trendValue = null;

            if ($previousRow) {
                $previousEfficiency = $this->calculateEfficiency((int) $previousRow->produced, (int) $previousRow->defective);
                $trendValue = $efficiency - $previousEfficiency;
            }

            return [
                'line' => optional($productTypes->firstWhere('id', (int) $row->product_type_id))->name ?: 'Sem nome',
                'produced' => $produced,
                'defective' => $defective,
                'efficiency' => round($efficiency, 2),
                'trend' => is_null($trendValue) ? '--' : sprintf('%+.1f%%', $trendValue),
                'trend_class' => is_null($trendValue) ? '' : ($trendValue < 0 ? 'is-down' : 'is-up'),
            ];
        })->sortByDesc('efficiency')->values();

        $dailyPulse = ProductionMetric::query()
            ->selectRaw('day, SUM(quantity_produced) as produced, SUM(quantity_defective) as defective')
            ->whereBetween('day', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->when($selectedProductTypeId, function ($query) use ($selectedProductTypeId) {
                $query->where('product_type_id', $selectedProductTypeId);
            })
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($row) {
                $produced = (int) $row->produced;
                $defective = (int) $row->defective;

                return [
                    'day' => Carbon::parse($row->day)->format('d/m'),
                    'produced' => $produced,
                    'defective' => $defective,
                    'efficiency' => round($this->calculateEfficiency($produced, $defective), 2),
                ];
            })
            ->values();

        // Daily trend by product line for multi-line chart
        $dailyTrendByLine = ProductionMetric::query()
            ->selectRaw('product_type_id, day, SUM(quantity_produced) as produced, SUM(quantity_defective) as defective')
            ->whereBetween('day', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->when($selectedProductTypeId, function ($query) use ($selectedProductTypeId) {
                $query->where('product_type_id', $selectedProductTypeId);
            })
            ->groupBy('product_type_id', 'day')
            ->orderBy('day')
            ->get()
            ->groupBy('product_type_id')
            ->map(function ($metrics, $productTypeId) use ($productTypes) {
                $productTypeName = optional($productTypes->firstWhere('id', (int) $productTypeId))->name ?: 'Sem nome';
                
                $data = $metrics->map(function ($row) {
                    $produced = (int) $row->produced;
                    $defective = (int) $row->defective;
                    
                    return [
                        'day' => Carbon::parse($row->day)->format('d/m'),
                        'efficiency' => round($this->calculateEfficiency($produced, $defective), 2),
                    ];
                })->values();

                return [
                    'name' => $productTypeName,
                    'data' => $data,
                ];
            })
            ->values();

        $summary = $this->buildSummary($lineMetrics);

        return view('dashboard.index', [
            'lineMetrics' => $lineMetrics,
            'dailyPulse' => $dailyPulse,
            'dailyTrendByLine' => $dailyTrendByLine,
            'summary' => $summary,
            'productTypes' => $productTypes,
            'selectedProductTypeId' => $selectedProductTypeId,
            'selectedProductTypeName' => optional($selectedProductType)->name,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'availableMonths' => $availableMonths,
            'availableYears' => $availableYears,
            'periodLabel' => $this->formatPeriodLabel($selectedMonth, $selectedYear),
            'dataReady' => true,
        ]);
    }

    private function calculateEfficiency($produced, $defective)
    {
        if ($produced <= 0) {
            return 0;
        }

        return (($produced - $defective) / $produced) * 100;
    }

    private function buildSummary($lineMetrics)
    {
        $totalProduced = (int) $lineMetrics->sum('produced');
        $totalDefective = (int) $lineMetrics->sum('defective');
        $bestLine = $lineMetrics->first();

        return [
            'lines_monitored' => $lineMetrics->count(),
            'total_produced' => $totalProduced,
            'total_defective' => $totalDefective,
            'overall_efficiency' => round($this->calculateEfficiency($totalProduced, $totalDefective), 2),
            'best_line' => $bestLine ? $bestLine['line'] : null,
        ];
    }

    private function formatPeriodLabel($month, $year)
    {
        $months = [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez',
        ];

        return ($months[(int) $month] ?? 'Mes') . '/' . $year;
    }

    private function emptyStateData()
    {
        return [
            'lineMetrics' => collect(),
            'dailyPulse' => collect(),
            'dailyTrendByLine' => collect(),
            'summary' => [
                'lines_monitored' => 0,
                'total_produced' => 0,
                'total_defective' => 0,
                'overall_efficiency' => 0,
                'best_line' => null,
            ],
            'productTypes' => collect(),
            'selectedProductTypeId' => null,
            'selectedProductTypeName' => null,
            'selectedMonth' => 1,
            'selectedYear' => 2026,
            'availableMonths' => collect([1]),
            'availableYears' => collect([2026]),
            'periodLabel' => 'Jan/2026',
            'dataReady' => false,
        ];
    }
}
