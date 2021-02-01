@extends('vendors.adminLayout')
@section('content')
<?php 
    use App\User;
?>
<section class="content-header">
    <h4>
        Create Event 
    </h4>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-body">
                        @if($user->can_pay_offline == User::PAY_ONLINE )
                            @if(!isset($data) && empty($data))
                                @if(!isset($payment)  )
                                <div class="row align-items-center" id="eventPayment" style="">
                                    <div class="col-md-12">
                                        <div class="payment_box">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="payment_box_in">
                                                    <h4>Payment</h4>
                                                    <p>if you want to create an event will be pay to admin to get the permission for creating the event each time.</p>
                                                    <p>Payment Amount: ₹{{ $paymentAmount->amount }}</p>
                                                   
                                                    <ul class="card_img">
                                                        <li><a herf="#"><img src="{{ asset('images/payment-1.png') }}"></a></li>
                                                        <li><a herf="#"><img src="{{ asset('images/payment-2.png') }}"></a></li>
                                                        <li><a herf="#"><img src="{{ asset('images/payment-3.png') }}"></a></li>
                                                        <li><a herf="#"><img src="{{ asset('images/payment-4.png') }}"></a></li>
                                                    </ul>
                                                    <form action="{{ url($Prefix . '/events/payment') }}" method="POST">
                                                        @csrf 

                                                        <script
                                                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                            data-key="pk_test_51HZAf7LkM2YxAR2pEechQp2WSVjNvFT4JZuSImLAqwwo44mi8fdoNtITfU9qmOlWsweLStJKS5qYf2RlRhOtBLqg00ifkQkkMB"
                                                            data-amount="{{ number_format(($paymentAmount->amount*100) , 0, '', '') }}"
                                                            data-email="{{ Auth::user()->email }}"
                                                            data-description="AmbiPlatforms"
                                                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                                            data-locale="auto"
                                                            data-currency="inr"
                                                        > 
                                                        </script>
                                                        <input type="hidden" name="amount" value="{{ $paymentAmount->amount }}">
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                        @endif

                            <form id="eventForm" action="{{ asset($Prefix . '/store-events') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @include('vendors.events.form')

                            </form>
                        </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection