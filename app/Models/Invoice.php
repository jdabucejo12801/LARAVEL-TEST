<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';

    

  
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

     public static function getInvoices()
    {
        return self::all();   // same as Invoice::all()
    }
   
    
}
