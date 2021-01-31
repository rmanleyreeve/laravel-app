<section class="content-area">
	<div class="container content">

		<div class="page-body" id="vue-element">

			@if($selected->managed_tenancy)

				<div class="card bg-light mt-4 mb-4">
					<div class="card-header"><h3>Letting Agent Details</h3></div>
					<div class="card-body p-2">
						<table class="table table-striped table-sm table-bordered mb-1">

							<tr><th>Letting Agency:</th><td>{{ $selected->agent_company }}</td></tr>
							<tr><th>Contact Name:</th><td>{{ $selected->agent_name }}</td></tr>
							<tr>
								<th>Address:</th><td><a href="#" target="_blank" v-on:click.stop="getAddressLink">{{ $selected->agent_address }} {{ $selected->agent_postcode }}</a></td>
							</tr>
							<tr><th>Tel:</th><td><a href="{{ $utils->telLink($selected->agent_tel) }}">{{ $selected->agent_tel }}</a></td></tr>
							<tr><th>Mobile:</th><td><a href="{{ $utils->telLink($selected['agent_mobile']) }}">{{ $selected->agent_mobile }}</a></td></tr>
							<tr><th>Email:</th><td><a href="mailto:{{ $selected->agent_email }}">{{ $selected->agent_email }}</a></td></tr>
							<tr><th>Website:</th><td><a href="{{ $selected->agent_url }}">{{ $selected->agent_url }}</a></td></tr>
						</table>
						<h4 class="m-3">To report an issue with the property, please use the <a href="/report">Report An Issue</a> link.</h4>
					</div><!-- //card-body -->
				</div><!-- //card -->

			@else

				<div class="card bg-light mt-4 mb-4">
					<div class="card-header"><h3>Landlord Details</h3></div>
					<div class="card-body p-2">
						<p>Your Landlord is: <strong>{{ $selected->property_owner_names }}</strong></p>
						<p>To contact your landlord or report an issue with the property, please use the <a href="/report">Report An Issue</a> link.</p>

					</div><!-- //card-body -->
				</div><!-- //card -->

			@endif

			<div class="row">
				<div class="col col-xs-12">
					<a href="/dashboard" class="mt-2 mb-3 btn btn-block btn-sm btn-secondary">Back</a>
				</div>
			</div>

		</div><!-- //page-body -->

	</div>
</section>

<script>
    new Vue({
        el: '#vue-element',
        data: {
            addr: '{!! urlencode("{$selected->property_address} {$selected->property_postcode}") !!}'
        },
        methods: {
            getAddressLink: function (ev){
                let url = 'maps.google.com/maps?q=' + this.addr;
                let lat = ev.target.dataset.lat;
                let lng = ev.target.dataset.lng;
                if(lat && lng) {
                    url += '&center='+lat+','+lng+'&amp;ll=';
                }
                if ((navigator.platform.indexOf("iP") != -1)){
                    if(confirm('Close this app and open in Maps?')) {
                        window.open("maps://" + url); // we're on iOS, open in Apple Maps
                    }
                } else {
                    alert(url);
                    window.open("https://" + url); // else use Google
                }
            }
        }
    });
</script>
