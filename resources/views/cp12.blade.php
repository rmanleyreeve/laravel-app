<x-master>
<section class="content-area">
	<div class="container content">
	
		<div class="page-body">

			<div class="card bg-light mt-4 mb-4">
				<div class="card-header"><h3>Your Gas Safety Certificate</h3></div>
				<div class="card-body p-2">
					
					<div class="row">
						<div class="col col-8">
							<p class="mb-0">The gas safety certificate, also known as a CP12, is a legal requirement in any rented property. This gas certificate is obtained via an annual inspection, and is designed to certify that the appliances are in good working order and safe to use. </p>
						</div>
						<div class="col col-4">
							<img class="img-fluid" src="/img/gas-safe-logo.png" alt=""/>
						</div>
					</div>
					
					<hr>
					@isset($selected)
						<h4 class="mb-3">CP12 Details for {{ $selected->property_address }} {{ $selected->property_postcode }}</h4>
						<p>Certificate expires: <strong>{{ date('j M Y',strtotime($selected->document_expiry)) }}</strong></p>
						<?php $file = $utils->getDoc($selected->property_fk,'CP12 Certificate',$selected->document_id); ?>
						@isset($file)
                            <?php
							//build token
							$p = $selected->property_fk;
							$d = $selected->document_id;
							$k = env('CP12_KEY');
							$token = md5("p={$p}&d={$d}&k={$k}");
							$ext = pathinfo($file,PATHINFO_EXTENSION);
							$link = "https://admin.pbne.co.uk/homezone/cp12/{$p}/{$d}/{$token}.{$ext}";
							?>
						@else
							<?php $link = "javascript:alert('We cannot display your certificate at the moment, please contact your letting agent or landlord');"; ?>
						@endisset
						<p><a href="<?php echo $link;?>" target="@isset($file) _blank @endisset"><strong>View Certificate</strong> &nbsp; <i class="fas fa-external-link-alt"></i></a></p>
					@else
						<h4 class="text-danger">No CP12 on file for this property, please contact your letting agent or landlord</h4>
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
</x-master>