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

								<form id="update-form" method="post" @submit.prevent="processForm">
                                    @csrf
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Name:</strong></label>
										<div class="col-sm-12">
											<input v-model="contact_name" class="form-control input-sm" name="contact_name" id="contact_name" :required="true" />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Telephone:</strong></label>
										<div class="col-sm-12">
											<input v-model="contact_tel" type="tel" class="form-control input-sm" name="contact_tel" id="contact_tel"  />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Mobile:</strong></label>
										<div class="col-sm-12">
											<input v-model="contact_mobile" type="tel" class="form-control input-sm" name="contact_mobile" id="contact_mobile" />
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 control-label"><strong>Contact Email:</strong></label>
										<div class="col-sm-12">
											<input v-model="contact_email" type="email" class="form-control input-sm" name="contact_email" id="contact_email" />
										</div>
									</div>
									<div class="form-group row mt-5">
										<div class="col-sm-12">
                                            <button :disabled="btnDisabled" type="submit" id="submit-btn" class="m-w-100 btn btn-sm btn-primary">Send</button>
                                            <a href="/dashboard" class="ml-2 btn btn-sm btn-outline-secondary">Cancel</a>
                                            <span v-if="loader" class="ml-3" style="vertical-align:-webkit-baseline-middle;color:#007bff;"><i class="fas fa-2x fa-sync fa-spin"></i></span>
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
    new Vue({
        el: '#update-form',
        data: {
            loader: false,
            btnDisabled: false,
            contact_name: '{!! $selected->contact_name !!}',
            contact_tel: '{!! $selected->contact_tel !!}',
            contact_mobile: '{!! $selected->contact_mobile !!}',
            contact_email: '{!! $selected->contact_email !!}'
        },
        methods: {
            processForm: function (ev){
                if(!this.contact_tel && !this.contact_mobile && !this.contact_email){
                    alert('Please enter a contact number or email');
                } else {
                    this.loader = true;
                    this.btnDisabled = true;
                    return false;
                }
            }
        }
    });
</script>
