<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display paginated sales data with search and filters.
     */
    public function index(Request $request)
    {
        // Search logic
        $query = Sale::query();
        // if ($request->has('search')) {
        //     $query->where('product', 'LIKE', '%' . $request->search . '%');
        // }

        if ($request->has('filter')) {
            if ($request->filter == 'highest_sales') {
                $query->orderBy('Gross_Sales', 'desc');
            } elseif ($request->filter == 'lowest_sales') {
                $query->orderBy('Gross_Sales', 'asc');
            } elseif ($request->filter == 'latest') {
                $query->orderBy('sale_date', 'desc');
            } elseif ($request->filter == 'oldest') {
                $query->orderBy('sale_date', 'asc');
            }
        }
        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('Product', $request->sort);
        }

        if ($request->has('search')) {
            $query->where('product', 'like', "%{$request->search}%");
        }

        $sales = $query->paginate(10);

        // Summary Data
        $totalProducts = Sale::distinct('product')->count();
        $totalOrders = Sale::count();

        $totalSalesPerProduct = Sale::select('Product', DB::raw('SUM(Sales) as total_sales'))
            ->groupBy('Product')
            ->get();

        return view('sales.index', compact('sales', 'totalProducts', 'totalOrders', 'totalSalesPerProduct'));
    }


    /**
     * Import sales data from a CSV file.
     */
    public function importCSV(Request $request)
    {
        $file = $request->file('csv_file');

        if (!$file) {
            return redirect()->back()->with('error', 'No file uploaded.');
        }

        $data = array_map('str_getcsv', file($file->getPathname()));

        if (empty($data) || count($data) < 2) {
            return redirect()->back()->with('error', 'CSV file is empty or invalid.');
        }

        $header = array_shift($data); // Remove header row

        foreach ($data as $row) {
            if (count($row) < 16)
                continue; // Ensure enough columns exist

            Sale::create([
                'Segment' => trim($row[0]),
                'Country' => trim($row[1]),
                'Product' => trim($row[2]),
                'Discount_Band' => trim($row[3]),
                'Units_Sold' => is_numeric($row[4]) ? (int) $row[4] : 0,
                'Manufacturing_Price' => is_numeric($row[5]) ? (int) $row[5] : 0,
                'Sale_Price' => is_numeric($row[6]) ? (int) $row[6] : 0,
                'Gross_Sales' => is_numeric($row[7]) ? (int) $row[7] : 0,
                'Discounts' => is_numeric($row[8]) ? (int) $row[8] : 0,
                'Sales' => is_numeric($row[9]) ? (int) $row[9] : 0,
                'COGS' => is_numeric($row[10]) ? (int) $row[10] : 0,
                'Profit' => is_numeric($row[11]) ? (int) $row[11] : 0,
                'sale_date' => Carbon::parse(trim($row[12]))->format('Y-m-d'),
                'Month_Number' => is_numeric($row[13]) ? (int) $row[13] : 0,
                'Month_Name' => trim($row[14]),
                'Year' => is_numeric($row[15]) ? (int) $row[15] : 0,
            ]);
        }

        return redirect()->back()->with('success', 'Sales data imported successfully!');
    }

    /**
     * Fetch sales chart data grouped by month.
     */
    public function getSalesChartData()
    {
        $salesData = Sale::selectRaw('MONTH(sale_date) as month, SUM(total_sales_amount) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($salesData);
    }




    public function processSalesData(Request $request)
    {
        $sales = Sale::query();

        if ($request->has('product')) {
            $sales->where('Product', 'LIKE', "%{$request->product}%");
        }
        if ($request->has('year')) {
            $sales->where('Year', $request->year);
        }
        if ($request->has('month')) {
            $sales->where('Month_Name', $request->month);
        }

        if ($request->has('sort_by')) {
            $sales->orderBy($request->sort_by, $request->get('order', 'asc'));
        }

        $salesData = $sales->paginate(10);

        $totalSalesPerProduct = Sale::select('Product', DB::raw('SUM(Sales) as total_sales'))
            ->groupBy('Product')
            ->get();

        $highestSellingProduct = $totalSalesPerProduct->sortByDesc('total_sales')->first();

        $averageSales = Sale::avg('Sales');

        $highestSalesMonth = Sale::select('Month_Name', DB::raw('SUM(Sales) as total_sales'))
            ->groupBy('Month_Name')
            ->orderByDesc('total_sales')
            ->first();

        $salesGraphData = Sale::select('Month_Name', DB::raw('SUM(Sales) as total_sales'))
            ->groupBy('Month_Name')
            ->orderByRaw("FIELD(Month_Name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();

        return view('sales.report', compact('salesData', 'totalSalesPerProduct', 'highestSellingProduct', 'averageSales', 'highestSalesMonth', 'salesGraphData'));
    }





    public function getHighestSellingProduct()
    {
        $highestSellingProduct = Sale::select('Product', DB::raw('SUM(Sales) as total_sales'))
            ->groupBy('Product')
            ->orderByDesc('total_sales')
            ->first();

        return response()->json($highestSellingProduct);
    }
}
