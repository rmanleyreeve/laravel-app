<x-master>
<section class="content-area">
	<div class="container content">

		<div class="row vcenter">
			<div class="col"></div><div class="col-6"><img class="img-fluid" src="/img/login.png"></div><div class="col"></div>

			<div class="text-center">
				<h2>Secure Tenancy Management App</h2>
				<div class="mb-2">Please enter your login details.</div>
			</div>

			<div class="col"></div>
			<div class="col-8">
				<form name="login-form" id="login-form" method="POST" action="login">
                    @csrf
					<div class="input-group mb-4">
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="fa fa-user"></span></div>
						</div>
						<input type="text" class="form-control" placeholder="Username" name="username" id="username" required/>
					</div>

					<div class="input-group mb-4">
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="fa fa-lock"></span></div>
						</div>
						<input type="password" class="form-control" placeholder="Password" name="password" id="password" required/>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-lg btn-primary">Log In</button>
					</div>
				</form>
			</div><!-- //col -->
			<div class="col"></div>
		</div><!-- //row -->

	</div>
</section>

<!-- Install Propmp Modal -->
<div class="modal fade" id="installModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalTitle">Install App?</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p><strong>To install this App on your iOS Device:</strong></p>
				<p>Tap <img src="/img/share.svg" style="height: 22px; vertical-align: text-bottom"> then select <i class="mx-1 fa-lg far fa-plus-square"></i> 'Add to Home Screen'</li>
			</div>
		</div>
	</div>
</div>

<script>
// Detects if device is on iOS
const isIos = () => {
  const userAgent = window.navigator.userAgent.toLowerCase();
  return /iphone|ipad|ipod/.test( userAgent );
}
// Detects if device is in standalone mode
const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);
// Checks if should display install popup notification:
if (isIos() && !isInStandaloneMode()) {
  showInstallMessage();
}
function showInstallMessage(){
	$('#installModal').modal('show');
}
</script>
</x-master>
