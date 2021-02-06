<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'contact_id';

    protected $guarded = [
        'created',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'contact_fk', 'contact_id');
    }


}
