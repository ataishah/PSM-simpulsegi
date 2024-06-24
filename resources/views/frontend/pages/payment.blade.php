@extends('frontend.layouts.master')

@section('content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="fp__breadcrumb" style="background: url(images/counter_bg.jpg);">
        <div class="fp__breadcrumb_overlay">
            <div class="container">
                <div class="fp__breadcrumb_text">
                    <h1>payment</h1>
                    <ul>
                        <li><a href="{{  url('/') }}">home</a></li>
                        <li><a href="javascript:;">payment</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


    <!--============================
        PAYMENT PAGE START
    ==============================-->
    <section class="fp__payment_page mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <h2>Choose Your Payment Gateway</h2>
                <div class="col-lg-8">
                    <div class="fp__payment_area">
                        <div class="row">
                            <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                <a class="fp__single_payment payment-card" data-name="paypal" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    href="#">
                                    <img src="{{ asset('frontend/images/pay_1.jpg') }}" alt="payment method" class="img-fluid w-100">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div id="sticky_sidebar" class="fp__cart_list_footer_button">
                        <h6>total cart</h6>
                        <p>subtotal: <span>{{ currencyPosition(cartTotal()) }}</span></p>
                        @if (session()->has('coupon'))
                        <p>discount: <span>{{ currencyPosition(session()->get('coupon')['discount']) }}</span></p>
                        @else
                        <p>discount: <span>{{ currencyPosition(0) }}</span></p>

                        @endif
                        <p class="total"><span>total:</span> <span id="grand_total">{{ currencyPosition(grandCartTotal()) }}</span></p>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
      $(document).ready(function(){
            $('.payment-card').on('click', function(e){
                e.preventDefault();

                let paymentGateway = $(this).data('name');

                $.ajax({
                    method: 'POST',
                    url: '{{ route("make-payment") }}',
                    data: {
                        payment_gateway: paymentGateway
                    },
                    beforeSend: function(){
                        showLoader();
                    },
                    success: function(response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function(xhr, status, error){
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(index, value){
                            toastr.error(value);
                        });
                    },
                    complete: function() {
                        hideLoader();
                    }
                })
            });
        })
</script>

@endpush

