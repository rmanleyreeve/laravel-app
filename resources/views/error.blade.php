<!DOCTYPE html>
<html lang="en" class="no-js">
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

		<link rel="shortcut icon" href="/favicon.png" />
		<meta name="description" content="">
		<title>HOMEZONE Secure Tenancy App by PBNE</title>
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/app.css?t={{ time() }}">
		<!-- Core JavaScript -->
		<script src="/js/vendor/jquery-3.4.1.min.js"></script>
		<script src="/js/vendor/bootstrap.bundle.min.js"></script>
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
					</div>
				</nav>
			</header>
			<!-- End Header Area -->

			<!-- Start Page Content -->
			<h3 class="text-center text-danger py-5">No active tenancy recorded in the system.</h3>
			<a class="btn btn-danger mx-5" href="/logout">Log Out</a>
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

		<!-- Custom Js -->
		<script src="/js/app.js"></script>
	</body>
</html>
