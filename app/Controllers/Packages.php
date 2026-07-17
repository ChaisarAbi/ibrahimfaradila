<?php namespace App\Controllers;

use App\Models\PackageModel;

class Packages extends BaseController
{
    public function index()
    {
        $model = new PackageModel();
        $data = [
            'title'    => 'Data Paket',
            'packages' => $model->findAll()
        ];
        return $this->render('packages/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Paket'];
        return $this->render('packages/create', $data);
    }

    public function store()
    {
        $model = new PackageModel();
        $model->save([
            'name'       => $this->request->getVar('name'),
            'weight_type' => $this->request->getVar('weight_type'),
            'min_weight' => $this->request->getVar('min_weight'),
            'max_weight' => $this->request->getVar('max_weight'),
            'box_count'  => $this->request->getVar('box_count'),
            'price'      => $this->request->getVar('price'),
            'is_special' => $this->request->getVar('is_special') ? 1 : 0,
        ]);
        return redirect()->to('/admin/packages')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $model = new PackageModel();
        $data = [
            'title'   => 'Edit Paket',
            'package' => $model->find($id)
        ];
        return $this->render('packages/edit', $data);
    }

    public function update($id = null)
    {
        $model = new PackageModel();
        $model->update($id, [
            'name'       => $this->request->getVar('name'),
            'weight_type' => $this->request->getVar('weight_type'),
            'min_weight' => $this->request->getVar('min_weight'),
            'max_weight' => $this->request->getVar('max_weight'),
            'box_count'  => $this->request->getVar('box_count'),
            'price'      => $this->request->getVar('price'),
            'is_special' => $this->request->getVar('is_special') ? 1 : 0,
        ]);
        return redirect()->to('/admin/packages')->with('success', 'Paket berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $model = new PackageModel();
        $model->delete($id);
        return redirect()->to('/admin/packages')->with('success', 'Paket berhasil dihapus.');
    }
}