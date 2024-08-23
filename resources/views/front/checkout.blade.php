@extends('front.Layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                        <li class="breadcrumb-item">Checkout</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-9 pt-4">
            <div class="container">
                <form action="" id="orderForm" name="orderForm" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="sub-title">
                                <h2>Shipping Address</h2>
                            </div>
                            <div class="card shadow-lg border-0">
                                <div class="card-body checkout-form">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="first_name" id="first_name" class="form-control"
                                                    placeholder="First Name"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="last_name" id="last_name" class="form-control"
                                                    placeholder="Last Name"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                                <p></p>

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="email" id="email" class="form-control"
                                                    placeholder="Email"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                                <p></p>

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <select name="country" id="country" class="form-control">
                                                    <option value="0">Select a Country</option>

                                                    @if (!empty($countries))
                                                        @foreach ($countries as $item)
                                                            <option
                                                                {{ !empty($customerAddress) && $customerAddress->country_id == $item->id ? 'selected' : '' }}
                                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control"> {{ !empty($customerAddress) ? $customerAddress->first_name : '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="apartment" id="apartment" class="form-control"
                                                    placeholder="Apartment, suite, unit, etc. (optional)"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="city" id="city" class="form-control"
                                                    placeholder="City"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="state" id="state" class="form-control"
                                                    placeholder="State"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->state : '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="zip" id="zip" class="form-control"
                                                    placeholder="Zip"
                                                    value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <input type="text" name="mobile" id="mobile" class="form-control"
                                                    placeholder="Mobile No."
                                                    value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <textarea name="notes" id="notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                    class="form-control"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="sub-title">
                                <h2>Order Summery</h3>
                            </div>
                            <div class="card cart-summery">
                                <div class="card-body">
                                    {{-- @dd(Cart::content()) --}}
                                    @foreach (Cart::content() as $item)
                                        <div class="d-flex justify-content-between pb-2">
                                            <div class="h6">{{ $item->name }} X {{ $item->qty }} </div>
                                            <div class="h6">${{ $item->price * $item->qty }}</div>
                                        </div>
                                    @endforeach

                                    <div class="d-flex justify-content-between summery-end">
                                        <div class="h6"><strong>Subtotal</strong></div>
                                        <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                                    </div>

                                    <div class="d-flex justify-content-between summery-end">
                                        <div class="h6"><strong>Discount</strong></div>
                                        <div class="h6"><strong
                                                id="discount_value">${{ number_format($discount, 2) }}</strong>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="h6"><strong>Shipping</strong></div>
                                        <div class="h6">
                                            <strong
                                                id="totalShippingCharges">${{ number_format($totalShippingCharges, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 summery-end">
                                        <div class="h5"><strong>Total</strong></div>
                                        <div class="h5"><strong
                                                id="grandTotal">${{ number_format($grandTotal, 2) }}</strong></div>
                                    </div>
                                </div>
                            </div>


                            {{-- coupon--------------- --}}
                            <div class="input-group apply-coupan mt-4">
                                <input type="text" placeholder="Coupon Code" class="form-control" id="discount"
                                    name="discount">
                                <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                            </div>
                            <p></p>

                            <div id="discount-wrapper">
                                @if (Session::has('code'))
                                    <div class=" mt-4" id="discount-section">
                                        <strong> {{ Session::get('code')->code }} </strong>
                                        <a class="btn btn-sm btn-danger" id="remove_coupon"><i
                                                class="fa fa-times"></i></a>
                                    </div>
                                @endif
                            </div>
                            {{-- coupon--------------- --}}



                            <div class="card payment-form ">
                                <h3 class="card-title h5 mb-3">Payment Method</h3>
                                <div class="">
                                    <input type="radio" checked name="payment_method" value="cod"
                                        id="payment_method_one">
                                    <label for="payment_method_one" class="form-check-label">COD</label>
                                </div>

                                <div class="">
                                    <input type="radio" name="payment_method" value="cod" id="payment_method_two">
                                    <label for="payment_method_two" class="form-check-label">Stripe</label>
                                </div>










                                <div class="card-body p-0 mt-4 d-none" id="card-payment-form">
                                    <div class="mb-3">
                                        <label for="card_number" class="mb-2">Card Number</label>
                                        <input type="text" name="card_number" id="card_number"
                                            placeholder="Valid Card Number" class="form-control">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="expiry_date" class="mb-2">Expiry Date</label>
                                            <input type="text" name="expiry_date" id="expiry_date"
                                                placeholder="MM/YYYY" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="expiry_date" class="mb-2">CVV Code</label>
                                            <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div class="pt-4">
                                    <button class="btn-dark btn btn-block w-100" type="submit">Pay Now</button>
                                </div>
                            </div>


                            <!-- CREDIT CARD FORM ENDS HERE -->

                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection





@section('customJs')
    <script type="text/javascript">
        // form submission----------
        $('#orderForm').submit(function(event) {
            event.preventDefault();
            $("button[type=submit]").prop('disabled', true)


            $.ajax({
                url: '{{ route('front.processCheckout') }}',
                type: 'post',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false)

                    var error = response.errors;
                    if (response.status == false) {
                        if (error.first_name) {
                            $('#first_name').siblings("p").addClass('invalid-feedback').html(error
                                .first_name);
                            $('#first_name').addClass("is-invalid");
                        } else {
                            $('#first_name').siblings("p").removeClass('invalid-feedback').html();
                            $('#first_name').removeClass("is-invalid");
                        }

                        if (error.last_name) {
                            $('#last_name').siblings("p").addClass('invalid-feedback').html(error
                                .last_name);
                            $('#last_name').addClass("is-invalid");
                        } else {
                            $('#last_name').siblings("p").removeClass('invalid-feedback').html();
                            $('#last_name').removeClass("is-invalid");
                        }

                        if (error.email) {
                            $('#email').siblings("p").addClass('invalid-feedback').html(error
                                .email);
                            $('#email').addClass("is-invalid");
                        } else {
                            $('#email').siblings("p").removeClass('invalid-feedback').html();
                            $('#email').removeClass("is-invalid");
                        }

                        if (error.address) {
                            $('#address').siblings("p").addClass('invalid-feedback').html(error
                                .address);
                            $('#address').addClass("is-invalid");
                        } else {
                            $('#address').siblings("p").removeClass('invalid-feedback').html();
                            $('#address').removeClass("is-invalid");
                        }

                        if (error.country) {
                            $('#country').siblings("p").addClass('invalid-feedback').html(error
                                .country);
                            $('#country').addClass("is-invalid");
                        } else {
                            $('#country').siblings("p").removeClass('invalid-feedback').html();
                            $('#country').removeClass("is-invalid");
                        }

                        if (error.city) {
                            $('#city').siblings("p").addClass('invalid-feedback').html(error
                                .city);
                            $('#city').addClass("is-invalid");
                        } else {
                            $('#city').siblings("p").removeClass('invalid-feedback').html();
                            $('#city').removeClass("is-invalid");
                        }

                        if (error.state) {
                            $('#state').siblings("p").addClass('invalid-feedback').html(error
                                .state);
                            $('#state').addClass("is-invalid");
                        } else {
                            $('#state').siblings("p").removeClass('invalid-feedback').html();
                            $('#state').removeClass("is-invalid");
                        }

                        if (error.zip) {
                            $('#zip').siblings("p").addClass('invalid-feedback').html(error
                                .zip);
                            $('#zip').addClass("is-invalid");
                        } else {
                            $('#zip').siblings("p").removeClass('invalid-feedback').html();
                            $('#zip').removeClass("is-invalid");
                        }

                        if (error.mobile) {
                            $('#mobile').siblings("p").addClass('invalid-feedback').html(error
                                .mobile);
                            $('#mobile').addClass("is-invalid");
                        } else {
                            $('#mobile').siblings("p").removeClass('invalid-feedback').html();
                            $('#mobile').removeClass("is-invalid");
                        }

                        if (error.order_notes) {
                            $('#notes').siblings("p").addClass('invalid-feedback').html(error
                                .notes);
                            $('#notes').addClass("is-invalid");
                        } else {
                            $('#notes').siblings("p").removeClass('invalid-feedback').html();
                            $('#notes').removeClass("is-invalid");
                        }

                        if (error.zip) {
                            $('#zip').siblings("p").addClass('invalid-feedback').html(error
                                .zip);
                            $('#zip').addClass("is-invalid");
                        } else {
                            $('#zip').siblings("p").removeClass('invalid-feedback').html();
                            $('#zip').removeClass("is-invalid");
                        }

                        if (error.country) {
                            $('#country').siblings("p").addClass('invalid-feedback').html(error
                                .country);
                            $('#country').addClass("is-invalid");
                        } else {
                            $('#country').siblings("p").removeClass('invalid-feedback').html();
                            $('#country').removeClass("is-invalid");
                        }
                    } else {
                        window.location.href = "{{ url('thankyou/') }}/" + response.orderId;
                    }
                }
            });


        })

        // form submission----------

















        $("#payment_method_one").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").addClass('d-none')
            }
        });


        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none')
            }
        });















        // when we change country-----------
        $("#country").change(function() {
            $.ajax({
                url: '{{ route('front.getOrderSummary') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    country_id: $(this).val()
                },
                success: function(response) {
                    if (response.status == true) {
                        $("#totalShippingCharges").html("$" + response.totalShippingCharges);
                        $("#grandTotal").html("$" + response.grandTotal);

                    }
                }
            });
        })
        // when we change country-----------










        // apply discount-------------

        $('#apply-discount').click(function() {
            $.ajax({
                url: '{{ route('front.apply-discount') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    code: $("#discount").val(),
                    country_id: $("#country").val()
                },
                success: function(response) {
                    if (response.status == true) {
                        $("#totalShippingCharges").html("$" + response.totalShippingCharges);
                        $("#grandTotal").html("$" + response.grandTotal);
                        $("#discount_value").html("$" + response.discount);
                        $("#totalShippingCharges").html("$" + response.totalShippingCharges);
                        $("#discount-wrapper").html(response.discountSection);

                    } else {
                        $("#discount-wrapper").html("<span class='text-danger'>"+response.message+"</span>");
                       

                    }
                }
            });
        })
        // apply discount-------------















        // remove discount---------
        $('body').on('click', "#remove_coupon", function() {
            $.ajax({
                url: '{{ route('front.remove_coupon') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    country_id: $("#country").val()
                },
                success: function(response) {
                    if (response.status == true) {
                        $("#totalShippingCharges").html("$" + response.totalShippingCharges1);
                        $("#grandTotal").html("$" + response.grandTotal);
                        $("#discount_value").html("$" + response.discount);
                        $("#totalShippingCharges").html("$" + response.totalShippingCharges);
                        $("#discount-section").html("");
                        $("#discount").val("");



                    }
                }
            });
        })
        // remove discount---------
    </script>
@endsection
