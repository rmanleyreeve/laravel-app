<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
	use HasFactory;

    public $timestamps = false;
	protected $primaryKey = 'tenant_id';

	protected $guarded = [
		'created',
	];

    public function contact() {
        return $this->belongsTo(Contact::class, 'contact_fk','contact_id');
    }

    public function tenant_name() {
        return $this->belongsTo(Contact::class, 'contact_fk','contact_id')
            ->select(['contact_id','contact_name']);
    }


}
