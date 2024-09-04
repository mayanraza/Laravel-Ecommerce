@extends('front.Layouts.app')


@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                        <li class="breadcrumb-item">Reset Password</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-10">
            <div class="container">

                {{-- session----------------------------------- --}}
                @if (Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {!! Session::get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {!! Session::get('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                {{-- session----------------------------------- --}}



                <div class="login-form">
                    <form action="{{ route('front.processResetPassword') }}" method="post">
                        @csrf

                        <input type="hidden" name="token" value="{{$token}}">

                        <h4 class="modal-title">Reset Password</h4>


                        <div class="form-group">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                placeholder="New Password" name="new_password" >
                            @error('new_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                                placeholder="Confirm Password" name="confirm_password" >
                            @error('confirm_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                       


                        
                        <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">
                    </form>
                    <div class="text-center small">Click here to <a href="{{ route('account.login') }}">Login</a></div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('customJs')
@endsection
