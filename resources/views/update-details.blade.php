<x-master>
<section class="content-area">
	<div class="container content">
	
		<div class="page-body">
	
			<div class="form-row clearfix">
				<div class="col-xs-12 col-sm-12">
							
				@if($updated)
							
					<div class="card bg-light mt-4 mb-4">
						<div class="card-header"><h3>Update My Details</h3></div>
						<div class="card-body">
							<h4>Thank you for updating your contact details.</h4>
							<p>Your updated details will be forwarded to your letting agent or landlord.</p>
						</div><!-- //card-body -->
					</div><!-- //card -->
							
					<div class="row">
						<div class="col col-xs-12">
							<a href="/dashboard" class="mt-2 mb-3 btn btn-block btn-sm btn-secondary">Back</a>				
						</div>
					</div>

					@else
			
						<div class="card bg-light mt-4 mb-4">
							<div class="card-header"><h3>Update My Details</h3></div>
							<div class="card-body">

								<form id="update-form" method="post">
                                    @csrf
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Name:</strong></label>
										<div class="col-sm-12">
											<input class="form-control input-sm" name="contact_name" id="contact_name" value="{{ $selected->contact_name }}" required />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Telephone:</strong></label>
										<div class="col-sm-12">
											<input type="tel" class="form-control input-sm" name="contact_tel" id="contact_tel" value="{{ $selected->contact_tel }}" />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Mobile:</strong></label>
										<div class="col-sm-12">
											<input type="tel" class="form-control input-sm" name="contact_mobile" id="contact_mobile" value="{{ $selected->contact_mobile }}" />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Email:</strong></label>
										<div class="col-sm-12">
											<input type="email" class="form-control input-sm" name="contact_email" id="contact_email" value="{{ $selected->contact_email }}" />
										</div>
									</div>
									<div class="form-group row mt-5">
										<div class="col-sm-12">
											<button type="button" id="submit-btn" class="m-w-100 btn btn-sm btn-primary">Update</button>
											<a href="/dashboard" class="ml-2 btn btn-sm btn-outline-secondary">Cancel</a>
											<span id="loader" class="ml-3" style="vertical-align:-webkit-baseline-middle;color:#f8f9fa;"><i class="fas fa-2x fa-sync fa-spin"></i></span>
										</div>
									</div>
									<input type="hidden" name="contact_id" value="{{ $selected->contact_id }}">
								</form>

							</div><!-- //col -->
						</div><!-- //row -->
							
					@endif
					
				</div><!-- //col -->
			</div><!-- //row -->
						
		</div><!-- //page-body -->
	
	</div>
</section>

<script>

$(function(){
	
	
	$('#submit-btn').on('click',function(e){
		if(
			'' == $('#contact_tel').val() &&
			'' == $('#contact_mobile').val() &&
			'' == $('#contact_email').val()
		) { 
			alert('Please enter a contact number or email'); 
		} else {
			$('#loader').css('color','#007bff');
			$(this).attr('disabled',true);
			$('#update-form').submit();
		}
	});
	
});

</script>
</x-master>