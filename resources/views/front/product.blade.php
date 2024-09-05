@extends('front.Layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                        <li class="breadcrumb-item">{{ $product->title }}</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-7 pt-3 mb-3">
            @if (!empty($product))
                <div class="container">
                    <div class="row ">

                        <div class="col-md-12">
                            @include('front.account.common.message')
                        </div>

                        <div class="col-md-5">
                            <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner bg-light">

                                    @if (!empty($product->product_images))
                                        <div id="productCarousel" class="carousel slide">
                                            <div class="carousel-inner">
                                                @foreach ($product->product_images as $key => $item)
                                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                        <img class="d-block w-100"
                                                            src="{{ asset('uploads/product/large/' . $item->image) }}"
                                                            alt="Product Image">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                                </a>
                                <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="bg-light right">
                                <h1>{{ $product->title }}</h1>
                                <div class="d-flex mb-3">
                                    <div class="star-rating product mt-1" title="">
                                        <div class="back-stars">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>

                                            <div class="front-stars"
                                                style="width: {{ ($ratingAvg / 5) * 100 }}%">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="pt-1">({{ $product->product_ratings_count>1?$product->product_ratings_count." Reviews":$product->product_ratings_count." Review" }})</small>
                                </div>
                                @if ($product->compare_price > 0)
                                    <h2 class="price text-secondary"><del>${{ $product->compare_price }}</del></h2>
                                @endif

                                <h2 class="price ">${{ $product->price }}</h2>

                                <p>{!! $product->short_description !!}</p>


                                @if ($product->track_qty == 'Yes')
                                    @if ($product->qty > 0)
                                        <a class="btn btn-dark" href="javascript:void(0)"
                                            onclick="addToCart({{ $product->id }})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @else
                                        <a class="btn btn-dark" href="javascript:void(0)">
                                            <i class="fa fa-shopping-cart"></i> Out of Stock
                                        </a>
                                    @endif
                                @else
                                    <a class="btn btn-dark" href="javascript:void(0)"
                                        onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                @endif

                            </div>
                        </div>

                        <div class="col-md-12 mt-5">
                            <div class="bg-light">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                            data-bs-target="#description" type="button" role="tab"
                                            aria-controls="description" aria-selected="true">Description</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab"
                                            data-bs-target="#shipping" type="button" role="tab"
                                            aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                            data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews"
                                            aria-selected="false">Reviews</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                                        aria-labelledby="description-tab">
                                        <p>
                                            {!! $product->description !!}
                                        </p>
                                    </div>
                                    <div class="tab-pane fade" id="shipping" role="tabpanel"
                                        aria-labelledby="shipping-tab">
                                        <p> {!! $product->shipping_returns !!}</p>
                                    </div>





                                    {{-- reviews--------------- --}}
                                    <div class="tab-pane fade" id="reviews" role="tabpanel"
                                        aria-labelledby="reviews-tab">
                                        <form action="" name="ratingForm" id="ratingForm">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <h3 class="h4 pb-3">Write a Review</h3>
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="username">Name</label>
                                                        <input type="text" class="form-control" name="username"
                                                            id="username" placeholder="Name">
                                                        <p></p>
                                                    </div>
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="text" class="form-control" name="email"
                                                            id="email" placeholder="Email">
                                                        <p></p>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="rating">Rating</label>
                                                        <br>
                                                        <div id="rating" class="rating" style="width: 10rem">
                                                            <input id="rating-5" type="radio" name="rating"
                                                                value="5" /><label for="rating-5"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-4" type="radio" name="rating"
                                                                value="4" /><label for="rating-4"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-3" type="radio" name="rating"
                                                                value="3" /><label for="rating-3"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-2" type="radio" name="rating"
                                                                value="2" /><label for="rating-2"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                            <input id="rating-1" type="radio" name="rating"
                                                                value="1" /><label for="rating-1"><i
                                                                    class="fas fa-3x fa-star"></i></label>
                                                        </div>
                                                        <p></p>

                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="">How was your overall experience?</label>
                                                        <textarea name="comment" id="comment" class="form-control" cols="30" rows="10"
                                                            placeholder="How was your overall experience?"></textarea>
                                                        <p></p>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-dark">Submit</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                        <div class="col-md-12 mt-5">
                                            <div class="overall-rating mb-3">
                                                <div class="d-flex">
                                                    <h1 class="h3 pe-3">{{ $ratingAvg }}</h1>
                                                    <div class="star-rating mt-2" title="">
                                                        <div class="back-stars">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>

                                                            <div class="front-stars"
                                                                style="width: {{ ($ratingAvg / 5) * 100 }}%">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="pt-2 ps-2">
                                                        ({{ $product->product_ratings_count > 1 ? $product->product_ratings_count . ' Reviews' : $product->product_ratings_count . ' Review' }})
                                                    </div>
                                                </div>

                                            </div>

                                            @if ($product->product_ratings->isNotEmpty())
                                                @foreach ($product->product_ratings as $item)
                                                    @php
                                                        $ratingPerc = ($item->rating / 5) * 100;
                                                    @endphp


                                                    <div class="rating-group mb-4">
                                                        <span> <strong>{{ $item->username }} </strong></span>
                                                        <div class="star-rating mt-2" title="">
                                                            <div class="back-stars">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>

                                                                <div class="front-stars"
                                                                    style="width: {{ $ratingPerc }}%">
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="my-3">
                                                            <p>{{ $item->comment }} </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif



                                        </div>
                                        {{-- reviews--------------- --}}


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @endif

        </section>



        @if (!empty($relatedProduct))
            <section class="pt-5 section-8">
                <div class="container">
                    <div class="section-title">
                        <h2>Related Products</h2>
                    </div>

                    <div class="col-md-12">
                        <div id="related-products" class="carousel">



                            {{-- @dd($relatedProduct) --}}
                            @foreach ($relatedProduct as $item)
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('front.product', $item->slug) }}" class="product-img">
                                            @if ($item->product_images->isNotEmpty())
                                                <img class="card-img-top"
                                                    src="{{ asset('uploads/product/large/' . $item->product_images->first()->image) }}"
                                                    alt="">
                                            @endif

                                        </a>
                                        <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                        <div class="product-action">
                                            @if ($item->track_qty == 'Yes')
                                                @if ($item->qty > 0)
                                                    <a class="btn btn-dark" href="javascript:void(0)"
                                                        onclick="addToCart({{ $item->id }})">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0)">
                                                        <i class="fa fa-shopping-cart"></i> Out of Stock
                                                    </a>
                                                @endif
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0)"
                                                    onclick="addToCart({{ $item->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body text-center mt-3">
                                        <a class="h6 link" href="">{{ $item->title }}</a>
                                        <div class="price mt-2">
                                            <span class="h5"><strong>${{ $item->price }}</strong></span>
                                            @if ($item->compare_price > 0)
                                                <span
                                                    class="h6 text-underline"><del>${{ $item->compare_price }}</del></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </section>
        @endif

    </main>
@endsection





@section('customJs')
    <script type="text/javascript">
        // form submission---
        $("#ratingForm").submit(function(event) {
            event.preventDefault();
            var element = $(this)
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('front.productrating', $product->id) }}',
                type: "post",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false)


                    if (response["status"] === true) {


                        $("#username").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#email").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#comment").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");
                        $("#ratting").removeClass("is-invalid")
                            .siblings('p')
                            .removeClass("invalid-feedback").html("");

                        window.location.href = "{{ route('front.product', $product->slug) }}"

                    } else {
                        var errors = response['errors']
                        if (errors['username']) {
                            $("#username").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['name']);
                        } else {
                            $("#username").removeClass("is-invalid")
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
                        if (errors['comment']) {
                            $("#comment").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['comment']);
                        } else {
                            $("#comment").removeClass("is-invalid")
                                .siblings('p')
                                .removeClass("invalid-feedback").html("");
                        }
                        if (errors['rating']) {
                            $("#rating").addClass("is-invalid")
                                .siblings('p')
                                .addClass("invalid-feedback").html(errors['rating']);
                        } else {
                            $("#rating").removeClass("is-invalid")
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














        // add to cart----------
        function addToCart(id) {
            $.ajax({
                url: '{{ route('front.addToCart') }}',
                type: "post",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('front.cart') }}"
                    } else {
                        alert(response.message)
                    }
                }

            })
        }
        // add to cart----------
    </script>
@endsection
