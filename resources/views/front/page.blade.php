@extends('front.Layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">

                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item">{{ $page->name }}</li>
                    </ol>
                </div>
            </div>
        </section>



        @if ($page->slug == 'contact-us')
            <section class=" section-10">
                <div class="container">
                    <div class="section-title mt-5 ">
                        <h2>{{ $page->name }}</h2>
                    </div>
                </div>
            </section>


            <section>
                <div class="container">
                    <div class="row">


                        <div class="col-md-12">
                            @include('front.account.common.message')
                        </div>


                        <div class="col-md-6 mt-3 pe-lg-5">
                            {!! $page->content !!}
                        </div>

                        <div class="col-md-6">
                            <form class="shake" role="form" method="post" id="contactForm" name="contactForm">
                                <div class="mb-3">
                                    <label class="mb-2" for="name">Name</label>
                                    <input class="form-control" id="name" type="text" name="name"
                                        data-error="Please enter your name">
                                    <p></p>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2" for="email">Email</label>
                                    <input class="form-control" id="email" type="email" name="email"
                                        data-error="Please enter your Email">
                                    <p></p>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2">Subject</label>
                                    <input class="form-control" id="subject" type="text" name="subject"
                                        data-error="Please enter your message subject">
                                    <p></p>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="mb-2">Message</label>
                                    <textarea class="form-control" rows="3" id="message" name="message" data-error="Write your message"></textarea>
                                    <p></p>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-submit">
                                    <button class="btn btn-dark" type="submit" id="form-submit"><i
                                            class="material-icons mdi mdi-message-outline"></i> Send Message</button>
                                    <div id="msgSubmit" class="h3 text-center hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class=" section-10">
                <div class="container">
                    <h1 class="my-3">{{ $page->name }}</h1>
                    <p>{!! $page->content !!}</p>
                </div>
            </section>
        @endif



    </main>
@endsection

@section('customJs')
    <script>
        // form submission---
        $("#contactForm").submit(function(event) {
            event.preventDefault();
            var element = $("#contactForm");
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('front.contactform') }}',
                type: "post",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {


                        $("#name").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        $("#subject").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        $("#message").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");


                        window.location.href = "{{ route('front.page', $page->slug) }}"



                    } else {
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
                        if (errors['email']) {
                            $("#email").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['email']);
                        } else {
                            $("#email").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        // subject error message-----
                        if (errors['subject']) {
                            $("#subject").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['subject']);
                        } else {
                            $("#subject").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        // message error message-----
                        if (errors['message']) {
                            $("#message").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['message']);
                        } else {
                            $("#message").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        // category dropdown error message-----

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
