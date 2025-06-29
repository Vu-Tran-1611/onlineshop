<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $shopProfile = auth()->user()->shop_profile;
        // Revenue data by Months from Jan to Dec 
        $revenueData = [
            'Jan' => $shopProfile->getRevenueByMonthAndYear(1, date('Y')),
            'Feb' => $shopProfile->getRevenueByMonthAndYear(2, date('Y')),
            'Mar' => $shopProfile->getRevenueByMonthAndYear(3, date('Y')),
            'Apr' => $shopProfile->getRevenueByMonthAndYear(4, date('Y')),
            'May' => $shopProfile->getRevenueByMonthAndYear(5, date('Y')),
            'Jun' => $shopProfile->getRevenueByMonthAndYear(6, date('Y')),
            'Jul' => $shopProfile->getRevenueByMonthAndYear(7, date('Y')),
            'Aug' => $shopProfile->getRevenueByMonthAndYear(8, date('Y')),
            'Sep' => $shopProfile->getRevenueByMonthAndYear(9, date('Y')),
            'Oct' => $shopProfile->getRevenueByMonthAndYear(10, date('Y')),
            'Nov' => $shopProfile->getRevenueByMonthAndYear(11, date('Y')),
            'Dec' => $shopProfile->getRevenueByMonthAndYear(12, date('Y')),
        ];
        $totalRevenue = array_sum($revenueData);
        $lowestRevenue = min($revenueData);
        $highestRevenue = max($revenueData);
        $lowestMonth = array_search($lowestRevenue, $revenueData);
        $highestMonth = array_search(max($revenueData), $revenueData);


        // Number of Orders Over time 
        $ordersData = [
            'Jan' => $shopProfile->getOrderCountByMonthAndYear(1, date('Y')),
            'Feb' => $shopProfile->getOrderCountByMonthAndYear(2, date('Y')),
            'Mar' => $shopProfile->getOrderCountByMonthAndYear(3, date('Y')),
            'Apr' => $shopProfile->getOrderCountByMonthAndYear(4, date('Y')),
            'May' => $shopProfile->getOrderCountByMonthAndYear(5, date('Y')),
            'Jun' => $shopProfile->getOrderCountByMonthAndYear(6, date('Y')),
            'Jul' => $shopProfile->getOrderCountByMonthAndYear(7, date('Y')),
            'Aug' => $shopProfile->getOrderCountByMonthAndYear(8, date('Y')),
            'Sep' => $shopProfile->getOrderCountByMonthAndYear(9, date('Y')),
            'Oct' => $shopProfile->getOrderCountByMonthAndYear(10, date('Y')),
            'Nov' => $shopProfile->getOrderCountByMonthAndYear(11, date('Y')),
            'Dec' => $shopProfile->getOrderCountByMonthAndYear(12, date('Y')),
        ];

        $totalOrders = array_sum($ordersData);
        $peakOrders = max($ordersData);
        $peakMonth = array_search($peakOrders, $ordersData);
        $averageOrders = $totalOrders > 0 ? $totalOrders / count($ordersData) : 0;

        // Top Ten Selling Products
        $topTenProducts = $shopProfile->getTopTenSellingProducts();
        dd($topTenProducts);

        return view(
            'vendor.dashboard',
            [
                'revenueData' => $revenueData,
                'totalRevenue' => $totalRevenue,
                'lowestRevenue' => $lowestRevenue,
                'highestRevenue' => $highestRevenue,
                'lowestMonth' => $lowestMonth,
                'highestMonth' => $highestMonth,
                'ordersData' => $ordersData,
                'totalOrders' => $totalOrders,
                'peakOrders' => $peakOrders,
                'peakMonth' => $peakMonth,
                'averageOrders' => $averageOrders,
            ]
        );
    }
}
