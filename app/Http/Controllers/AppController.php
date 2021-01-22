<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Arr;
use \App\Domain\AppUtils as Utils;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Mail;

class AppController extends Controller
{

    // main app dashboard
    public function showDashboard(Request $request){
        $res = DB::table('tenancies AS t')
            ->join('contacts AS tc','tc.contact_id','=','t.tenant_contact_fk')
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->where('t.deleted','=','FALSE')
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk'] ?? 0))
            ->where(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->orWhere('t.tenancy_end_date','=','')
                    ->orWhere('t.tenancy_end_date','<','1')
                    ->orWhere('t.tenancy_end_date','>','DATE(NOW())');
            })
            ->select('t.*','tc.contact_name AS tenant_name','p.property_name','p.property_address','p.property_postcode')
            ->get()
           ->toArray();
        //print_r($res); exit;
        if(!$res) {
            setcookie('tenant_id', '', -1, '/');
            setcookie('tenant_contact_fk', '', -1, '/');
            setcookie('tenant_username', '', -1, '/');
            setcookie('tenant_fullname', '', -1, '/');
            $_COOKIE = [];
            $request->session()->flush();
            return view('error');
        } else {
            return view('global.master', [
                'content' => 'dashboard',
                'selected' => reset($res)
            ]);
        }
    }

    // property & utilities info
    public function showPropertyInfo(Request $request) {
        $res = DB::table('tenancies AS t')
            ->join('contacts AS tc','tc.contact_id','=','t.tenant_contact_fk')
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->join('property_types AS pt','pt.property_type_id','=','p.property_type_fk')
            ->join('furnishing_types AS ft','ft.furnishing_type_id','=','p.furnishing_type_fk')
            ->join('postal_towns AS pto','pto.town_id','=','p.postal_town_fk')
            ->join('postal_counties AS co','co.county_id','=','pto.county_fk')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select(
                't.*',
                'tc.contact_name AS tenant_name','tc.contact_address AS tenant_address','tc.contact_postcode AS tenant_postcode','tc.contact_tel AS tenant_tel','tc.contact_mobile AS tenant_mobile','tc.contact_email AS tenant_email',
                'p.*','pt.property_type_name','ft.furnishing_type_name','co.county_name'
            )
            ->selectRaw('UPPER(pto.town_name) AS town_name')
            ->get()
            ->toArray();
        //print_r($res); exit;
        return view('global.master', [
            'content' => 'property-info',
            'selected' => reset($res),
            'utils' => new Utils()
        ]);
    }

    // list current certificate
    public function showCP12(Request $request){
        $res = DB::table('tenancies AS t')
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->leftJoin('documents AS d','d.property_fk','=','t.property_fk')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->where(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->orWhere('t.tenancy_end_date','=','')
                    ->orWhere('t.tenancy_end_date','<','1')
                    ->orWhere('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->where(function($query){
                $query->where('d.archived','=',false)
                    ->whereIn('d.document_type_fk',function($query){
                        $query->select('document_type_id')->from('document_types')->where('document_type_name','=','CP12 Certificate');
                    });
            })
            ->select('t.tenancy_name','t.property_fk','p.property_name','p.property_address','p.property_postcode','d.document_id','d.document_name','d.document_expiry')
            ->get()
            ->toArray();
        //print_r($res); exit;
        return view('global.master', [
            'content' => 'cp12',
            'selected' => reset($res),
            'utils' => new Utils()
        ]);
    }

    // letting agent / landlord details
    public function showAgentDetails(Request $request){
        $res = DB::table('tenancies AS t')
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->leftJoin('contacts AS mc','mc.contact_id','=','t.management_contact_fk')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select(
                't.tenancy_name','t.managed_tenancy','p.property_name','p.property_address','p.property_postcode',
                'mc.contact_name AS agent_name','mc.contact_company AS agent_company','mc.contact_address AS agent_address','mc.contact_postcode AS agent_postcode','mc.contact_tel AS agent_tel','mc.contact_mobile AS agent_mobile','mc.contact_email AS agent_email','mc.contact_url AS agent_url'
            )
            ->selectRaw("(SELECT GROUP_CONCAT(owner_name ORDER BY owner_name SEPARATOR ' & ') FROM property_owners WHERE owner_id IN (SELECT owner_fk FROM link_property_owner WHERE property_fk=p.property_id)) AS property_owner_names")
            ->get()
            ->toArray();
        //print_r($res); exit;
        return view('global.master', [
            'content' => 'agent',
            'selected' => reset($res),
            'utils' => new Utils()
        ]);
    }

    // report an issue
    public function showReportForm(Request $request) {
        return view('global.master', [
                'content' => 'report-issue',
                'reported' => false,
                'issue_areas' => config('app.issue_areas'),
            ]
        );
    }
    public function submitReport(Request $request) {
        $res = DB::table('tenancies AS t')
            ->join('contacts AS tc','tc.contact_id','=','t.tenant_contact_fk')
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->leftJoin('contacts AS mc','mc.contact_id','=','t.management_contact_fk')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select(
                't.tenancy_id','t.tenancy_name',
                'tc.contact_name AS tenant_name','tc.contact_address AS tenant_address','tc.contact_postcode AS tenant_postcode','tc.contact_tel AS tenant_tel','tc.contact_mobile AS tenant_mobile','tc.contact_email AS tenant_email',
                'p.property_name','p.property_address','p.property_postcode'
            )
            ->selectRaw("(SELECT GROUP_CONCAT(owner_name,',',LOWER(owner_email) ORDER BY owner_id SEPARATOR '|') FROM property_owners WHERE owner_id IN (SELECT owner_fk FROM link_property_owner WHERE property_fk=p.property_id)) AS property_owners")
            ->selectRaw("(SELECT CONCAT(mc.contact_name,',',LOWER(mc.contact_email))) AS agent")
            ->get()
            ->toArray();
        //print_r($res); exit;
        if($res) {
            $selected = reset($res);
            // step 1 - add record & files
            $dataObj = new \App\Models\ReportedIssue();
            $dataObj->timestamps = false;
            $dataObj->tenancy_fk = intval($selected->tenancy_id);
            $dataObj->issue_area = $request->get('issue_area');
            $dataObj->issue_description = $request->get('issue_description');
            $dataObj->save();
            $issue_id = $dataObj->issue_id;
            $issue = $dataObj->toArray();
            $dir = env('ADMIN_SERVER'). "media/issues/{$issue_id}";
            if($request->hasfile('issue_image')) {
                $request->validate([
                    'issue_image.*' => 'mimes:png,gif,jpg,jpeg'
                ]);
                foreach($request->file('issue_image') as $file) {
                    //var_dump($file); exit;
                    if(!file_exists($dir)){ mkdir($dir,0777,true); }
                    $pi = pathinfo($file->getClientOriginalName());
                    $img = Image::make($file->getRealPath());
                    $img->resize(1200, 1200, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save("{$dir}/{$pi['filename']}.jpg", 90,'jpeg');
                }
            }
            // step 2 - send SMS
            $params = array(
                'method' => 'sendsms',
                'clientbillingreference' => "IssueId_{$issue_id}",
                'clientmessagereference' => "IssueId_{$issue_id}",
                'returncsvstring' => 'false',
                'externallogin' => env('SMS_ID'),
                'password' => env('SMS_KEY'),
                'originator' => 'PBNE Alerts',
                'destinations' => env('SMS_DEST'),
                'body' => "Issue with {$issue['issue_area']} reported at {$selected->property_name} by {$selected->tenant_name}. Open the PBNE app to see full details.",
                'validity' => '24',
                'charactersetid' => '2',
                'replymethodid' => '1',
                'replydata' => '',
                'statusnotificationurl' => '',
            );
            $url = env('SMS_API'). '?'. http_build_query($params);
            $client = new GuzzleHttp\Client(
                ['http_errors' => false]
            );
            //$result = $client->get($url);
            //pp($result); exit;
            // step 3 - send email to owner/agent
            $subject = "{$selected->property_name} Property Issue - Tenant Report";
            $emails = [];
            $pos = explode('|',$selected->property_owners);
            foreach($pos as $po) {
                $emails[] = array_combine(['name','email'], explode(',',$po));
            }
            if($selected->agent) {
                $emails[] = (array) array_combine(['name','email'], explode(',',$selected->agent));
            }
            $mailer = new \App\Mail\ReportIssue(NULL);
            $mailer->from = env('PBNE EMAIL');
            $mailer->subject = $subject;
            // attach files
            $nf = 0;
            if(file_exists($dir) && $files = glob("{$dir}/*") ) {
                foreach((array)$files as $f) {
                    if(is_file($f)){
                        $mailer->attach($f,['as'=>basename($f)]);
                        $nf ++;
                    }
                }
            }
            $content = "
            <h4>An issue has been reported by the tenant at {$selected->property_name}</h4>
            <p><strong>Address:</strong> {$selected->property_address} {$selected->property_postcode}</p>
            <p><strong>Tenant:</strong> {$selected->tenant_name} {$selected->tenant_tel} {$selected->tenant_mobile} {$selected->tenant_email}</p>
            <p><strong>Issue relates to:</strong> {$issue['issue_area']}</p>
            <p><strong>Issue Description:</strong> ". nl2br($issue['issue_description']) . "</p>
            ";
            if($nf) { $content .= "<p><strong>{$nf} Photos Attached</strong></p>"; }
            $mailer->content = $content;
            Mail::to($emails)->send($mailer);
            if (Mail::failures()) {
                // show errors
                var_dump(Mail::failures()); exit;
            }
            return view('global.master', [
                'content' => 'report-issue',
                'reported' => true
            ]);
        }
    }

    // rent statement
    public function showRentStatement(Request $request){
        $tenancy = DB::table('tenancies AS t')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select('t.tenancy_id','t.tenancy_name','t.payment_amount','t.tenancy_start_date','t.tenancy_end_date')
            ->first();
        $id = $tenancy->tenancy_id;
        $paid = DB::table('income AS i')
            ->join('financial_types AS ft','ft.financial_type_id','=','i.financial_type_fk')
            ->where('i.tenancy_fk','=', $id)
            ->where('i.deleted','=',false)
            ->whereIn('i.financial_type_fk',function($query){
                $query->select('financial_type_id')->from('financial_types')->where('account_type','=','INCOME');
            })
            ->orderBy('i.payment_date','ASC')
            ->select('i.*','ft.financial_type_name')
            ->get()
            ->toArray();
        // calculate rental periods starting from tenancy start date
        $intervalM = new DateInterval('P1M');
        $intervalD = new DateInterval('P1D');
        $start = new DateTime($tenancy->tenancy_start_date);
        $setlast = ($start->format("d") > 28);
        $now = date('Ymd');
        $periods = array();
        while ($start->format('Ymd') < $now) {
            $diff = 0;
            $next = (clone $start)->add($intervalM);
            // check for month skips
            $oldDay = $start->format("d");
            $newDay = $next->format("d");
            if($oldDay != $newDay) {
                $diff = $newDay;
                $next->sub(new DateInterval("P" . $newDay . "D"));
            }
            if($setlast) { $next->modify('last day of this month'); }
            $end = (clone $next)->sub($intervalD);
            $tmp = array();
            // check for payments between thse dates
            foreach((array)$paid as $idx=>$p) {
                $pd = strtotime($p['payment_date']);
                if($pd >= $start->getTimestamp() && $pd <= $end->getTimestamp()) {
                    //payment date is within this period
                    $tmp[] = $p;
                    unset($paid[$idx]);
                }
            }
            $periods[] = array(
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'payments' => $tmp
            );
            $start = $next;
        }
        return view('global.master', [
            'content' => 'rent',
            'selected' => $tenancy,
            'tenancy' => $tenancy,
            'periods' => $periods,
            'utils' => new Utils()
        ]);
    }

    // bond statement
    public function showBondStatement(Request $request){
        $res = DB::table('tenancies AS t')
            ->join('bond_payments AS bp','bp.tenancy_fk','=','t.tenancy_id')
            ->where('bp.deleted','=',false)
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select('bp.*')
            ->get()
            ->toArray();
        //print_r($res); exit;
        return view('global.master', [
            'content' => 'bond',
            'recordset' => $res,
            'utils' => new Utils(),
            't' => 0
        ]);
    }

    // property inspections
    public function showInspections(Request $request){
        $tenancy = DB::table('tenancies AS t')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select('t.tenancy_id','t.tenancy_name','t.payment_amount','t.tenancy_start_date','t.tenancy_end_date')
            ->first();
        $id = $tenancy->tenancy_id;
        $current = DB::table('inspections AS i')
            ->join('tenancies AS t','t.tenancy_id','=','i.tenancy_fk')
            ->where('i.tenancy_fk','=',$id)
            ->where('i.archived','=',false)
            ->orderBy('i.inspection_id','DESC')
            ->select('i.*', 't.tenancy_name')
            ->get()
            ->toArray();
        $archived = DB::table('inspections AS i')
            ->join('tenancies AS t','t.tenancy_id','=','i.tenancy_fk')
            ->where('i.tenancy_fk','=',$id)
            ->where('i.archived','=',true)
            ->orderBy('i.inspection_id','DESC')
            ->select('i.*', 't.tenancy_name')
            ->get()
            ->toArray();
        return view('global.master', [
            'content' => 'inspections',
            'current' => $current,
            'archived' => $archived,
            'utils' => new Utils(),
        ]);
    }

    //  inspection signature image
    public function getSignatureImage($id){
        $res = DB::select(
            "SELECT signature_image FROM inspections WHERE inspection_id=:id;",
            [':id'=>intval($id)]
        )[0];
        $data = $res->signature_image;
        $enc = str_replace("data:image/png;base64,", "", $data);
        return response(base64_decode($enc))->header('Content-Type', 'image/png');
    }

    // update user details
    public function showUpdateDetailsForm(Request $request) {
        $res = DB::table('tenancies AS t')
            ->join('contacts AS tc','tc.contact_id','=','t.tenant_contact_fk')
            ->where('t.deleted','=',false)
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->orWhere(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->where('t.tenancy_end_date','=','')
                    ->where('t.tenancy_end_date','<','1')
                    ->where('t.tenancy_end_date','>','DATE(NOW)');
            })
            ->select('tc.contact_id','tc.contact_name','tc.contact_tel','tc.contact_mobile','tc.contact_email')
            ->get()
            ->toArray();
        //print_r($res); exit;
        return view('global.master', [
            'content' => 'update-details',
            'selected' => reset($res),
            'utils' => new Utils(),
            'updated' => false
        ]);
    }
    public function submitUpdateDetails(Request $request){
        $dataObj = Contact::find($request->contact_id);
        if(!$dataObj) {
            $request->session()->put('alert',array('type'=>'error','msg'=>'No matching contact record!'));
            return redirect()->route('home');
        }
        $dataObj->timestamps = false;
        $vars = Arr::except($request->all(), ['_token']);
        $dataObj->update($vars);
        $dataObj->save();
        // send email
        $po = DB::table('tenancies AS t')
            ->selectRaw("(SELECT GROUP_CONCAT(owner_email SEPARATOR ',') FROM property_owners WHERE owner_id IN (SELECT owner_fk FROM link_property_owner WHERE property_fk=p.property_id)) AS property_owners")
            ->join('properties AS p','p.property_id','=','t.property_fk')
            ->where('t.tenant_contact_fk','=', intval($_COOKIE['tenant_contact_fk']))
            ->where('t.deleted','=',false)
            ->where(function($query){
                $query->whereNull('t.tenancy_end_date')
                    ->orWhere('t.tenancy_end_date','=','')
                    ->orWhere('t.tenancy_end_date','<','1')
                    ->orWhere('t.tenancy_end_date','>','DATE(NOW())');
            })
            ->first();
        if($po) {
            $emails = explode(',',$po->property_owners);
            $vars = $dataObj->toArray();
            Mail::send(
                'update-email',
                $vars,
                function($message) use ($vars,$emails) {
                    $message
                        ->to($emails)
                        ->from('test@localhost.com', 'RMR')
                        ->subject('HomeZone App : Tenant Details Updated');
                }
            );
            if (Mail::failures()) {
                // show errors
                var_dump(Mail::failures()); exit;
            }
        }
        return view('global.master', [
            'content' => 'update-details',
            'updated' => true
        ]);
    }

}
