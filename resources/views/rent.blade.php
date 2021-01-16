<section class="content-area">
	<div class="container content">

		<div class="page-body">

			<div class="card bg-light">
				<div class="card-header"><h3>Rent Statement</h3></div>
				<div class="card-body p-2 table-responsive">
				@isset($periods)
					<table class="table table-striped table-sm table-bordered">
						<thead class="thead-light">
							<tr>
								<th>&nbsp;</th>
								<th>Expected</th>
								<th>Paid</th>
								<th>Date</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
							<?php
                            $bal = 0;
							$t_expected = 0;
							$t_paid = 0;
							foreach ($periods as $period) {
								$bal -= $tenancy->payment_amount;
								if($period['payments']) { // 1 or more payments this month
									$c1 = date('j/n/y',strtotime($period['start'])) .' - '. date('j/n/y',strtotime($period['end']));
									$c2 = $tenancy->payment_amount;
									foreach($period['payments'] as $p) {
										$bal += $p['entry_amount'] + $p['entry_discount'];
										$t_expected += ($c2 ?: 0);
										$t_paid += ($p['entry_amount'] + $p['entry_discount']);
										?>
										<tr>
											<td>{{  $c1 }}</td>
											<td> {{ $utils->_gbp($c2) }}</td>
											<td>{{ $utils->_gbp($p['entry_amount'])}}
												@if($p['entry_discount'] > 0)
													<div class="text-success"><small>+ discount: {{ $utils->_gbp($p['entry_discount']) }}'</small></div>
												@endif
												</td>
											<td>{{ date('j M Y',strtotime($p['payment_date'])) }}</td>
											<td>{{ $utils->_gbp($bal) }}</td>
										</tr>
										<?php
										$c1 = NULL; $c2 = NULL;
									}
								} else { // no payment this month
									$t_expected += $tenancy->payment_amount;
								?>
								<tr>
									<td>{{ date('j/n/y',strtotime($period['start']))}} - {{ date('j/n/y',strtotime($period['end'])) }}</td>
									<td>{{ $utils->_gbp($tenancy->payment_amount) }}</td>
									<td>£0.00</td>
									<td>&nbsp;</td>
									<td>{{ $utils->_gbp($bal) }}</td>
								</tr>
								<?php
								}
							}
							?>
							<tr>
								<th>TOTALS:</th>
								<th>{{ $utils->_gbp($t_expected) }}</th>
								<th>{{ $utils->_gbp($t_paid) }}</th>
								<th></th>
								<th></th>
							</tr>
						</tbody>
					</table>
				@else
					<h3 class="text-center text-danger py-5">No records currently in the system.</h3>
				@endisset
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

$(function(){

});

</script>
