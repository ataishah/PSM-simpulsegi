<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TodaysOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPlacedNotification;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    function index(TodaysOrderDataTable $dataTable) : View|JsonResponse
    {
        $todaysOrders = Order::whereDate('created_at', now()->format('Y-m-d'))->count();
        $todaysEarnings = Order::whereDate('created_at', now()->format('Y-m-d'))->where('order_status', 'delivered')->sum('grand_total');

        $thisMonthsOrders = Order::whereMonth('created_at', now()->month)->count();
        $thisMonthsEarnings = Order::whereMonth('created_at', now()->month)->where('order_status', 'delivered')->sum('grand_total');

        $thisYearOrders = Order::whereYear('created_at', now()->year)->count();
        $thisYearEarnings = Order::whereYear('created_at', now()->year)->where('order_status', 'delivered')->sum('grand_total');

        $totalOrders = Order::count();
        $totalEarnings = Order::where('order_status', 'delivered')->sum('grand_total');

        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $totalProducts = Product::count();

        $productSales = OrderItem::select('products.name as product_name', DB::raw('SUM(order_items.unit_price * order_items.qty) as sales'))
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->groupBy('order_items.product_id')
        ->get();

        $salesData = [
            'labels' => [], // Array to hold the labels (dates)
            'datasets' => [
                [
                    'label' => 'Sales Performance Over Time',
                    'data' => [], // Array to hold the sales data corresponding to each date
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'lineTension' => 0.1
                ]
            ]
        ];

        // Get sales data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        $salesDataQuery = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', 'delivered')
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total_sales')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Fill in the sales data array
        foreach ($salesDataQuery as $data) {
            $salesData['labels'][] = $data->date;
            $salesData['datasets'][0]['data'][] = $data->total_sales;
        }

        return $dataTable->render('admin.dashboard.index', compact(
            'todaysOrders',
            'todaysEarnings',
            'thisMonthsOrders',
            'thisMonthsEarnings',
            'thisYearOrders',
            'thisYearEarnings',
            'totalOrders',
            'totalEarnings',
            'totalUsers',
            'totalAdmins',
            'totalProducts',
            'productSales',
            'salesData',
        ));
    }

    function clearNotification() {
        $notification = OrderPlacedNotification::query()->update(['seen' => 1]);

        toastr()->success('Notification Cleared Successfully!');
        return redirect()->back();
    }
}
