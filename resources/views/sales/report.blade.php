@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sales Report</h2>
    
    <form method="GET" action="{{ route('sales.report') }}" class="mb-3">
        <input type="text" name="product" placeholder="Search Product" value="{{ request('product') }}">
        <select name="year">
            <option value="">Select Year</option>
            @foreach(range(2020, date('Y')) as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Units Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $sale)
            <tr>
                <td>{{ $sale->Product }}</td>
                <td>{{ $sale->Units_Sold }}</td>
                <td>{{ $sale->Sales }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $salesData->links() }}

    <canvas id="salesChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($salesGraphData->pluck('Month_Name')),
            datasets: [{
                label: 'Sales',
                data: @json($salesGraphData->pluck('total_sales')),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
    });
</script>
@endsection