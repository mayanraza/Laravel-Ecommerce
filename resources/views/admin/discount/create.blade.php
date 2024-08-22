@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Discount</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('discount.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="discountForm" name="discountForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Code</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Coupon Code">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Coupon code name">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Max Uses</label>
                                    <input type="number" name="max_uses" id="max_uses" class="form-control"
                                        placeholder="Max Uses">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Max Uses User</label>
                                    <input type="number" name="max_uses_user" id="max_uses_user" class="form-control"
                                        placeholder="Max Uses User">
                                    <p></p>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">Type</label>
                                    <select name="type" id="showHome" class="form-control">
                                        <option value="percent">Percent</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Discount Amount</label>
                                    <input type="number" name="discount_amount" id="discount_amount" class="form-control"
                                        placeholder="Discount Amount">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Min Amount</label>
                                    <input type="number" name="min_amount" id="min_amount" class="form-control"
                                        placeholder="Min Amount">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">Status</label>
                                    <select name="status" id="showHome" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Starts At</label>
                                    <input autocomplete="off" type="text" name="starts_at" id="starts_at" class="form-control"
                                        placeholder="Starts At">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Expires At</label>
                                    <input autocomplete="off" type="text" name="expires_at" id="expires_at" class="form-control"
                                        placeholder="Expires At">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="slug">Description</label>
                                <textarea type="text" rows="5" class="form-control" name="description" id="description" class="form-control"
                                    placeholder="Description"></textarea>
                                <p></p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('discount.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
    <script>
        // form submission---
        $("#discountForm").submit(function(event) {
            event.preventDefault();
            var element = $(this)
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('discount.store') }}',
                type: "post",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {

                        window.location.href = "{{ route('discount.index') }}"

                        $("#code").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#description").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#max_uses").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#max_uses_user").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#type").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#discount_amount").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#min_amount").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#starts_at").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#expires_at").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                    } else {
                        var errors = response['errors']
                        if (errors['code']) {
                            $("#code").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['code']);
                        } else {
                            $("#code").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['name']) {
                            $("#name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['name']);
                        } else {
                            $("#name").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['description']) {
                            $("#description").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['description']);
                        } else {
                            $("#description").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['max_uses']) {
                            $("#max_uses").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['max_uses']);
                        } else {
                            $("#max_uses").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['max_uses_user']) {
                            $("#max_uses_user").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['max_uses_user']);
                        } else {
                            $("#max_uses_user").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['type']) {
                            $("#type").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['type']);
                        } else {
                            $("#type").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['discount_amount']) {
                            $("#discount_amount").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['discount_amount']);
                        } else {
                            $("#discount_amount").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['min_amount']) {
                            $("#min_amount").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['min_amount']);
                        } else {
                            $("#min_amount").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['starts_at']) {
                            $("#starts_at").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['starts_at']);
                        } else {
                            $("#starts_at").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['expires_at']) {
                            $("#expires_at").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['expires_at']);
                        } else {
                            $("#expires_at").removeClass("is-invalid")
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













        // daate time picker-------------
        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

        $('#expires_at').datetimepicker({
            // options here
            format: 'Y-m-d H:i:s',
        });
        // daate time picker-------------
    </script>
@endsection
