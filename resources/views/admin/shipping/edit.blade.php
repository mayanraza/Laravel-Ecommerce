@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="POST" id="shippingForm" name="shippingForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select class="form-control" id="country" name="country">
                                        <option value="">Select a Country</option>
                                        @if (!empty($countries))
                                            @foreach ($countries as $item)
                                                <option {{ $shipping->country_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            <option {{ $shipping->country_id == 'rest_of_world' ? 'selected' : '' }}
                                                value="rest_of_world">Rest of the world</option>
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>




                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="Amount" value="{{ $shipping->amount }}">
                                    <p></p>
                                </div>
                            </div>





                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>















        </div>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
<script>
    // form submission---
    $("#shippingForm").submit(function(event) {
        event.preventDefault();
        var element = $(this)
        $("button[type=submit]").prop('disabled', true)

        $.ajax({
            url: '{{ route('shipping.update',$shipping->id) }}',
            type: "put",
            data: element.serializeArray(),
            dataType: "json",
            success: function(response) {

                $("button[type=submit]").prop('disabled', false)


                if (response["status"] === true) {

                    window.location.href = "{{ route('shipping.create') }}"


                } else {
                    var errors = response['errors']
                    if (errors['country']) {
                        $("#country").addClass("is-invalid")
                            .siblings('p')
                            .addClass("invalid-feedback").html(errors['country']);
                    } else {
                        $("#country").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                    }
                    if (errors['amount']) {
                        $("#amount").addClass("is-invalid")
                            .siblings('p')
                            .addClass("invalid-feedback").html(errors['amount']);
                    } else {
                        $("#amount").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                    }
                }

            },
            error: function(jqXHR, exception) {
                console.log("something went wrong")
            }
        })
    });
    // form submission---
</script>
@endsection
