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
    <div data-dashboard-content>
        @include('dashboard.partials.content')
    </div>
@endsection
