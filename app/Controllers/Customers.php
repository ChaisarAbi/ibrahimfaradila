<?php namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index()
    {
        $model = new CustomerModel();
        $data = [
            'title' => 'Data Pelanggan',
            'customers' => $model->findAll()
        ];
        return $this->render('customers/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Pelanggan'];
        return $this->render('customers/create', $data);
    }

    public function store()
    {
        $model = new CustomerModel();
        $model->save([
            'name'       => $this->request->getVar('name'),
            'child_name' => $this->request->getVar('child_name'),
            'gender'     => $this->request->getVar('gender'),
            'birth_date' => $this->request->getVar('birth_date'),
            'phone'      => $this->request->getVar('phone'),
            'address'    => $this->request->getVar('address'),
        ]);
        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $model = new CustomerModel();
        $data = [
            'title'    => 'Edit Pelanggan',
            'customer' => $model->find($id)
        ];
        return $this->render('customers/edit', $data);
    }

    public function update($id = null)
    {
        $model = new CustomerModel();
        $model->update($id, [
            'name'       => $this->request->getVar('name'),
            'child_name' => $this->request->getVar('child_name'),
            'gender'     => $this->request->getVar('gender'),
            'birth_date' => $this->request->getVar('birth_date'),
            'phone'      => $this->request->getVar('phone'),
            'address'    => $this->request->getVar('address'),
        ]);
        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $model = new CustomerModel();
        $orderModel = new \App\Models\OrderModel();
        
        // Check if customer has orders - prevent cascade delete
        $orderCount = $orderModel->where('customer_id', $id)->countAllResults();
        if ($orderCount > 0) {
            return redirect()->to('/admin/customers')->with('error', "Pelanggan tidak dapat dihapus karena masih memiliki {$orderCount} pesanan terkait. Hapus pesanan terlebih dahulu.");
        }
        
        $model->delete($id);
        return redirect()->to('/admin/customers')->with('success', 'Pelanggan berhasil dihapus.');
    }
}