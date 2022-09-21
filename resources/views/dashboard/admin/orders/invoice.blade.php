@extends('dashboard.admin.layouts.app')

@section('content')

	@include('dashboard.admin.orders.includes.invoice')

    <div>
        <h2>@lang('orders.orders')</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a class="back-page" href="{{ route('dashboard.admin.home') }}">@lang('site.home')</a></li>
        <li class="breadcrumb-item"><a class="back-page" href="{{ route('dashboard.admin.orders.index') }}">@lang('orders.orders')</a></li>
        <li class="breadcrumb-item">@lang('site.create')</li>
    </ul>

    <div class="row">

        <div class="col-md-12">

            <div class="tile shadow">

            	<div class="row mb-2">

                    <div class="col-md-12">
                        
                        <a data-type="pdf" href="#" class="btn btn-primary export-invoice"><i class="fas fa-file-invoice-dollar"></i> @lang('invoices.expor_pdf')</a>
                        <a data-type="png" href="#" class="btn btn-primary export-invoice"><i class="fa-solid fa-image"></i> @lang('invoices.expor_image')</a>

                    </div>

                </div><!-- end of row -->

		        <div id="myInvoice" class="invoice-box">

					<table cellpadding="0" cellspacing="0">
						<tr class="top">
							<td colspan="2">
								<table>
									<tr>
										<td class="title">
											<img src="{{ asset('invoice/images/logo.jpg') }}" style="width: 40%; max-width: 100px; margin-right: -48px;" />
										</td>

										<td>
											@lang('invoices.invoice_created') #: {{ now()->format('Y-m-d') }}<br />
											@lang('invoices.created_order') : {{ $order->created_at->format('Y-m-d') }} <br/>
											{{-- Due: February 1, 2015 --}}
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class="information">
							<td colspan="2">
								<table>
									<tr>
										<td>
											@lang('orders.name') <br/>
											@lang('orders.event_data') <br/>
											@lang('orders.phone') <br/>
										</td>

										<td>
											{{ $order->user->username }}.<br />
											{{ $order->event_data }}.<br />
											<a href="tel:{{ $order->user->phone }}">{{ $order->user->phone }}</a>.<br />
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class="heading">
							<td>@lang('orders.payment_method')</td>

							<td>@lang('invoices.price') #</td>
						</tr>

						<tr class="details">
							<td>{{ $order->payment_order->payment_name ?? '' }}</td>

							<td>{{ $order->payment_order->payment_name ?? '' }}</td>
						</tr>

						{{-- <tr class="heading">
							<td>Item</td>

							<td>Price</td>
						</tr>

						<tr class="item">
							<td>Website design</td>

							<td>$300.00</td>
						</tr>

						<tr class="item">
							<td>Hosting (3 months)</td>

							<td>$75.00</td>
						</tr>

						<tr class="item last">
							<td>Domain name (1 year)</td>

							<td>$10.00</td>
						</tr> --}}

						<tr class="total">
							<td></td>

							<td>Total: $385.00</td>
						</tr>
					</table>
				</div>{{-- invoice-box --}}

            </div><!-- end of tile -->

        </div><!-- end of col -->

    </div><!-- end of row -->

@endsection

@push('scripts')

	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script type="text/javascript">

		$('.export-invoice').on('click', function (e) {
			e.preventDefault();

			var type 	= $(this).data('type');
			var namefile= "{{ now()->format('Y-m-d') .'-'. $order->user->username }}";
			var element = document.getElementById('myInvoice');

			var opt = {
			  margin:       1,
			  filename:     `${namefile}.`+type,
			  image:        { type: 'jpeg', quality: 0.98 },
			  html2canvas:  { scale: 2 },
			  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
			};

			// New Promise-based usage:
			let url    = "{{ route('dashboard.admin.orders.invoice.store', $order->id) }}";
			let method = "get";
			// var data   = html2pdf().set(opt).from(element).save();
			var dd   = html2pdf(element,opt);
		 	var formData = new FormData();
        	formData.append('pdf', dd);

			$.ajax({
				url: url,
				method: method,
				data: formData,
				processData: false,
	            contentType: false,
	            cache: false,
			});
			
			// var movie = html2pdf().set(opt).from(element).save();
	        // console.log(movie);
	        // var movie.files;
			// console.log(html2pdf().set(opt).from(element).save());

			// html2pdf(element, opt);

		});//end of click

	</script>

@endpush