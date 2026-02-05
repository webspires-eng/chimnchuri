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


        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total');




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
            ->orderByDesc('quantity_sold',)
            ->take(10)
            ->get();

        // $topItems = OrderItem::select('item_name', "size_name", "price", DB::raw('sum(quantity) as total'))
        //     ->groupBy('item_name', "size_name", "price")
        //     ->orderByDesc('total')
        //     ->take(10)
        //     ->get();


        // $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
        //     ->groupBy('month')
        //     ->pluck('revenue');






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


        $period = $request->get('period', '1Y'); // ALL, 1M, 6M, 1Y

        $startDate = match ($period) {
            '1M' => now()->subMonth()->startOfMonth(),
            '6M' => now()->subMonths(6)->startOfMonth(),
            '1Y' => now()->subYear()->startOfMonth(),
            default => now()->subYear()->startOfMonth(),
        };

        // Get actual data from database with YEAR included
        $data = DB::table('orders')
            ->selectRaw('
        DATE_FORMAT(created_at, "%b") as month,
        YEAR(created_at) as year,
        MONTH(created_at) as month_num,
        COUNT(*) as total_orders,
        COALESCE(SUM(grand_total), 0) as revenue
    ')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', now())
            ->groupBy('year', 'month_num', 'month')
            ->orderBy('year')
            ->orderBy('month_num')
            ->get()
            ->keyBy(function ($item) {
                // Create unique key with year and month: "2024-01", "2024-02", etc.
                return $item->year . '-' . str_pad($item->month_num, 2, '0', STR_PAD_LEFT);
            });

        // Generate all months in the period
        $allMonths = [];
        $currentDate = $startDate->copy();
        $endDate = now();

        while ($currentDate->lte($endDate)) {
            $monthName = $currentDate->format('M');
            $year = $currentDate->year;
            $monthNum = $currentDate->month;

            // Create unique key for lookup
            $key = $year . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT);

            // Check if data exists for this month
            if (isset($data[$key])) {
                $allMonths[] = [
                    'month' => $monthName,
                    'total_orders' => (int) $data[$key]->total_orders,
                    'revenue' => (float) $data[$key]->revenue,
                ];
            } else {
                // No data for this month, add zeros
                $allMonths[] = [
                    'month' => $monthName,
                    'total_orders' => 0,
                    'revenue' => 0,
                ];
            }

            $currentDate->addMonth();
        }

        return response()->json([
            'months' => collect($allMonths)->pluck('month')->values(),
            'orders' => collect($allMonths)->pluck('total_orders')->values(),
            'revenue' => collect($allMonths)->pluck('revenue')->values(),
        ]);
    }
}
