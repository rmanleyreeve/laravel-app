<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Contact;

// AUTHENTICATION #####################################################################################################
// default
Route::get('/', function () {
	//var_dump($_COOKIE); exit;
	if(isset($_COOKIE['tenant_id'])) {
		return redirect()->route('home');
	} else {
		return view('login');
	}
})->name('default');

// login
Route::post('/login', function (Request $request) {
	$un = trim($request->username);
	$pw = trim($request->password);
	$auth = Tenant::where([
		['username', '=', $un],
	])->first();
	if($auth && password_verify($pw, $auth->password)) {
		$auth->timestamps = false;
		$auth->update(['last_login' => date('Y-m-d H:i:s',time())]);
		$tenant = $auth->toArray();
		unset($tenant['password']);
		$contact = Contact::where([
			['contact_id','=',$auth->contact_fk]
		])->first();
		$tenant['fullname']  = $contact->contact_name;
		setcookie('tenant_id', $tenant['tenant_id'], time() + (86400 * env('COOKIE_EXPIRY')), '/');
		setcookie('tenant_contact_fk', $tenant['contact_fk'], time() + (86400 * env('COOKIE_EXPIRY')), '/');
		setcookie('tenant_username', $tenant['username'], time() + (86400 * env('COOKIE_EXPIRY')), '/');
		setcookie('tenant_fullname', $tenant['fullname'], time() + (86400 * env('COOKIE_EXPIRY')), '/');
		$request->session()->put('alert',array('type'=>'success','msg'=>'Welcome to your HomeZone App'));
		//var_dump($_COOKIE); exit;
	} else {
		setcookie('tenant_id','',-1,'/');
		setcookie('tenant_contact_fk','',-1,'/');
		setcookie('tenant_username','',-1,'/');
		setcookie('tenant_fullname','',-1,'/');
		$_COOKIE = array();
		$request->session()->flush();
		$request->session()->put('alert',array('type'=>'error','msg'=>'Login Failed!'));
	}
	return redirect()->route('home');
});

// logout
Route::get('/logout', function (Request $request) {
	setcookie('tenant_id','',-1,'/');
	setcookie('tenant_contact_fk','',-1,'/');
	setcookie('tenant_username','',-1,'/');
	setcookie('tenant_fullname','',-1,'/');
	$_COOKIE = array();
	$request->session()->flush();
	$request->session()->put('alert', ['type'=>'info','msg'=>'You are now logged out of your HomeZone App']);
	return redirect()->route('default');
});

