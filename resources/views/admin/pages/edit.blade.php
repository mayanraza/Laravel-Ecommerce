@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Pages</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('pages.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" name="pagesForm" id="pagesForm">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $pages->name }}">
                                    <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $pages->slug }}">
                                    <p></p>
                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description">Content</label>
                                    <textarea name="content" id="content" cols="30" rows="10" class="summernote" placeholder="content">{!! $pages->content !!}</textarea>
                                    <p class="error"></p>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
    <script>
        // form submission---
        $("#pagesForm").submit(function(event) {
            event.preventDefault();
            var element = $(this)
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('pages.update',$pages->id) }}',
                type: "put",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {


                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        window.location.href = "{{ route('pages.index') }}"

                    } else {
                        var errors = response['errors']
                        if (errors['name']) {
                            $("#name").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['name']);
                        } else {
                            $("#name").removeClass("is-invalid")
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
