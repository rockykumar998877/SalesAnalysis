<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .pagination {
            margin-top: 20px;
        }

        .pagination .page-item .page-link {
            color: #007bff;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #007bff;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .page-item .page-link:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Sales Dashboard</h2>

        <!-- Summary Cards -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <h5>Total Products</h5>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <h5>Total Sales Per Product</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <h5>Highest Sales Month</h5>
                    <h3>{{ $highestSalesMonth->month ?? 'N/A' }}</h3>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <form class="mt-3 d-flex align-items-center" action="{{ route('sales.index') }}"
            style="max-width: 600px; width: 100%;">
            <input type="text" class="form-control form-control-md me-2" name="search"
                placeholder="Search" value="{{ request('search') }}">


            <select name="filter" class="form-select me-2">
                <option value="">Filter by</option>
                <option value="highest_sales" {{ request('filter') == 'highest_sales' ? 'selected' : '' }}>Highest Sales
                </option>
                <option value="lowest_sales" {{ request('filter') == 'lowest_sales' ? 'selected' : '' }}>Lowest Sales
                </option>
                <option value="latest" {{ request('filter') == 'latest' ? 'selected' : '' }}>Latest Sales</option>
                <option value="oldest" {{ request('filter') == 'oldest' ? 'selected' : '' }}>Oldest Sales</option>
            </select>

            <select name="sort" class="form-select me-2">
                <option value="">Sort by</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>

            <button class="btn btn-md btn-primary" type="submit">Search</button>
        </form>

        <!-- Sales Table -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th>Sale Price</th>
                        <th>Gross Sales</th>
                        <th>Net Sales</th>
                        <th>Sale Date</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->Product ?? ' ' }}</td>
                            <td>{{ $sale->Units_Sold ?? ' ' }}</td>
                            <td>{{ $sale->Sale_Price ?? ' ' }}</td>
                            <td>{{ $sale->Gross_Sales ?? ' ' }}</td>
                            <td>{{ $sale->Sales ?? ' ' }}</td>
                            <td>{{ $sale->sale_date ?? ' ' }}</td>
                            <td>{{ $sale->Year ?? ' ' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $sales->links('vendor.pagination.bootstrap-5') }}
        </div>

        <!-- Import CSV Section -->
        <!-- <form class="mt-4" enctype="multipart/form-data">
            <label class="form-label">Import Sales Data (CSV)</label>
            <input type="file" class="form-control mb-2" id="csvFile">
            <button class="btn btn-success">Upload</button>
        </form> -->
        <form action="{{ url('/sales/import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file">
            <button type="submit">Import CSV</button>
        </form>

        <!-- Sales Chart -->
        <div class="mt-4">
            <h5>Sales Over Time</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("salesChart").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["January", "February", "March", "April", "May", "June"],
                    datasets: [{
                        label: "Sales",
                        data: [5000, 7000, 8000, 6000, 12000, 15000],
                        borderColor: "blue",
                        fill: false
                    }]
                }
            });
        });
    </script>
</body>

</html>