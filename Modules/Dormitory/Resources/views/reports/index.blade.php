@extends('core::layouts.master')

@php
    $page_id = 'dormitory_reports';
    $page_name = 'reports';
    $page_title = 'Dormitory Reports';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div>
        <h1>{{ $dormitory_name }} Reports</h1>
        <div id="chartdiv"></div>
        <h2>Monthly Rent Payments</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Monthly Rent</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyRents as $rent)
                    <tr>
                        <td>{{ $rent->created_at->format('Y-m') }}</td>
                        <td>{{ $rent->monthly_rent }}</td>
                        <td>{{ $rent->payment_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        <h2>Meter</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total</th>
                    <th>Payment Status</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dormitory_meters as $meter)
                    <tr>
                        <td>{{ $meter->created_at->format('Y-m') }}</td>
                        <td>{{ $meter->Total }}</td>
                        <td>{{ $meter->payment_status }}</td>
                        <td>{{ $meter->type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('css')
    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
@endsection

@section('js_link')
@endsection

@section('js_script')
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
    am5.ready(function() {
        var chartData = @json($chartData);

        var root = am5.Root.new("chartdiv");
        root._logo.dispose();

        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        var chart = root.container.children.push(am5percent.PieChart.new(root, {
            innerRadius: 100,
            layout: root.verticalLayout
        }));

        var series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: "size",
            categoryField: "sector"
        }));

        var months = Object.keys(chartData);
        var currentMonthIndex = 0;

        function getCurrentData() {
            var month = months[currentMonthIndex];
            var data = [
                { sector: "monthly_rent", size: chartData[month]['monthly_rent'] },
                { sector: "Total", size: chartData[month]['Total'] },
            ];
            currentMonthIndex++;
            if (currentMonthIndex >= months.length) currentMonthIndex = 0;
            return { month: month, data: data };
        }

        var label = root.tooltipContainer.children.push(am5.Label.new(root, {
            x: am5.p50,
            y: am5.p50,
            centerX: am5.p50,
            centerY: am5.p50,
            fill: am5.color(0x000000),
            fontSize: 50
        }));

        function loop() {
            var currentData = getCurrentData();
            label.set("text", "Month " + currentData.month);
            series.data.setAll(currentData.data);
            chart.setTimeout(loop, 4000);
        }

        loop();
    });
</script>
@endsection
