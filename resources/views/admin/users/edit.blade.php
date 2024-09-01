@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" name="usersForm" id="usersForm">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $users->name }}">
                                    <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        placeholder="Email" value="{{ $users->email }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter new password">
                                    <small class="form-text text-muted">Leave blank if you do not want to change the
                                        password.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Phone"value="{{ $users->phone }}">
                                    <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $users->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ $users->status == '0' ? 'selected' : '' }} value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="phone">Address</label>
                                    <textarea name="address" id="address" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection



@section('customjs')
    <script>
        // form submission---
        $("#usersForm").submit(function(event) {
            event.preventDefault();
            var element = $(this)
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('users.update',$users->id) }}',
                type: "put",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {


                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        $("#email").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#phone").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        window.location.href = "{{ route('users.index') }}"

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
                        if (errors['email']) {
                            $("#email").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['email']);
                        } else {
                            $("#email").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['phone']) {
                            $("#phone").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['phone']);
                        } else {
                            $("#phone").removeClass("is-invalid")
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
