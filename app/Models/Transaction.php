<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // جدول مرتبط (اختیاری اگر اسم جدول transactions باشد)
    protected $table = 'transactions';

    // فیلدهایی که قابل مقداردهی دسته‌جمعی هستند
    protected $fillable = [
        'person_id',
        'type',
        'amount',
        'description',
    ];

    /**
     * ارتباط با مدل Person (هر تراکنش متعلق به یک شخص است)
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * تعیین نوع تراکنش (برای نمایش فارسی یا رنگی...)
     */
    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case 'income':    return 'دریافتی';
            case 'expense':   return 'هزینه';
            case 'receive':   return 'دریافت قرض';
            case 'pay':       return 'پرداخت قرض';
            case 'debt':      return 'بدهی جدید';
            case 'credit':    return 'طلب جدید';
            default:          return $this->type;
        }
    }
}
