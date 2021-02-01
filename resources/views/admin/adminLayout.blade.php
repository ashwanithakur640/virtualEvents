<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>AmbiPlatforms</title>
		<link href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
		<link rel="stylesheet"  href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">

		<!-- Start dataTable -->
        <link href="{{ asset('assets/css/dataTable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <!-- End dataTable -->
        
        <link href="{{ asset('assets/css/admin/admin.css') }}" rel="stylesheet">
        <link rel="shortcut icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
        <link rel="icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
		@yield('css')

	</head>
	<body id="page-top">
		<div id="wrapper">
			@include('admin.sidebar')

			<div id="content-wrapper" class="d-flex flex-column">
				<div id="content">
				    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
				      	<ul class="navbar-nav ml-auto">
							<li class="nav-item dropdown no-arrow d-sm-none">
								<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-search fa-fw"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
									<form class="form-inline mr-auto w-100 navbar-search">
										<div class="input-group">
											<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
											<div class="input-group-append">
												<button class="btn btn-primary" type="button">
													<i class="fas fa-search fa-sm"></i>
												</button>
											</div>
										</div>
									</form>
							</div>
							</li>
							<li class="nav-item dropdown no-arrow">
								<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="mr-2 d-none d-lg-inline text-gray-600 small">
										{{ Auth::user()->first_name}} {{ Auth::user()->middle_name}} {{ Auth::user()->last_name}}
									</span>
									@if(isset(Auth::user()->image) && !empty(Auth::user()->image))
									<img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/'.Auth::user()->image) }}" alt="image">
									@else
									<img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
									@endif

								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
									<a class="dropdown-item" href="{{ url('/admin/edit-profile') }}">
										<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
										Profile
									</a>
									<a class="dropdown-item" href="{{ url('/admin/change-password') }}">
										<i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
										Change Password
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="{{ url('admin/logout') }}">
										<i class="fas fa-power-off fa-sm fa-fw mr-2 text-gray-400"></i>
										Logout
									</a>
								</div>
							</li>
						</ul>
					</nav>
					<div class="container-fluid">
						@yield('content')

					</div>
				</div>
				<footer class="sticky-footer bg-white">
					<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright &copy; AmbiPlatforms {{ date("Y") }}</span>
					</div>
					</div>
				</footer>
			</div>
		</div>
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
		<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
		<script src="{{asset('assets/js/sb-admin-2.min.js')}}"></script>

		<script src="{{asset('assets/js/moment.min.js')}}"></script>
		<script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
		<script src="{{asset('assets/js/parsley.js')}}"></script>

        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->

        <!-- Start custom js -->
        <script src="{{ asset('assets/js/admin/admin.js') }}"></script>
        <!-- End custom js -->

        <!-- Start dataTables -->
        <script src="{{ asset('assets/js/dataTable/dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTable/dataTables.bootstrap.min.js') }}"></script>
        <!-- End dataTables -->
        @if(\Request::segment(3) == 'reply-answer')
        <!-- Start ckeditor Basic -->
        <script src="{{ asset('js/ckeditor_basic/ckeditor.js')}}" type="text/javascript"></script>
        <script src="{{ asset('js/ckeditor_basic/samples/js/sample.js')}}" type="text/javascript"></script>
		<script>
		    if ($('#editor').length > 0) {
		        initSample();
		    }
		</script>
		<!-- End ckeditor Basic -->
		@endif

        <!-- Start ckeditor -->
        <script src="{{ asset('js/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
		<script>
		    if ($('#summary-ckeditor').length > 0) {
		        CKEDITOR.replace( 'summary-ckeditor' );
		    }
		</script>
		<!-- End ckeditor -->

		

		<!-- @stack('scripts') -->

		@yield('script')

		<!-- Start date and time picker -->
        <script type="text/javascript">
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                sideBySide: true,
                minDate: new Date(),
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fa fa-angle-up',
                    down: 'fa fa-angle-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'far fa-trash-alt',
                    close: 'far fa-times-circle'
                }
            })
        </script>
        <script type="text/javascript">
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                sideBySide: true,
                minDate: new Date(),
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                     up: 'fa fa-angle-up',
                    down: 'fa fa-angle-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'far fa-trash-alt',
                    close: 'far fa-times-circle'
                }
            })
        </script>
       
		<script type="text/javascript">
			$(document).ready(function () {
			    window.Parsley.addValidator('daterangevalidation', {
				    validateString: function(value) {
					    var allowed = true;

					    var date1 = new Date($('#start_date').val());
					    var date2 = new Date($('#end_date').val());

					    if(date1.getTime() >= date2.getTime()){
					      	return false;
					    }
					    return true;
				  	},
				  	messages: {
				    	en: 'End date must be after start date.',
				  	}
				});

				$('#eventForm #submit').on('click', function () {
				    $('#eventForm').parsley().validate();
				    validateFront();
				});

				var validateFront = function () {
				    if (true === $('#eventForm').parsley().isValid()) {
				      	$('.bs-callout-info').removeClass('hidden');
				      	$('.bs-callout-warning').addClass('hidden');
				    } else {
				      	$('.bs-callout-info').addClass('hidden');
				      	$('.bs-callout-warning').removeClass('hidden');
				    }
				};
			});
		</script>
		<!-- End date and time picker -->

		<script>
			/* Start delete record confirmation */
			$(document).on('click','.deleteRecord', function(){
			    var data =  confirm("Are you sure you want to delete?");
			    if(data == true){
			        $(this).next().submit();
			    }
			    return false;
			});
			/* End delete record confirmation */
			
			/* Start alert message fade out */
            $('.alert-success').delay(5000).fadeOut('slow');
            $('.alert-warning').delay(5000).fadeOut('slow');
            $('.alert-danger').delay(5000).fadeOut('slow');
            /* End alert message fade out */
		</script>
	</body>
</html>
