<x-master>
    <section class="content-area">
	<div class="container content">
	
		<?php //pp(selected); //exit; ?>
				
		<div class="page-body">
	
			<ul class="dashboard-buttons">
				<li><button type="button" class="btn" data-href="info">Property Information</button></li>
				<li><button type="button" class="btn" data-href="cp12">Gas Safety Certificate</button></li>
				<li><button type="button" class="btn" data-href="agent">{{ ($selected->managed_tenancy)?'Letting Agent':'Landlord' }} Details</button></li>
				<li><button type="button" class="btn" data-href="report">Report An Issue</button></li>
				<li><button type="button" class="btn" data-href="rent">Rent Statement</button></li>
				<li><button type="button" class="btn" data-href="bond">Bond Statement</button></li>
				<li><button type="button" class="btn" data-href="inspections">Inspections</button></li>
				<li><button type="button" class="btn" data-href="update">Update My Details</button></li>
				<li><button type="button" class="btn" data-href="logout">Log Out</button></li>
			</ul>		
	
		</div><!-- //page-body -->
	
	</div>
</section>

<script>

$(function(){
		
	$('.dashboard-buttons li button').on('click',function(){
		location = $(this).data('href');
	});

});

</script>
</x-master>