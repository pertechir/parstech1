<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    // اینجا می‌توانی متدها یا ارتباطات دیگر اضافه کنی
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'business_user', 'tenant_id', 'user_id');
    }
}
