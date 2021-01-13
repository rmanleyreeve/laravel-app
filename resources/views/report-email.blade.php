<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{{ $subject }}</title>
<style type="text/css">
html,body { margin:0; padding:0; }
html,body,table,tr,td,div,p { font-family: sans-serif; font-size: 14px; }
th { background-color:#EEEEEE; }
</style>
</head>
<body>
<div style="width:100%; background-color:#013856; padding:5px 20px"><img alt="PBNE logo" src="{{ Request::getSchemeAndHttpHost() }}/img/logo-small.png"></div>
<div style="padding:5px 20px">
<h2>{{ $subject }}</h2>
{{ $content }}
<hr><p>--- End of message. Please do not reply to this email. ---</p>
</div>
</body>
</html>
