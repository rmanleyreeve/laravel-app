<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;

class Tenant extends Model
{
	use HasFactory;

    public $timestamps = false;
	protected $primaryKey = 'tenant_id';

	protected $guarded = [
		'created',
	];

    protected function contact() {
        return $this->belongsTo(Contact::class, 'contact_id','tenant_contact_id');
    }


}
