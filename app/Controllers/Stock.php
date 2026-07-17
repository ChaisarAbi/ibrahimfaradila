<?php namespace App\Controllers;

use App\Models\StockModel;

class Stock extends BaseController
{
    public function index()
    {
        $model = new StockModel();
        $data = [
            'title' => 'Manajemen Stok',
            'stocks' => $model->findAll()
        ];
        return $this->render('stock/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Stok'];
        return $this->render('stock/create', $data);
    }

    public function store()
    {
        $model = new StockModel();
        $model->save([
            'item_name'    => $this->request->getVar('item_name'),
            'category'     => $this->request->getVar('category'),
            'quantity'     => $this->request->getVar('quantity'),
            'min_threshold' => $this->request->getVar('min_threshold'),
            'unit'         => $this->request->getVar('unit'),
        ]);
        return redirect()->to('/admin/stock')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $model = new StockModel();
        $data = [
            'title' => 'Edit Stok',
            'stock' => $model->find($id)
        ];
        return $this->render('stock/edit', $data);
    }

    public function update($id = null)
    {
        $model = new StockModel();
        $model->update($id, [
            'item_name'    => $this->request->getVar('item_name'),
            'category'     => $this->request->getVar('category'),
            'quantity'     => $this->request->getVar('quantity'),
            'min_threshold' => $this->request->getVar('min_threshold'),
            'unit'         => $this->request->getVar('unit'),
        ]);
        return redirect()->to('/admin/stock')->with('success', 'Stok berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $model = new StockModel();
        if (!$id || !$model->find($id)) {
            return redirect()->to('/admin/stock')->with('error', 'Stok tidak ditemukan.');
        }
        $model->delete($id);
        return redirect()->to('/admin/stock')->with('success', 'Stok berhasil dihapus.');
    }
}