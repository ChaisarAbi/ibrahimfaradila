<?php namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';
    protected $allowedFields = [
        'customer_id', 'package_id', 'animal_type', 'animal_gender',
        'jumlah_anak', 'slaughter_date', 'delivery_date', 'slaughter_time',
        'penyembelihan', 'use_photo_card', 'use_photo_certificate',
        'photo_path', 'status', 'total_price'
    ];
    protected $useTimestamps = false;
    
    public function getOrdersWithCustomer()
    {
        return $this->select('orders.*, customers.name as customer_name, customers.child_name, customers.phone, customers.address, packages.name as package_name')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->findAll();
    }
    
    public function getTodayOrders()
    {
        return $this->where('slaughter_date', date('Y-m-d'))
            ->where('status !=', 'Cancelled')
            ->findAll();
    }
    
    public function getMonthlyRevenue()
    {
        $month = date('m');
        $year = date('Y');
        return $this->selectSum('total_price')
            ->where('MONTH(created_at)', $month)
            ->where('YEAR(created_at)', $year)
            ->where('status !=', 'Cancelled')
            ->get()->getRow()->total_price ?? 0;
    }
}