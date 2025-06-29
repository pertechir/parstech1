<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'person_id',
        'date',
        'text',
        'is_done'
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_done' => 'boolean'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
