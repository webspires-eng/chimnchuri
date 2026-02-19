<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {


        $totalOrders = Order::count();

        // Orders This Week
        $ordersThisWeek = Order::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        // Orders Last Week
        $ordersLastWeek = Order::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();

        $orderWeekChange = $this->calculateChange($ordersThisWeek, $ordersLastWeek);


        $todayOrders = Order::whereDate('created_at', today())->count();

        $totalRevenue = Order::where('order_status', 'completed')->sum('grand_total');

        // Revenue Comparison
        $revenueThisMonth = Order::where('order_status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('grand_total');

        $revenueLastMonth = Order::where('order_status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('grand_total');

        $revenueChange = $this->calculateChange($revenueThisMonth, $revenueLastMonth);


        $pendingOrders = Order::where('order_status', 'pending')->count();


        $pendingThisMonth = Order::where("order_status", "pending")->whereMonth("created_at", now()->month)->count();
        $pendingLastMonth = Order::where("order_status", "pending")->whereMonth("created_at", now()->subMonth()->month)->count();


        $pendingChange = $this->calculateChange($pendingThisMonth, $pendingLastMonth);





        $completedOrders = Order::where('order_status', 'completed')->count();

        $customers = User::where('role', 'customer')->count();

        $recentOrders = Order::latest()->take(10)->get();

        // Customer Conversion (Last 30 Days)
        $last30DaysStart = now()->subDays(30);

        // A customer is identified by user_id if present, otherwise by customer_email
        $activeIdentifiers = DB::table('orders')
            ->select(DB::raw('DISTINCT CASE WHEN user_id IS NOT NULL THEN CAST(user_id AS CHAR) ELSE customer_email END as identifier'))
            ->where('created_at', '>=', $last30DaysStart)
            ->where(function ($query) {
                $query->whereNotNull('user_id')
                    ->orWhereNotNull('customer_email');
            })
            ->pluck('identifier');

        $totalCustomersCount = $activeIdentifiers->count();

        $returningCustomersCount = 0;
        if ($totalCustomersCount > 0) {
            $returningCustomersCount = DB::table('orders')
                ->select(DB::raw('DISTINCT CASE WHEN user_id IS NOT NULL THEN CAST(user_id AS CHAR) ELSE customer_email END as identifier'))
                ->where('created_at', '<', $last30DaysStart)
                ->whereIn(DB::raw('CASE WHEN user_id IS NOT NULL THEN CAST(user_id AS CHAR) ELSE customer_email END'), $activeIdentifiers)
                ->count(DB::raw('DISTINCT CASE WHEN user_id IS NOT NULL THEN CAST(user_id AS CHAR) ELSE customer_email END'));
        }

        $newCustomersCount = $totalCustomersCount - $returningCustomersCount;
        $returningRate = $totalCustomersCount > 0 ? round(($returningCustomersCount / $totalCustomersCount) * 100, 1) : 0;

        // Total quantity sold from all items
        $totalSold = DB::table('order_items')->sum('quantity');

        // Top selling items
        $topItems = DB::table('order_items')
            ->join('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.item_name',
                "order_items.quantity",
                "order_items.size_name",
                "order_items.price",
                DB::raw('SUM(order_items.quantity) as quantity_sold'),
                DB::raw('sum(quantity) as total')
            )
            ->groupBy('items.id', 'order_items.item_name', "order_items.size_name", "order_items.price", "order_items.quantity")
            ->orderByDesc('quantity_sold')
            ->take(10)
            ->get();


        return view('admin.dashboard.index', compact(
            'totalOrders',
            'todayOrders',
            'ordersThisWeek',
            'orderWeekChange',
            'totalRevenue',
            "revenueChange",
            'pendingOrders',
            "pendingChange",
            'completedOrders',
            'customers',
            'recentOrders',
            "topItems",
            "totalSold",
            'newCustomersCount',
            'returningCustomersCount',
            'returningRate'
        ));
    }


    private function calculateChange($current, $previous = 0)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }


    public function getPerformanceData(Request $request)
    {

        // $month = request()->get('month', date('m'));
        $month = request()->get('month', 2);
        $year = request()->get('year', date('Y'));

        $results = DB::table('orders')
            ->select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(grand_total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->get()
            ->keyBy('day');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $reports = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            if (isset($results[$i])) {
                $reports[] = [
                    "days" => $results[$i]->day,
                    "sales" => $results[$i]->total,
                    "orders" => $results[$i]->count,
                ];
            } else {
                $reports[] = [
                    "days" => $i,
                    "sales" => 0,
                    "orders" => 0,
                ];
            }
        }



        $days = [];
        $sales = [];
        $orders = [];
        foreach ($reports as $report) {
            $days[] = $report['days'];
            $sales[] = $report['sales'];
            $orders[] = $report['orders'];
        }

        return response()->json([
            'days' => $days,
            'sales' => $sales,
            'orders' => $orders
        ]);
    }

    public function last12MonthSales(Request $request)
    {
        $startDate = now()->subMonths(11)->startOfMonth();
        $endDate = now()->endOfMonth();

        $results = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(grand_total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('order_status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $labels = [];
        $sales = [];
        $orders = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->year . '-' . $date->month;

            $labels[] = $date->format('M Y');
            if (isset($results[$key])) {
                $sales[] = (float)$results[$key]->total;
                $orders[] = (int)$results[$key]->count;
            } else {
                $sales[] = 0;
                $orders[] = 0;
            }
        }

        return response()->json([
            'months' => $labels,
            'sales' => $sales,
            'orders' => $orders
        ]);
    }
}
