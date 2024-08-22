@extends('front.Layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                {{-- Category listing--------------------- --}}
                                @if ($category->isNotEmpty())
                                    @foreach ($category as $key => $item)
                                        <div class="accordion-item">
                                            @if ($item->sub_Category->isNotEmpty())
                                                <h2 class="accordion-header" id="headingOne">
                                                    <a href="{{ route('front.shop', $item->slug) }}">
                                                        <button
                                                            class="accordion-button collapsed {{ $categorySelected == $item->id ? 'text-primary' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseOne-{{ $key }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapseOne-{{ $key }}">
                                                            {{ $item->name }}
                                                        </button>
                                                    </a>
                                                </h2>
                                            @else
                                                <a href="{{ route('front.shop', $item->slug) }}"
                                                    class="nav-item nav-link ">{{ $item->name }}</a>
                                            @endif

                                            <div id="collapseOne-{{ $key }}"
                                                class="accordion-collapse collapse {{ $categorySelected == $item->id ? 'show' : '' }}"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                style="">
                                                <div class="accordion-body">
                                                    <div class="navbar-nav">
                                                        @if ($item->sub_Category->isNotEmpty())
                                                            @foreach ($item->sub_Category as $subitem)
                                                                <a href="{{ route('front.shop', [$item->slug, $subitem->slug]) }}"
                                                                    class="nav-item nav-link {{ $subCategorySelected == $subitem->id ? 'text-primary' : '' }}">{{ $subitem->name }}</a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- Category listing--------------------- --}}

                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            {{-- Brand listing--------------------- --}}
                            @if ($brand->isNotEmpty())
                                @foreach ($brand as $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input brand-label" type="checkbox"
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, $brandArray) ? 'checked' : '' }} name="brand[]"
                                            id="brand-{{ $item->id }}">
                                        <label class="form-check-label" for="brand-{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Brand listing--------------------- --}}

                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <input type="text" class="js-range-slider" name="my_range" value="" />

                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">

                                    <select name="sort" id="sort" class="form-control">
                                        <option value="">Sorting</option>

                                        <option value="latest" {{ $priceSort == 'latest' ? 'selected' : '' }}>Price Latest
                                        </option>
                                        <option value="price_desc" {{ $priceSort == 'price_desc' ? 'selected' : '' }}>Price
                                            High</option>
                                        <option value="price_asc" {{ $priceSort == 'price_asc' ? 'selected' : '' }}>Price
                                            Low
                                        </option>

                                    </select>

                                </div>
                            </div>
                        </div>




                        {{-- Product listing--------------------- --}}
                        @if ($product->isNotEmpty())
                            @foreach ($product as $item)
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="{{ route('front.product', $item->slug) }}" class="product-img">
                                                @if (!empty($item->product_images->first()->image))
                                                    <img class="card-img-top"
                                                        src="{{ asset('uploads/product/small/' . $item->product_images->first()->image) }}"
                                                        alt="">
                                                @else
                                                    <img class="card-img-top" src="admin-assets/img/prod-1.jpg"
                                                        alt="">
                                                @endif

                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart({{$item->id}})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $item->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>${{ $item->price }}</strong></span>
                                                @if ($item->compare_price > 0)
                                                    <span
                                                        class="h6 text-underline"><del>${{ $item->compare_price }}</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        {{-- Product listing--------------------- --}}



                        <div class="col-md-12 pt-5">
                            {{ $product->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        // price range filter -----
        rangeSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: 1000,
            from: {{ $priceMin }},
            step: 10,
            to: {{ $priceMax }},
            skin: "round",
            max_postfix: "+",
            prefix: "$",
            onFinish: function() {
                apply_filter()
            },
        })
        // saving its instance to var
        var slider = $(".js-range-slider").data("ionRangeSlider");
        // price range filter -----








        // price sort filter -----
        $("#sort").change(function() {
            apply_filter()

        })
        // price sort filter -----









        // brand filter -----
        $(".brand-label").change(function() {
            apply_filter()
        });

        function apply_filter() {
            var brands = [];
            $(".brand-label").each(function() {
                if ($(this).is(":checked") == true) {
                    brands.push($(this).val())
                }
            })
            var url = '{{ url()->current() }}?';

            // brand filter----
            if (brands.length > 0) {
                url += '&brand=' + brands.toString()
            }
            // price range----
            url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to;


            // sorting filter----
            url += '&sort=' + $("#sort").val();


            window.location.href = url
        }
        // brand filter -----












        // add to cart---------------
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
        // add to cart---------------
    </script>
@endsection
