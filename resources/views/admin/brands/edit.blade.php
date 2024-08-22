@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>







    <section class="content">
        <!-- Default box -->
        <form action="" method="Put" name="brandForm" id="brandForm">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                          
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $brand->name }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $brand->slug }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $brand->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $brand->status == 0 ? 'selected' : '' }} value="0">
                                            Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Edit</button>
                    <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>

        </form>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
    <script>
        // form submission---
        $("#brandForm").submit(function(event) {
            event.preventDefault();
            var element = $("#brandForm");
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('brands.update', $brand->id) }}',
                type: "put",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {

                        window.location.href = "{{ route('brands.index') }}"

                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#slug").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                       
                    } else {

                        // if brand not found in database-------
                        if (response['notFound'] === true) {
                            window.location.href = "{{ route('subcategories.index') }}";
                            return false;
                        }
                        // if brand not found in database-------

                        var errors = response['errors']
                        // name error message-----
                        if (errors['name']) {
                            $("#name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['name']);
                        } else {
                            $("#name").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        // slug error message-----
                        if (errors['slug']) {
                            $("#slug").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['slug']);
                        } else {
                            $("#slug").removeClass("is-invalid")
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








        // slug -------------------
        $('#name').change(function() {
            var element = $(this)
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('getSlug') }}',
                type: "get",
                data: {
                    title: element.val()
                },
                dataType: "json",
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false)

                    if (response["status"] == true) {
                        $("#slug").val(response["slug"])
                    }
                }
            });
        })
        // slug -------------------
    </script>
@endsection
