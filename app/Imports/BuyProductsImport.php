<?php

namespace App\Imports;

use App\Models\Biller\TempBuy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BuyProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new TempBuy([
            'company_id'=> $row['company'],
            'user_id'=> $row['user'],
            'code'=> $row['code'],
            'product_id'=> $row['product_id'],
            'cost'=> $row['cost'],
            'stock'=> $row['stock'],
            'status' => $row['status'],
        ]);
    }
}
