<?php namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id_schedule';
    protected $allowedFields = ['order_id', 'slaughter_date', 'priority', 'status'];
    protected $useTimestamps = false;
    
    public function getSchedulesWithDetails()
    {
        return $this->select('schedules.*, orders.customer_id, orders.slaughter_date as order_date, 
            customers.name as customer_name, customers.phone, 
            packages.name as package_name, packages.weight_type,
            orders.status as order_status')
            ->join('orders', 'orders.id_order = schedules.order_id')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->join('packages', 'packages.id_package = orders.package_id')
            ->orderBy('schedules.priority', 'ASC')
            ->findAll();
    }
    
    public function getCalendarEvents()
    {
        return $this->select('schedules.*, customers.name as title, 
            orders.status as order_status, orders.slaughter_date as start')
            ->join('orders', 'orders.id_order = schedules.order_id')
            ->join('customers', 'customers.id_customer = orders.customer_id')
            ->findAll();
    }
}