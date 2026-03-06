<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>LG Production Dashboard - Planta A</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        @php
            $hasMixManifest = file_exists(public_path('mix-manifest.json'));
        @endphp

        <link rel="stylesheet" href="{{ $hasMixManifest ? mix('css/app.css') : asset('css/app.css') }}">

        <!-- Landing Page Styles -->
        <style>
            :root {
                --bs-body-font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
                --bs-body-color: #1b2530;
                --lg-page-bg: #eef2eb;
                --lg-page-bg-soft: #f9faf7;
                --lg-accent: #c8102e;
                --lg-accent-soft: #f8d5dc;
                --lg-success: #1f9d6a;
                --lg-shadow: 0 20px 50px rgba(18, 26, 36, 0.14);
            }

            body {
                font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
                background:
                    radial-gradient(circle at 7% 12%, rgba(200, 16, 46, 0.08), transparent 24%),
                    radial-gradient(circle at 92% 4%, rgba(31, 157, 106, 0.09), transparent 22%),
                    linear-gradient(170deg, var(--lg-page-bg-soft) 0%, var(--lg-page-bg) 46%, #e4eada 100%);
                min-height: 100vh;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Archivo', 'Space Grotesk', 'Segoe UI', sans-serif;
                letter-spacing: 0.01em;
            }

            .landing-container {
                animation: lg-rise 0.55s ease both;
            }

            @keyframes lg-rise {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .landing-card {
                position: relative;
                overflow: hidden;
                background: rgba(255, 255, 255, 0.98);
                border-color: #dce3d2;
            }

            .landing-card::before {
                content: '';
                position: absolute;
                inset: -120px auto auto -100px;
                width: 260px;
                height: 260px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(200, 16, 46, 0.21) 0%, rgba(200, 16, 46, 0) 70%);
                z-index: 0;
            }

            .landing-card::after {
                content: '';
                position: absolute;
                right: -85px;
                bottom: -100px;
                width: 220px;
                height: 220px;
                border-radius: 1.5rem;
                transform: rotate(20deg);
                background: linear-gradient(145deg, rgba(31, 157, 106, 0.17) 0%, rgba(31, 157, 106, 0) 74%);
                z-index: 0;
            }

            .landing-content {
                position: relative;
                z-index: 1;
            }

            .landing-status-badge {
                display: inline-flex;
                align-items: center;
                border-radius: 50px;
                padding: 0.4rem 0.9rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: #8d1028;
                background: var(--lg-accent-soft);
                border: 1px solid rgba(200, 16, 46, 0.22);
            }

            .feature-card-item {
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.6);
                border-color: #dce3d2;
            }

            .feature-card-item:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 30px rgba(18, 26, 36, 0.1);
                border-color: rgba(200, 16, 46, 0.2);
            }

            .feature-icon {
                font-size: 1.8rem;
                margin-bottom: 0.6rem;
                display: block;
            }

            .btn-dashboard {
                background: linear-gradient(120deg, var(--lg-accent) 0%, #db4d69 100%);
                box-shadow: 0 8px 24px rgba(200, 16, 46, 0.3);
                transition: all 0.3s ease;
            }

            .btn-dashboard:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 32px rgba(200, 16, 46, 0.4);
                color: #fff;
            }

            .btn-dashboard:active {
                transform: translateY(-1px);
            }

            .landing-footer-divider {
                border-top: 1px solid #dce3d2;
            }

        </style>
    </head>
    <body>
        <div class="d-flex align-items-center justify-content-center min-vh-100 py-5">
            <div class="landing-container w-100" style="max-width: 1000px;">
                <div class="card landing-card border rounded-4 p-md-5 p-4 shadow-lg">
                    <div class="card-body landing-content text-center p-0">
                        <small class="d-block text-uppercase text-muted fw-semibold mb-2" style="letter-spacing: 0.07em; font-size: 0.66rem;">Sistema de Monitoramento</small>
                        <h1 class="display-3 fw-bold mb-3">LG Electronics</h1>
                        <p class="fs-5 text-muted mb-4">Dashboard de produção • Planta A</p>

                        <p class="mb-5 mx-auto" style="max-width: 700px; line-height: 1.7;">
                            Sistema completo para monitoramento e análise da <strong class="text-danger">eficiência de produção</strong> 
                            da Planta A da LG Electronics. Visualize métricas detalhadas, acompanhe o desempenho 
                            de múltiplas linhas de produção e tome decisões estratégicas baseadas em dados precisos.
                        </p>

                        <!-- Features Grid -->
                        <div class="row g-3 mb-5">
                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card-item border rounded-3 h-100 p-3">
                                    <div class="card-body p-0 text-start">
                                        <span class="feature-icon">📊</span>
                                        <h6 class="fw-semibold mb-2">Dashboard Interativo</h6>
                                        <small class="text-muted">Visualização em gráficos e métricas detalhadas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card-item border rounded-3 h-100 p-3">
                                    <div class="card-body p-0 text-start">
                                        <span class="feature-icon">🏭</span>
                                        <h6 class="fw-semibold mb-2">Múltiplas Linhas</h6>
                                        <small class="text-muted">Geladeira, Máquina de Lavar, TV e Ar-Condicionado</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card-item border rounded-3 h-100 p-3">
                                    <div class="card-body p-0 text-start">
                                        <span class="feature-icon">📈</span>
                                        <h6 class="fw-semibold mb-2">Análise de Eficiência</h6>
                                        <small class="text-muted">Cálculo automático de eficiência e análise de defeitos</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="card feature-card-item border rounded-3 h-100 p-3">
                                    <div class="card-body p-0 text-start">
                                        <span class="feature-icon">🔍</span>
                                        <h6 class="fw-semibold mb-2">Filtros Personalizados</h6>
                                        <small class="text-muted">Filtre por linha específica e período personalizado</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mb-5">
                            <a href="{{ route('dashboard.index') }}" class="btn btn-lg btn-dashboard text-white fw-semibold rounded-3 px-5">
                                Acessar Dashboard
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="landing-footer-divider pt-4">
                            <small class="text-muted">Desenvolvido com Laravel & Bootstrap • Planta A - LG Electronics</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
