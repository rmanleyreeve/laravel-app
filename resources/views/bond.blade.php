<x-master>
<section class="content-area">
	<div class="container content">
	
		<?php //pp($recordset); //exit; ?>
				
		<div class="page-body">
	
			<div class="card bg-light">
				<div class="card-header"><h3>Bond Statement</h3></div>
				<div class="card-body p-2 table-responsive">
				@if($recordset)
					<table class="table table-striped table-sm table-bordered">
						<thead class="thead-light">
							<tr>
								<th>Date</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
						@foreach($recordset as $record)
							<tr>
								<td><?php echo date('j M Y',strtotime($record['payment_date']));?></td>
								<td><?php echo _gbp($record['payment_amount']);?></td>
							</tr>
						    <?php $t += $record['payment_amount']; ?>
                            @endforeach
							<tr><th class="text-right">Total:</th><th>{{ $utils->_gbp($t) }}</th></tr>
						</tbody>
					</table>
					@else
						<h3 class="text-center text-danger py-5">No records currently in the system.</h3>
					@endif
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
</x-master>