@extends('frontend.layouts.master')

@section('content')


    <!--=============================
        BANNER START
    ==============================-->
   @include('frontend.home.components.sider')
    <!--=============================
        BANNER END
    ==============================-->


    <!-- CART POPUT START -->
    @include('frontend.home.components.cart-popup')
    <!-- CART POPUT END -->
    <!--=============================
        OFFER ITEM END
    ==============================-->


    <!--=============================
        MENU ITEM START
    ==============================-->
    @include('frontend.home.components.menu-item')
    <!--=============================
        MENU ITEM END
    ==============================-->



    <!--=============================
       TESTIMONIAL  START
    ==============================-->
  @include('frontend.home.components.testimonial')
    <!--=============================
        TESTIMONIAL END
    ==============================-->



@endsection
