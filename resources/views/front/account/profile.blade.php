@extends('front.Layouts.app')


@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                        <li class="breadcrumb-item">Settings</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container  mt-5">
                <div class="row">
                    <div class="col-md-12">
                        @include('front.account.common.message')
                    </div>
                    <div class="col-md-3">
                        @include('front.account.common.sidebar')
                    </div>
                    <div class="col-md-9">


                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                            </div>

                            <form action="" name="profileForm" id="profileForm">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name"
                                                placeholder="Enter Your Name" class="form-control"
                                                value="{{ $users->name }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email"
                                                placeholder="Enter Your Email" class="form-control"
                                                value="{{ $users->email }}">
                                            <p></p>

                                        </div>
                                        <div class="mb-3">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" id="phone"
                                                placeholder="Enter Your Phone" class="form-control"
                                                value="{{ $users->phone }}">
                                            <p></p>

                                        </div>


                                        <div class="d-flex">
                                            <button class="btn btn-dark">Update</button>
                                        </div>
                                    </div>
                            </form>
                        </div>




                        <div class="card ">
                            <div class="card-header ">
                                <h2 class="h5 mb-0 pt-2 pb-2 mt-4">Address</h2>
                            </div>

                            <form action="" name="addressForm" id="addressForm">
                                <div class="card-body p-4">
                                    <div class="row">


                                        <div class="mb-3 col-md-6">
                                            <label for="name">First Name</label>
                                            <input type="text" name="first_name" id="first_name"
                                                placeholder="Enter Your First Name" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>


                                        <div class="mb-3 col-md-6">
                                            <label for="name">Last Name</label>
                                            <input type="text" name="last_name" id="last_name"
                                                placeholder="Enter Your Last Name" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email"
                                                placeholder="Enter Your Email" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="mobile" id="mobile"
                                                placeholder="Enter Your Phone" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone">Country</label>
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($country->isNotEmpty())
                                                    @foreach ($country as $item)
                                                        <option
                                                            {{ $customerAddress && $item->id == $customerAddress->country_id ? 'selected' : '' }}
                                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone">Address</label>
                                            <textarea class="form-control" name="address" id="address" cols="30" rows="10">{{ $customerAddress ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone">City</label>
                                            <input type="text" name="city" id="city"
                                                placeholder="Enter Your city" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone">State</label>
                                            <input type="text" name="state" id="state"
                                                placeholder="Enter Your state" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->state : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone">Apartment</label>
                                            <input type="text" name="apartment" id="apartment"
                                                placeholder="Enter Your apartment" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->apartment : '' }}">
                                            <p></p>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone">Zip</label>
                                            <input type="text" name="zip" id="zip"
                                                placeholder="Enter Your zip" class="form-control"
                                                value="{{ $customerAddress ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>









                                        <div class="d-flex">
                                            <button class="btn btn-dark">Update</button>
                                        </div>
                                    </div>
                            </form>
                        </div>



                    </div>
                </div>
            </div>
            </div>
        </section>
    </main>
@endsection

@section('customJs')
    <script>
        $("#profileForm").submit(function(e) {
            e.preventDefault();


            $.ajax({

                url: '{{ route('account.updateProfile') }}',
                type: "post",
                data: $(this).serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response["status"] == false) {
                        var error = response['error']

                        if (error["name"]) {
                            $("#name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['name']);
                        } else {
                            $("#name").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                        if (error["email"]) {
                            $("#email").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['email']);
                        } else {
                            $("#email").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                        if (error["phone"]) {
                            $("#phone").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['phone']);
                        } else {
                            $("#phone").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                    } else {

                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        $("#email").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        $("#phone").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        window.location.href = "{{ route('account.profile') }}"
                    }



                }

            })
        })



        $("#addressForm").submit(function(e) {
            e.preventDefault();


            $.ajax({

                url: '{{ route('account.updateAddress') }}',
                type: "post",
                data: $(this).serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response["status"] == false) {
                        var error = response['error']

                        if (error["first_name"]) {
                            $("#first_name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['first_name']);
                        } else {
                            $("#first_name").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                        if (error["last_name"]) {
                            $("#last_name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['last_name']);
                        } else {
                            $("#last_name").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                        if (error["email"]) {
                            $("#addressForm  #email").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['email']);
                        } else {
                            $("#addressForm #email").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["mobile"]) {
                            $("#mobile").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['mobile']);
                        } else {
                            $("#mobile").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["country"]) {
                            $("#country").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['country']);
                        } else {
                            $("#country").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["address"]) {
                            $("#address").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['address']);
                        } else {
                            $("#address").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["city"]) {
                            $("#city").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['city']);
                        } else {
                            $("#city").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["state"]) {
                            $("#state").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['state']);
                        } else {
                            $("#state").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                        if (error["apartment"]) {
                            $("#apartment").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['apartment']);
                        } else {
                            $("#apartment").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }


                        if (error["zip"]) {
                            $("#zip").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(error['zip']);
                        } else {
                            $("#zip").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }

                    } else {

                        $("#first_name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        $("#last_name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#addressForm #email").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#mobile").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#country").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#address").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#city").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#state").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#apartment").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#zip").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        window.location.href = "{{ route('account.profile') }}"
                    }



                }

            })
        })
    </script>
@endsection
