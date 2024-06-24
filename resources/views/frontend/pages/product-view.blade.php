@extends('frontend.layouts.master')

@section('content')
    <!--=============================
                        BREADCRUMB START
                    ==============================-->
    <section class="fp__breadcrumb" style="background: url(images/counter_bg.jpg);">
        <div class="fp__breadcrumb_overlay">
            <div class="container">
                <div class="fp__breadcrumb_text">
                    <h1>product Details</h1>
                    <ul>
                        <li><a href="{{ url('/') }}">home</a></li>
                        <li><a href="javascript:;">Product Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                        BREADCRUMB END
                    ==============================-->


    <!--=============================
                        MENU DETAILS START
                    ==============================-->
    <section class="fp__menu_details mt_115 xs_mt_85 mb_95 xs_mb_65">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-9 wow fadeInUp" data-wow-duration="1s">
                    <div class="exzoom hidden" id="exzoom">
                        <div class="exzoom_img_box fp__menu_details_images">
                            <ul class='exzoom_img_ul'>
                                <li><img class="zoom ing-fluid w-100" src="{{ asset($product->thumb_image) }}"
                                        alt="product"></li>

                                @foreach ($product->productImages as $image)
                                    <li><img class="zoom ing-fluid w-100" src="{{ asset($image->image) }}" alt="product">
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="exzoom_nav"></div>
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="far fa-chevron-left"></i>
                            </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="far fa-chevron-right"></i>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__menu_details_text">
                        <h2>{!! $product->name !!}</h2>
                        <p class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <span>(201)</span>
                        </p>
                        <h3 class="price">
                            @if ($product->offer_price > 0)
                                {{ currencyPosition($product->offer_price) }}
                                <del>{{ currencyPosition($product->price) }}</del>
                            @else
                                {{ currencyPosition($product->price) }}
                            @endif
                        </h3>
                        <p class="short_description">{!! $product->short_description !!}</p>
                    </div>

                    <form action="" id="v_add_to_cart_form">
                        @csrf
                        <input type="hidden" name="base_price" class="v_base_price"
                            value="{{ $product->offer_price > 0 ? $product->offer_price : $product->price }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">


                        <div class="details_quentity">
                            <h5>select quantity</h5>
                            <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                                <div class="quentity_btn">
                                    <button class="btn btn-danger v_decrement"><i class="fal fa-minus"></i></button>
                                    <input type="text" name="quantity" placeholder="1" value="1" readonly
                                        id="v_quantity">
                                    <button class="btn btn-success v_increment"><i class="fal fa-plus"></i></button>
                                </div>
                                <h3 id="v_total_price">
                                    {{ $product->offer_price > 0 ? currencyPosition($product->offer_price) : currencyPosition($product->price) }}
                                </h3>
                            </div>
                        </div>
                    </form>
                    <ul class="details_button_area d-flex flex-wrap">
                        @if ($product->quantity === 0)
                            <li><a class="common_btn bg-danger" href="javascript:;">Stock Out</a></li>
                        @else
                            <li><a class="common_btn v_submit_button" href="#">add to cart</a></li>
                        @endif
                        <li><a class="wishlist" href="#"><i class="far fa-heart"></i></a></li>
                    </ul>
                </div>
            </div>

            <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                <div class="fp__menu_description_area mt_100 xs_mt_70">
                    <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Description</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="menu_det_description">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab" tabindex="0">
                                    <div class="menu_det_description">
                                        {!! $product->long_description !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            @push('scripts')
                <script>
                    $(document).ready(function() {
                        function v_updateTotalPrice() {
                            let basePrice = parseFloat($('.v_base_price').val());
                            let quantity = parseFloat($('#v_quantity').val());
                            let totalPrice = basePrice * quantity;
                            $('#v_total_price').text("{{ config('settings.site_currency_icon') }}" + totalPrice.toFixed(2));
                        }

                        $('.v_increment').on('click', function(e) {
                            e.preventDefault();
                            let quantity = $('#v_quantity');
                            let currentQuantity = parseFloat(quantity.val());
                            quantity.val(currentQuantity + 1);
                            v_updateTotalPrice();
                        });

                        $('.v_decrement').on('click', function(e) {
                            e.preventDefault();
                            let quantity = $('#v_quantity');
                            let currentQuantity = parseFloat(quantity.val());
                            if (currentQuantity > 1) {
                                quantity.val(currentQuantity - 1);
                                v_updateTotalPrice();
                            }
                        });

                        $('.v_submit_button').on('click', function(e) {
                            e.preventDefault();
                            $("#v_add_to_cart_form").submit();
                        });

                        $("#v_add_to_cart_form").on('submit', function(e) {
                            e.preventDefault();

                            let formData = $(this).serialize();
                            $.ajax({
                                method: 'POST',
                                url: '{{ route('add-to-cart') }}',
                                data: formData,
                                beforeSend: function() {
                                    $('.v_submit_button').attr('disabled', true);
                                    $('.v_submit_button').html(
                                        '<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Loading...'
                                        );
                                },
                                success: function(response) {
                                    updateSidebarCart();
                                    toastr.success(response.message);
                                },
                                error: function(xhr, status, error) {
                                    let errorMessage = xhr.responseJSON.message;
                                    toastr.error(errorMessage);
                                },
                                complete: function() {
                                    $('.v_submit_button').html('Add to Cart');
                                    $('.v_submit_button').attr('disabled', false);
                                }
                            });
                        });

                        // Initial calculation
                        v_updateTotalPrice();
                    });
                </script>
            @endpush
