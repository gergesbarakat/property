@extends('layouts.app')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
    </ul>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var options = {
                series: [{
                    name: "{{ __('Income') }}",
                    type: 'column',
                    data: {!! json_encode($result['incomeExpenseByMonth']['income']) !!},
                }, {
                    name: " {{ __('Expense') }}",
                    type: 'area',
                    data: {!! json_encode($result['incomeExpenseByMonth']['expense']) !!},
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [0, 2],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: "20%",
                        borderRadius: 5,
                    }
                },
                fill: {
                    opacity: [1, 0.1]
                },
                colors: ['#5c6ac4', '#5c6ac4'],
                yaxis: {
                    labels: {
                        formatter: function(y) {
                            return "{{ $result['settings']['CURRENCY_SYMBOL'] ?? '$' }}" + y.toFixed(0);
                        },
                    },
                },
                xaxis: {
                    categories: {!! json_encode($result['incomeExpenseByMonth']['label']) !!},
                },
            };
            var chart = new ApexCharts(document.querySelector("#incomeExpense"), options);
            chart.render();

            // ✅ NEW: JavaScript for the installment filter buttons
            $('.installment-filter-btn').on('click', function() {
                $('.installment-filter-btn').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                var target = $(this).data('target');
                $('.installments-list').hide();
                $('#' + target).show();
            });
        })();
    </script>
@endpush

@section('content')
    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-primary-lighten text-primary rounded"><i
                                        class="fa fa-building"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">{{ __('Total Active Properties') }}</p>
                            <h4 class="mb-0">{{ $result['totalProperty'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-primary-lighten text-primary rounded"><i
                                        class="fa fa-home"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">{{ __('Total Units') }}</p>
                            <h4 class="mb-0">{{ $result['totalUnit'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-success-lighten text-success rounded"><i
                                        class="fa fa-money-bill-wave"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">{{ __('Total Income') }}</p>
                            <h4 class="mb-0">
                                {{ $result['settings']['CURRENCY_SYMBOL'] ?? '$' }}{{ number_format($result['totalIncome'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded"><span
                                    class="avatar-title bg-danger-lighten text-danger rounded"><i
                                        class="fa fa-arrow-circle-down"></i></span></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">{{ __('Total Expense') }}</p>
                            <h4 class="mb-0">
                                {{ $result['settings']['CURRENCY_SYMBOL'] ?? '$' }}{{ number_format($result['totalExpense'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ✅ NEW: Upcoming Installments Card --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Upcoming Installments') }}</h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-sm installment-filter-btn"
                            data-target="this-month-installments">{{ __('This Month') }}</button>
                        <button type="button" class="btn btn-outline-primary btn-sm installment-filter-btn"
                            data-target="next-month-installments">{{ __('Next Month') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- List for "This Month" --}}
                    <div class="installments-list" id="this-month-installments">
                        <table class="table table-sm">
                            <tbody>
                                @forelse($result['dueThisMonth'] as $installment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tenant.show', $installment->buyer->id) }}"
                                                class="text-dark fw-bold">{{ optional($installment->buyer->user)->first_name }}</a>
                                            <small
                                                class="d-block text-muted">{{ optional($installment->buyer->linked_property)->name }}
                                                - {{ optional($installment->buyer->propertyUnit)->name }}</small>
                                        </td>
                                        <td class="text-end">
                                            <strong
                                                class="text-dark">{{ $result['settings']['CURRENCY_SYMBOL'] ?? '$' }}{{ number_format($installment->amount, 2) }}</strong>
                                            <small class="d-block text-muted">Due:
                                                {{ \Carbon\Carbon::parse($installment->due_date)->format('D, M j') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted p-4">{{ __('No payments due this month.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- List for "Next Month" --}}
                    <div class="installments-list" id="next-month-installments" style="display: none;">
                        <table class="table table-sm">
                            <tbody>
                                @forelse($result['dueNextMonth'] as $installment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tenant.show', $installment->buyer->id) }}"
                                                class="text-dark fw-bold">{{ optional($installment->buyer->user)->first_name }}</a>
                                            <small
                                                class="d-block text-muted">{{ optional($installment->buyer->linked_property)->name }}
                                                - {{ optional($installment->buyer->propertyUnit)->name }}</small>
                                        </td>
                                        <td class="text-end">
                                            <strong
                                                class="text-dark">{{ $result['settings']['CURRENCY_SYMBOL'] ?? '$' }}{{ number_format($installment->amount, 2) }}</strong>
                                            <small class="d-block text-muted">Due:
                                                {{ \Carbon\Carbon::parse($installment->due_date)->format('D, M j') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted p-4">{{ __('No payments due next month.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Income vs Expense Chart --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Income Vs Expense') }}</h4>
                </div>
                <div class="card-body">
                    <div id="incomeExpense"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
