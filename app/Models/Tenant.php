<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
	use HasFactory;

	protected $primaryKey = 'tenant_id';

	protected $guarded = [
		'created',
	];
}
