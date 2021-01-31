    <section class="content-area">
	<div class="container content">

		<div class="page-body">

			<ul class="dashboard-buttons" id="dashboard-buttons">
				<li><button type="button" class="btn" v-on:click="goto" data-href="info">Property Information</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="cp12">Gas Safety Certificate</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="agent">{{ ($selected->managed_tenancy)?'Letting Agent':'Landlord' }} Details</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="report">Report An Issue</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="rent">Rent Statement</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="bond">Bond Statement</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="inspections">Inspections</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="update">Update My Details</button></li>
				<li><button type="button" class="btn" v-on:click="goto" data-href="logout">Log Out</button></li>
			</ul>

		</div><!-- //page-body -->

	</div>
</section>

<script>

    new Vue({
        el: '#dashboard-buttons',
        methods: {
            goto: function (ev){
                window.location = ev.target.dataset.href;
            }
        }
    });

</script>
