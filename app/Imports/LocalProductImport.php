<?php

namespace App\Imports;

use App\Models\Admin\LocalProduct;
use App\Models\Admin\Product;
use App\Models\Admin\Kardex;
use App\Models\Admin\ProductEntry;
use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;

class LocalProductImport implements ToCollection, WithStartRow
{
    protected $company_id, $user_id, $local_id;
    
    public function __construct($company_id, $local_id, $user_id){
        $this->user_id = $user_id;
        $this->company_id = $company_id;
        $this->local_id = $local_id;
    } 

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // dd($row[0], $row[1], $row[3]);

            if($row[3] != 0){
                Product::where('id', $row[0])->increment('stock', $row[3]);

                if(LocalProduct::where('local_id', $this->local_id)->where('product_id', $row[0])->exists()){
                    LocalProduct::where('local_id', $this->local_id)->where('product_id', $row[0])->increment('stock', $row[3]);
                }else{
                    LocalProduct::create(['user_id'=>$this->user_id, 'local_id'=>$this->local_id, 'product_id'=>$row[0], 'stock'=>$row[3], 'approved'=>1]);
                }

                ProductEntry::create(['company_id'=>$this->company_id, 'user_id'=>$this->user_id, 'local_id'=>$this->local_id, 'product_id'=>$row[0], 'amount'=>$row[3], 'cost'=>1]);
                    
                $karde = Kardex::where('local_id', $this->local_id)
                        ->where('product_id', $row[0])
                        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                        ->first();
        
                if($karde){
                    $karde->increment('entry', $row[3]);
                }
                else{
                    Kardex::create(['company_id'=>$this->company_id, 'local_id'=>$this->local_id, 'product_id'=>$row[0], 'entry'=>$row[3], 'output'=>0]);
                } 
            }
            // LocalProduct::create([
            //     'name' => $row[0],
            // ]);
        }
    }

     public function startRow(): int
    {
        return 2;
    }

    // public function headingRow(): int
    // {
    //     return 2;
    // }

    // public function model(array $row)
    // {
    //     return new LocalProduct([
    //         //
    //     ]);
    // }
}
