<section class="content-area">
	<div class="container content">

		<div class="page-body">

			<div class="card bg-light" id="vue-element">
				<div class="card-header"><h3>Bond Statement</h3></div>
				<div class="card-body p-2 table-responsive">
                    <h3 v-cloak v-if="loaded && vBondData.length == 0" class="text-center text-danger py-5">No records currently in the system.</h3>
					<table v-show="vBondData.length > 0" class="table table-striped table-sm table-bordered">
						<thead class="thead-light">
							<tr>
								<th>Date</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
                            <tr v-for="record in vBondData">
                                <td>@{{ record.payment_date }}</td>
                                <td>&pound;@{{ record.payment_amount }}</td>
                            </tr>
							<tr><th class="text-right">Total:</th><th>&pound;@{{ totalAmount }}</th></tr>
						</tbody>
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
            loaded: false,
            vBondData: []
        },
        created: function () {
            this.getData();
        },
        methods: {
            getData: function () {
                let self = this;
                fetch('/bond-data')
                    .then(function (response) {
                        return response.json()
                    }).then(function (responseJson) {
                    console.log(responseJson);
                    self.vBondData = responseJson;
                    self.loaded = true;
                }).catch(function (ex) {
                    console.log('vue getData failed', ex);
                });
            }
        },
        computed: {
            totalAmount: function () {
                let sum = 0;
                this.vBondData.forEach(e => {
                    sum += parseFloat(e.payment_amount);
                });
                return sum.toFixed(2);
            }
        }
    });
</script>
