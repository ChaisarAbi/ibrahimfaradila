<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    protected $globalData = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load sidebar global data if user is logged in
        if (session()->has('isLoggedIn')) {
            $stockModel = new \App\Models\StockModel();
            $orderModel = new \App\Models\OrderModel();
            $notifModel = new \App\Models\NotificationModel();
            
            // Count low stock items (quantity <= min_threshold)
            $lowStockCount = 0;
            $stocks = $stockModel->findAll();
            foreach ($stocks as $s) {
                if ($s['quantity'] <= $s['min_threshold']) {
                    $lowStockCount++;
                }
            }
            
            $this->globalData = [
                'low_stock_count'      => $lowStockCount,
                'pending_orders_count' => $orderModel->where('status', 'Pending')->countAllResults(),
                'unread_notifications' => $notifModel->where('is_read', 0)->countAllResults(),
            ];
        }
    }
    
    /**
     * Render a view with global data merged.
     */
    protected function render(string $view, array $data = []): string
    {
        return view($view, array_merge($this->globalData, $data));
    }
}
