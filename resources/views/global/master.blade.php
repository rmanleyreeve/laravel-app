@if(!@$alert) @php $alert = Session::get('alert'); @endphp @endif
<!DOCTYPE html>
<html lang="en" class="no-js">
	<!--
	PBNE HomeZone App
	(c)2018-{{ date('Y') }} by RE MEDIA  www.re-media.biz
	Powered by: PHP {{ phpversion() }}, Laravel {{ app()::VERSION }}
	UI: jQuery 3, Bootstrap 4
	-->
	<head>
		<meta charset="UTF-8">
		<!-- PWA behaviour -->
		<link rel="manifest" href="/manifest.json">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="application-name" content="PBNE HomeZone">
		<meta name="apple-mobile-web-app-title" content="PBNE HomeZone">
		<meta name="msapplication-starturl" content="/">
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">

		<!-- iPhone XR (828px x 1792px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="/img/apple-launch-828x1792.png" />
		<!-- iPhone X (1125px x 2436px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="/img/apple-launch-1125x2436.png">
		<!-- iPhone 8 Plus, 7 Plus, 6s Plus, 6 Plus (1242px x 2208px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" href="/img/apple-launch-1242x2208.png">
		<!-- iPhone 8, 7, 6s, 6 (750px x 1334px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="/img/apple-launch-750x1334.png">
		<!-- iPhone 5 (640px x 1136px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="/img/apple-launch-640x1136.png">

		<link rel="apple-touch-icon" href="/img/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/img/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/img/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/img/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/img/apple-touch-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon-180x180.png" />

		<link rel="shortcut icon" href="/favicon.png" />
		<meta name="description" content="">
		<title>HOMEZONE Secure Tenancy App by PBNE</title>
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Custom styles for this template -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link href="/css/switchery.css" rel="stylesheet" />
		<link href="/css/toastr.css" rel="stylesheet" />
		<link rel="stylesheet" href="/css/app.css?t={{ time() }}">
		<!-- Core JavaScript -->
		<script src="/js/vendor/jquery-3.4.1.min.js"></script>
		<script src="/js/vendor/bootstrap.bundle.min.js"></script>
		<!-- Toastr Js -->
		<script src="/js/vendor/toastr.min.js"></script>
		<script>
		$(function(){
			@if(@$alert)
			// notifications
			$('#toast-container').remove();
			toastr.options = {  {!! \App\Providers\UtilsProvider::toastr_options() !!} };
			toastr['{{ $alert['type'] }}']("{{ $alert['msg'] }}", "{{ strtoupper($alert['type']) }}");
            {{ $alert = NULL }} {{ Session::forget('alert') }} @endif

			//set active menu from URL
			var uri = '/{{ Request::path() }}';
			$('#main-menu li a').each(function(i, e) {
				//console.log(uri, $(this).attr('href'));
				if(uri == $(this).attr('href')) {
					$(this).addClass('active');
					return false;
				}
			});
		});
		</script>
	</head>
	<body>

		<div id="page-container" class="container-fluid pr-0 pl-0 d-flex flex-column">

			<!-- Start Header Area -->
			<header>
				<nav class="navbar navbar-expand-lg navbar-dark">
					<div class="container">
							<a class="navbar-brand" href="/" id="home-link">
								<img src="/img/logo-small.png" alt="">
							</a>
							@isset($_COOKIE['tenant_id'])
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarSupportedContent">
								@include('global.menu')
							</div>
							@endisset
					</div>
				</nav>
			</header>
			<!-- End Header Area -->

            @isset($_COOKIE['tenant_id'])
			<div class="alert alert-dark mb-1" role="alert">
			<h5>Welcome, {{ $_COOKIE['tenant_fullname']}}</h5>
			</div>
			@endisset

		<!-- Start Page Content -->
        @include($content)
		<!-- End Page Content -->

			<!-- Start footer area -->
			<footer class="footer">
				<div class="container">
					<div class="footer-content">
						<p class="text-center">HOMEZONE Secure Tenancy App  &copy;PBNE {{ date('Y') }}</p>
					</div>
				</div>
			</footer>
			<!-- End footer area -->

		</div><!-- End page container -->

		<!-- Generic Modal -->
		<div class="modal fade" id="bsModal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content"></div>
			</div>
		</div>

		<!-- Switchery Js -->
		<script src="/js/vendor/switchery.js"></script>
		<!-- Custom Js -->
		<script src="/js/app.js"></script>
	</body>
</html>
