<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\PackageModel;
use App\Models\OrderDetailModel;
use App\Models\BoneMenuModel;
use App\Models\MeatMenuModel;
use App\Controllers\Scheduler;

class Orders extends BaseController
{
    public function index()
    {
        $model = new OrderModel();
        $packageModel = new PackageModel();
        $customerModel = new CustomerModel();
        $data = [
            'title'  => 'Data Pesanan',
            'orders' => $model->getOrdersWithCustomer(),
            'packages' => $packageModel->findAll(),
            'customers' => $customerModel->findAll()
        ];
return $this->render('orders/index', $data);
    }

    public function create()
    {
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $boneModel = new BoneMenuModel();
        $meatModel = new MeatMenuModel();
        
        $data = [
            'title'      => 'Tambah Pesanan',
            'customers'  => $customerModel->findAll(),
            'packages'   => $packageModel->findAll(),
            'bone_menus' => $boneModel->findAll(),
            'meat_menus' => $meatModel->findAll()
        ];
return $this->render('orders/create', $data);
    }

    public function store()
    {
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $customerModel = new CustomerModel();
        
        // Cek apakah customer_id ada (pilih customer existing) atau buat baru
        $customer_id = $this->request->getVar('customer_id');
        $customer_name = $this->request->getVar('customer_name');
        $child_name = $this->request->getVar('child_name');
        $gender = $this->request->getVar('gender');
        $birth_date = $this->request->getVar('birth_date');
        $phone = $this->request->getVar('phone');
        $address = $this->request->getVar('address');
        
        if (!$customer_id && $customer_name) {
            // Buat customer baru
            $customerModel->save([
                'name'       => $customer_name,
                'child_name' => $child_name,
                'gender'     => $gender,
                'birth_date' => $birth_date,
                'phone'      => $phone,
                'address'    => $address,
            ]);
            $customer_id = $customerModel->insertID();
        } elseif ($customer_id) {
            // Existing customer - fetch gender from database (since form fields may be disabled)
            $existingCustomer = $customerModel->find($customer_id);
            if ($existingCustomer) {
                $gender = $existingCustomer['gender'];
                $child_name = $existingCustomer['child_name'];
                $birth_date = $existingCustomer['birth_date'];
            }
        } elseif (!$customer_id && !$customer_name) {
            return redirect()->back()->withInput()->with('error', 'Pilih pelanggan existing atau isi data pelanggan baru.');
        }
        
        // Hitung jumlah_anak based on gender
        $jumlah_anak = ($gender == 'Laki-laki') ? 2 : 1;
        
        $orderModel->save([
            'customer_id'           => $customer_id,
            'package_id'            => $this->request->getVar('package_id'),
            'animal_type'           => $this->request->getVar('animal_type'),
            'animal_gender'         => $this->request->getVar('animal_gender'),
            'jumlah_anak'           => $jumlah_anak,
            'slaughter_date'        => $this->request->getVar('slaughter_date'),
            'delivery_date'         => $this->request->getVar('delivery_date'),
            'slaughter_time'        => $this->request->getVar('slaughter_time'),
            'penyembelihan'         => $this->request->getVar('penyembelihan'),
            'use_photo_card'        => $this->request->getVar('use_photo_card') ? 1 : 0,
            'use_photo_certificate' => $this->request->getVar('use_photo_certificate') ? 1 : 0,
            'status'                => 'Pending',
            'total_price'           => $this->request->getVar('total_price'),
        ]);
        
        $order_id = $orderModel->insertID();
        
        // Handle photo upload (max 2MB)
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if ($photo->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran foto maksimal 2MB.');
            }
            $newName = "customer_{$customer_id}_child.jpg";
            $photo->move('public/uploads/photos', $newName);
            $orderModel->update($order_id, ['photo_path' => 'uploads/photos/' . $newName]);
        }
        
        // Simpan order details - convert empty strings to NULL for FK compatibility
        $bone_menu_id = $this->request->getVar('bone_menu_id');
        $meat_menu_id = $this->request->getVar('meat_menu_id');
        $box_type = $this->request->getVar('box_type');
        $jumlah_box = $this->request->getVar('jumlah_box');
        
        $bone_val = (!empty($bone_menu_id)) ? $bone_menu_id : null;
        $meat_val = (!empty($meat_menu_id)) ? $meat_menu_id : null;
        
        $detailModel->save([
            'order_id'    => $order_id,
            'bone_menu_id' => $bone_val,
            'meat_menu_id' => $meat_val,
            'box_type'    => $box_type ?? 'Box Premium',
            'jumlah_box'  => $jumlah_box ?? 0,
        ]);
        
        // Auto-run scheduler after creating an order
        $scheduler = new Scheduler();
        $scheduler->run();
        
        return redirect()->to('/admin/orders')->with('success', 'Pesanan berhasil ditambahkan dan scheduler dijalankan.');
    }

    public function edit($id = null)
    {
        $orderModel = new OrderModel();
        $customerModel = new CustomerModel();
        $packageModel = new PackageModel();
        $boneModel = new BoneMenuModel();
        $meatModel = new MeatMenuModel();
        $detailModel = new OrderDetailModel();
        
        $order = $orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        $customer = $customerModel->find($order['customer_id']);
        
        $data = [
            'title'     => 'Edit Pesanan',
            'order'     => $order,
            'customer'  => $customer,
            'customers' => $customerModel->findAll(),
            'packages'  => $packageModel->findAll(),
            'bone_menus' => $boneModel->findAll(),
            'meat_menus' => $meatModel->findAll(),
            'details'   => $detailModel->where('order_id', $id)->findAll()
        ];
return $this->render('orders/edit', $data);
    }

    public function update($id = null)
    {
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $customerModel = new CustomerModel();
        
        // Cek apakah customer_id ada atau buat baru
        $customer_id = $this->request->getVar('customer_id');
        $customer_name = $this->request->getVar('customer_name');
        $child_name = $this->request->getVar('child_name');
        $gender = $this->request->getVar('gender');
        $birth_date = $this->request->getVar('birth_date');
        $phone = $this->request->getVar('phone');
        $address = $this->request->getVar('address');
        
        if (!$customer_id && $customer_name) {
            $customerModel->save([
                'name'       => $customer_name,
                'child_name' => $child_name,
                'gender'     => $gender,
                'birth_date' => $birth_date,
                'phone'      => $phone,
                'address'    => $address,
            ]);
            $customer_id = $customerModel->insertID();
        } elseif ($customer_id) {
            // Existing customer - fetch gender from database
            $existingCustomer = $customerModel->find($customer_id);
            if ($existingCustomer) {
                $gender = $existingCustomer['gender'];
                $child_name = $existingCustomer['child_name'];
                $birth_date = $existingCustomer['birth_date'];
            }
        }
        
        $jumlah_anak = ($gender == 'Laki-laki') ? 2 : 1;
        
        $orderModel->update($id, [
            'customer_id'           => $customer_id,
            'package_id'            => $this->request->getVar('package_id'),
            'animal_type'           => $this->request->getVar('animal_type'),
            'animal_gender'         => $this->request->getVar('animal_gender'),
            'jumlah_anak'           => $jumlah_anak,
            'slaughter_date'        => $this->request->getVar('slaughter_date'),
            'delivery_date'         => $this->request->getVar('delivery_date'),
            'slaughter_time'        => $this->request->getVar('slaughter_time'),
            'penyembelihan'         => $this->request->getVar('penyembelihan'),
            'use_photo_card'        => $this->request->getVar('use_photo_card') ? 1 : 0,
            'use_photo_certificate' => $this->request->getVar('use_photo_certificate') ? 1 : 0,
            'total_price'           => $this->request->getVar('total_price'),
        ]);
        
        // Hapus details lama, buat baru
        $detailModel->where('order_id', $id)->delete();
        
        $bone_menu_id = $this->request->getVar('bone_menu_id');
        $meat_menu_id = $this->request->getVar('meat_menu_id');
        $box_type = $this->request->getVar('box_type');
        $jumlah_box = $this->request->getVar('jumlah_box');
        
        $bone_val = (!empty($bone_menu_id)) ? $bone_menu_id : null;
        $meat_val = (!empty($meat_menu_id)) ? $meat_menu_id : null;
        
        $detailModel->save([
            'order_id'    => $id,
            'bone_menu_id' => $bone_val,
            'meat_menu_id' => $meat_val,
            'box_type'    => $box_type ?? 'Box Premium',
            'jumlah_box'  => $jumlah_box ?? 0,
        ]);
        
        return redirect()->to('/admin/orders')->with('success', 'Pesanan berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $detailModel->where('order_id', $id)->delete();
        $orderModel->delete($id);
        return redirect()->to('/admin/orders')->with('success', 'Pesanan berhasil dihapus.');
    }
    
    public function stats()
    {
        $orderModel = new OrderModel();
        $status_counts = [
            'Pending' => $orderModel->where('status', 'Pending')->countAllResults(),
            'Processing' => $orderModel->where('status', 'Processing')->countAllResults(),
            'Completed' => $orderModel->where('status', 'Completed')->countAllResults(),
            'Scheduled' => $orderModel->where('status', 'Scheduled')->countAllResults(),
        ];
        return $this->response->setJSON(['status_counts' => $status_counts]);
    }
    
    public function getPackageInfo($id = null)
    {
        $packageModel = new PackageModel();
        $package = $packageModel->find($id);
        if ($package) {
            return $this->response->setJSON($package);
        }
        return $this->response->setJSON(['error' => 'Package not found']);
    }
    
    public function pendingCount()
    {
        $orderModel = new OrderModel();
        $count = $orderModel->where('status', 'Pending')->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }
}
