<section class="content-area">
	<div class="container content">

		<div class="page-body">

			<div class="form-row clearfix">
				<div class="col-xs-12 col-sm-12">

				@if($reported)

					<div class="card bg-light mt-4 mb-4">
						<div class="card-header"><h3>Report An Issue</h3></div>
						<div class="card-body">
							<h4>Thank you for reporting this issue.</h4>
							<p>We will attend to your report and be in touch as soon as possible.</p>
						</div><!-- //card-body -->
					</div><!-- //card -->
					<div class="row">
						<div class="col col-xs-12">
							<a href="/dashboard" class="mt-2 mb-3 btn btn-block btn-sm btn-secondary">Back</a>
						</div>
					</div>

					@else

						<div class="card bg-light mt-4 mb-4">
							<div class="card-header"><h3>Report An Issue</h3></div>
							<div class="card-body">

								<form id="report-form" method="post" enctype="multipart/form-data" @submit.prevent="processForm">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-12 control-label">Issue Relates to:</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="issue_area" id="issue_area" required>
                                                <option value="">Select...</option>
                                                @foreach($issue_areas as $i)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 control-label">Full Description of Issue:</label>
                                        <div class="col-sm-12">
                                            <resizable-textarea>
                                                <textarea rows="6" class="form-control no-resize" name="issue_description" id="issue_description" required></textarea>
                                            </resizable-textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 control-label">Add Photos:</label>
                                        <div class="col-sm-12">
                                            <input class="form-control-file input-sm" name="issue_image[]" type="file" multiple id="issue_image" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-5">
                                        <div class="col-sm-12">
                                            <button :disabled="btnDisabled" type="submit" id="submit-btn" class="m-w-100 btn btn-sm btn-primary">Send</button>
                                            <a href="/dashboard" class="ml-2 btn btn-sm btn-outline-secondary">Cancel</a>
                                            <span v-if="loader" class="ml-3" style="vertical-align:-webkit-baseline-middle;color:#007bff;"><i class="fas fa-2x fa-sync fa-spin"></i></span>
                                        </div>
                                    </div>
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
    Vue.component('resizable-textarea', {
        methods: {
            resizeTextarea (event) {
                event.target.style.height = 'auto'
                event.target.style.height = (event.target.scrollHeight) + 'px'
            },
        },
        mounted () {
            this.$nextTick(() => {
                this.$el.setAttribute('style', 'height:' + (this.$el.scrollHeight) + 'px;overflow-y:hidden;')
            })
            this.$el.addEventListener('input', this.resizeTextarea)
        },
        beforeDestroy () {
            this.$el.removeEventListener('input', this.resizeTextarea)
        },
        render () {
            return this.$slots.default[0]
        },
    });

    new Vue({
        el: '#report-form',
        data: {
            loader: false,
            btnDisabled: false
        },
        methods: {
            processForm: function (ev){
                this.loader = true;
                this.btnDisabled = true;
                return true;
            }
        }
    });
</script>
a
