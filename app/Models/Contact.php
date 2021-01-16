<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $primaryKey = 'contact_id';

	protected $guarded = [
		'created',
	];

	protected function tenant() {
	    return $this->belongsTo(Tenant::class, 'tenant_contact_id','contact_id');
    }



}
