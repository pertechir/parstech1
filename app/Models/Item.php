<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // فیلدهای قابل مقداردهی انبوه
    protected $fillable = [
        'name',
        'code',
        'warehouse_id',
        'unit',
        'stock',
        'min_stock',
        'description',
        // هر فیلد دیگری که نیاز داری اضافه کن
    ];

    // اگر هر آیتم به یک انبار تعلق دارد
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
