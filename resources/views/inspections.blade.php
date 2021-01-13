<x-master>
<section class="content-area">
	<div class="container content">
	
		<div class="page-body">
	
			<div class="card bg-light">
				<div class="card-header"><h3>Inspection Record</h3></div>
				<div class="card-body p-2 table-responsive">
				@if($current)
					<div class="alert alert-danger" role="alert">
						<h6>Your next inspection is due on {{ date('D j M Y',strtotime($current->next_inspection_due)) }}</h6>
					</div>
					<h4 class="mb-1">Current Inspection</h4>
					<table class="table table-striped table-sm table-bordered">
						<thead class="thead-light">
							<tr>
								<th>Date</th>
								<th>Comments</th>
								<th>Signature</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>{{ date('D j M Y H:i',strtotime($current->created)) }}</td>
								<td>{{ nl2br($current->condition_notes) }}</td>
								<td>
									<a href="/inspections/{{ $current->inspection_id }}/signature" target="_blank"><img class="signature-image" src="{{ $current->signature_image }}"></a>
								</td>
							</tr>
						</tbody>
					</table>
					@else
						<h3 class="text-center text-danger py-3">No current inspection recorded in the system.</h3>
					@endif
					
					<h4 class="mb-1">Previous Inspections</h4>
					<table class="table table-striped table-sm table-bordered">
						<thead class="thead-light">
							<tr>
								<th>Date</th>
								<th>Comments</th>
								<th>Signature</th>
							</tr>
						</thead>
						<tbody>
						@foreach($archived as $record)
							<tr>
								<td>{{ date('D j M Y H:i',strtotime($record->created)) }}</td>
								<td>{{ nl2br($record->condition_notes) }}</td>
								<td>
									<a href="/inspections/{{ $record->inspection_id }}/signature" target="_blank"><img class="signature-image" src="{{ $record->signature_image }}"></a>
								</td>
							</tr>
							@endforeach
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

$(function(){
	
});

</script>
</x-master>