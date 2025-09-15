<?php

namespace App\Imports;

use App\Models\Admin\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{

    protected $user_id, $company_id;
    
    public function __construct($company_id, $user_id){
        $this->user_id = $user_id;
        $this->company_id = $company_id;
    }

    public function model(array $row)
    {
        return new Product([
            'company_id'=>$this->company_id, 
            'user_id'=>$this->user_id, 
            'name'=>$row['nombre'], 
            'description'=>$row['descripcion'],
            'price'=>$row['precio'],
            'category_id'=>1, 
            'stock'=>0,
        ]);
    }
}
