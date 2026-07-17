<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\StockModel;
use App\Models\ScheduleModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $stockModel = new StockModel();
        
        // Get total stock count
        $stocks = $stockModel->findAll();
        $total_stock = 0;
        foreach ($stocks as $s) {
            $total_stock += $s['quantity'];
        }
        
        // Get recent orders with join to customers and packages
        $db = \Config\Database::connect();
        $recent_orders = $db->table('orders')
            ->select('orders.*, customers.name as customer_name, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
        
        $data = [
            'title' => 'Dashboard',
            'total_orders' => $orderModel->countAllResults(),
            'today_orders' => count($orderModel->getTodayOrders()),
            'total_customers' => $customerModel->countAllResults(),
            'stock_hewan' => $stockModel->where('category', 'hewan')->findAll(),
            'total_stock' => $total_stock,
            'monthly_revenue' => $orderModel->getMonthlyRevenue(),
            'recent_orders' => $recent_orders,
        ];
        
        return $this->render('dashboard/index', $data);
    }
}
