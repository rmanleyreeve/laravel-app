<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Tenant Details Updated</title>
    <style type="text/css">
        html,body { margin:0; padding:0; }
        html,body,table,tr,td,div,p { font-family: sans-serif; font-size: 14px; }
        th { background-color:#EEEEEE; }
    </style>
</head>
<body>
<div style="width:100%; background-color:#013856; padding:5px 20px"><img src="https://tenant.pbne.co.uk/img/logo-small.png"></div>
<div style="padding:5px 20px">
    <h2>HomeZone App : Tenant Details Updated</h2>
    <p>Dear property owner, the following tenant details have been updated via the HomeZone App:</p>
    <ul>
        <li>Tenant Name: {{ $contact_name }}</li>
        <li>Contact Telephone: {{ $contact_email }}</li>
        <li>Contact Mobile: {{ $contact_mobile }}</li>
        <li>Contact Email: {{ $contact_email }}</li>
    </ul>
    <hr><p>--- End of message. Please do not reply to this email. ---</p>
</div>
</body>
</html>
