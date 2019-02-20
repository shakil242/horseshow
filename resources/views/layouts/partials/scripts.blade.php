<!-- REQUIRED JS SCRIPTS -->

<!-- Modal -->
<div id="feedbackModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Feedback</h4>
			</div>
			{!! Form::open(['url'=>'#','method'=>'post', 'id'=>'feedback-form']) !!}
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<p>
							<span class="question">What do you like about the application? </span>
							<input type="text" class="form-control" name="qno1" required>
						</p>
						<br>
					</div>
					<div class="col-sm-10">
						<p>
							<span class="question">What do you dislike / didn't like about the application?</span>
							<input type="text" class="form-control" name="qno2" required>
						</p>
						<br>
					</div>
					<div class="col-sm-10">
						<p>
							<span class="question"> Any ideas to improve the application?</span>
							<input type="text" class="form-control" name="qno3" required>
						</p>
						<br>
					</div>
					<div class="col-sm-10">
						<p>
							<span class="question">Any issues faced in the application?</span>
							<input type="text" class="form-control" name="qno4" required>
						</p>
						<br>
					</div>
				</div>

			 
			</div>
			<div class="modal-footer">
			<div id="loading" class="text-center"><i class="feedback-spinner fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></div>
			 {!! Form::submit("Share" , ['class' =>"btn btn-feedback"]) !!}    

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			{!! Form::close() !!}
		</div>

	</div>
</div>
<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js -->
<!-- Laravel App -->

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="{{ asset('/adminstyle/js/vender/bootstrap.min.js') }}"></script>
		<script src="{{ asset('/adminstyle/js/vender/bootstrap-select.js') }}"></script>
		<script src="{{ asset('/adminstyle/js/vender/bootstrap-colorpicker.js') }}"></script>
		<script src="{{ asset('/adminstyle/js/vender/jquery.mCustomScrollbar.min.js') }}"></script>

		<link href="{{ asset('/old_css/vender/bootstrap-dialog.min.css') }}" rel="stylesheet" />
		<script src="{{ asset('/js/vender/bootstrap-dialog.min.js') }}"></script>

		<script src="{{ asset('/js/custom.js') }}"></script>
		<script src="{{asset('/js/main.js') }}"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
			Both of these plugins are recommended to enhance the
			user experience. Slimscroll is required when using the
			fixed layout. -->
<script>

		window.Laravel = {!! json_encode([
				'csrfToken' => csrf_token(),
		]) !!};
</script>
