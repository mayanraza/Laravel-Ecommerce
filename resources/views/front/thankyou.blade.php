@extends('front.Layouts.app')

@section('content')
    <section class="container">
        <div class="col-md-12 text-center py-5">

           

            @if (Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible  show" role="alert">
                        {!! Session::get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif


            <h1>Thank You!</h1>
            <p>Your Order Id is: {{ $id }} </p>
        </div>
    </section>
@endsection





@section('customJs')
@endsection
