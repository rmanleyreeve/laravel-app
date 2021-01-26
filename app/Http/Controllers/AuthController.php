<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Contact;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function default() {
        return redirect()->route('home');
    }

    public function login(){
        return view('master', [
            'content' => 'login',
        ]);
    }

    public function authenticate(Request $request) {
        $un = trim($request->username);
        $pw = trim($request->password);
        $model = Tenant::where('username','=',$un)->with('tenant_name')->first();
        if($model && password_verify($pw, $model->password)) {
            $model->timestamps = false;
            $model->update(['last_login' => date('Y-m-d H:i:s',time())]);
            $tenant = $model->toArray();
            //var_dump($tenant); exit();
            unset($tenant['password']);
            $expiry = time() + (86400 * env('COOKIE_EXPIRY'));
            Cookie::queue('tenant_id',$tenant['tenant_id'], $expiry);
            Cookie::queue('tenant_contact_fk',$tenant['contact_fk'], $expiry);
            Cookie::queue('tenant_username',$tenant['username'], $expiry);
            Cookie::queue('tenant_fullname',$tenant['tenant_name']['contact_name'], $expiry);
            $request->session()->flash('alert',array('type'=>'success','msg'=>'Welcome to your HomeZone App'));
            return redirect()->route('home');
        } else {
            Cookie::queue(Cookie::forget('tenant_id'));
            Cookie::queue(Cookie::forget('tenant_contact_fk'));
            Cookie::queue(Cookie::forget('tenant_username'));
            Cookie::queue(Cookie::forget('tenant_fullname'));
            $request->session()->flush();
            $request->session()->flash('alert',array('type'=>'error','msg'=>'Login Failed!'));
            return redirect()->route('login');
        }
    }

    public function logout(Request $request){
        Cookie::queue(Cookie::forget('tenant_id'));
        Cookie::queue(Cookie::forget('tenant_contact_fk'));
        Cookie::queue(Cookie::forget('tenant_username'));
        Cookie::queue(Cookie::forget('tenant_fullname'));
        $request->session()->flush();
        $request->session()->flash('alert', ['type'=>'info','msg'=>'You are now logged out of your HomeZone App']);
        return redirect()->route('login');
    }
}
