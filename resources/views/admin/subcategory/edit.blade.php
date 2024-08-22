@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('subcategories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>







    <section class="content">
        <!-- Default box -->



        <form action="" method="POST" name="subcategoryForm" id="subcategoryForm">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $item)
                                                <option {{ $subcategory->category_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $subcategory->name }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text"  name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $subcategory->slug }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $subcategory->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $subcategory->status == 0 ? 'selected' : '' }} value="0">
                                            Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Show on Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option {{ $subcategory->showHome == 'Yes' ? 'selected' : '' }} value="Yes">
                                            Yes
                                        </option>
                                        <option {{ $subcategory->showHome == 'No' ? 'selected' : '' }} value="No">
                                            No</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Edit</button>
                    <a href="{{ route('subcategories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>

        </form>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
    <script>
        // form submission---
        $("#subcategoryForm").submit(function(event) {
            event.preventDefault();
            var element = $("#subcategoryForm");
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('subcategories.update', $subcategory->id) }}',
                type: "put",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {

                        window.location.href = "{{ route('subcategories.index') }}"

                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#slug").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#category").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                    } else {

                        // if sub category not found in database-------
                        if (response['notFound'] === true) {
                            window.location.href = "{{ route('subcategories.index') }}";
                            return false;
                        }
                        // if sub category not found in database-------

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
                        // category dropdown error message-----
                        if (errors['category']) {
                            $("#category").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['category']);
                        } else {
                            $("#category").removeClass("is-invalid")
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
