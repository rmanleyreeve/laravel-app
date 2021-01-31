<section class="content-area">
	<div class="container content">

		<div class="page-body">

			<div class="card bg-light mt-4 mb-4" id="vue-element">
				<div class="card-header"><h3>Property Details</h3></div>
				<div class="card-body p-2">
					<table class="table table-striped table-sm table-bordered mb-1">
						<tr>
							<th>Address:</th><td><a v-on:click.stop="getAddressLink" href="#" target="_blank" data-lat="{{ $selected->latitude }}" data-lng="{{ $selected->longitude }}">{{  $selected->property_address }}, {{ $selected->county_name }} {{ $selected->property_postcode }}</a></td>
						</tr>
						<tr>
							<th>Type:</th><td>{{ $selected->property_type_name }}, {{ $selected->furnishing_type_name }}, {{ $selected->number_bedrooms }} bedrooms</td>
						</tr>
						<tr>
							<th>Tenant since:</th><td>{{ date ('j M Y',strtotime($selected->tenancy_start_date)) }}</td>
						</tr>
					</table>
				</div><!-- //card-body -->
			</div><!-- //card -->

			<div class="card bg-light mt-4 mb-4">
				<div class="card-header"><h3>Utility Details</h3></div>
				<div class="card-body p-2">
					<table class="table table-striped table-sm table-bordered mb-1">
						<tr><th>Gas meter:</th><td>{{ $selected->gas_meter_location }}</td></tr>
						<tr><th>Boiler:</th><td>{{ $utils->_cm($selected->boiler_info) }} located in {{ $selected->boiler_location }}</td></tr>
						<tr><th>Electric meter:</th><td>{{ $selected->electric_meter_location }}</td></tr>
						<tr><th>Fusebox:</th><td>{{ $selected->fusebox_location }}</td></tr>
						<tr><th>Water meter:</th><td>{{ $selected->water_meter_location }}</td></tr>
						<tr><th>Stopcock:</th><td>{{ $selected->stopcock_location }}</td></tr>
						<tr><th>Bins collected:</th><td>{{ $selected->refuse_collection }}</td></tr>
					</table>
				</div><!-- //card-body -->
			</div><!-- //card -->

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
                    window.open("https://" + url); // else use Google
                }
            }
        }
    });
</script>
