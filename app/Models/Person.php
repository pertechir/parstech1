<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = [
        'accounting_code','total_purchases', 'last_transaction_at', 'total_sales', 'balance', 'status','type', 'first_name', 'last_name', 'nickname', 'credit_limit', 'price_list', 'tax_type',
        'national_code', 'economic_code', 'registration_number', 'branch_code', 'description', 'address', 'country',
        'province', 'city', 'postal_code', 'phone', 'mobile', 'fax', 'phone1', 'phone2', 'phone3', 'email', 'website',
        'birth_date', 'marriage_date', 'join_date', 'company_name', 'title'
    ];

    protected $casts = [
        'total_purchases' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'balance' => 'decimal:2',
        'last_transaction_at' => 'datetime',
    ];


    protected $appends = ['display_name'];

    public function getDisplayNameAttribute()
    {
        if (!empty($this->company_name)) {
            return $this->company_name;
        }

        $name = trim($this->first_name . ' ' . $this->last_name);
        if (!empty($name)) {
            return $name;
        }

        return $this->nickname ?? 'بدون نام';
    }

    // محاسبه نام کامل
    public function getFullNameAttribute()
    {
        if (!empty($this->company_name)) {
            return $this->company_name;
        }

        $name = trim($this->first_name . ' ' . $this->last_name);
        if (!empty($name)) {
            return $name;
        }

        return $this->nickname ?? 'بدون نام';
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'person_id');
    }

    public static function validateNationalCode($code)
    {
        if (!preg_match('/^[0-9]{10}$/', $code)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        }

        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));

        if ($ret < 2) {
            return $ret == $parity;
        }
        return (11 - $ret) == $parity;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    // ارتباط با فروش‌ها
    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }


    public function lastTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    // ارتباط با خریدها
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'customer_id');
    }

    // محاسبه مانده حساب
    public function getBalanceAttribute()
    {
        return ($this->total_sales ?? 0) - ($this->total_purchases ?? 0);
    }

    public function purchasesTotal()
    {
        return $this->sales()->sum('total_price');
    }

    public function purchasesCount()
    {
        return $this->sales()->count();
    }

    public function lastPurchase()
    {
        return $this->sales()->latest()->first();
    }

    public function ownedProducts()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'product_shareholder', 'person_id', 'product_id')
            ->withPivot('percent');
    }

    // Helper method برای به‌روزرسانی مبالغ
    public function updateFinancials()
    {
        // محاسبه مجموع فروش‌ها با استفاده از final_amount
        $this->total_sales = $this->sales()
            ->where('status', '!=', 'cancelled')
            ->sum('final_amount') ?? 0;

        // محاسبه مجموع پرداخت‌ها با استفاده از paid_amount
        $this->total_purchases = $this->sales()
            ->where('status', '!=', 'cancelled')
            ->sum('paid_amount') ?? 0;

        // محاسبه مانده حساب
        $this->balance = $this->total_sales - $this->total_purchases;

        // به‌روزرسانی تاریخ آخرین تراکنش
        $lastSale = $this->sales()->latest()->first();
        if ($lastSale) {
            $this->last_transaction_at = $lastSale->created_at;
        }

        $this->save();
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function getTypeLabelAttribute()
    {
        return [
            'customer'    => 'مشتری',
            'supplier'    => 'تأمین‌کننده',
            'shareholder' => 'سهامدار',
            'employee'    => 'کارمند',
        ][$this->type] ?? $this->type;
    }

}
