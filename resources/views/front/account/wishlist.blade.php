@extends('front.Layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                        <li class="breadcrumb-item">Settings</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container  mt-5">

                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>



                <div class="row">
                    <div class="col-md-3">
                        @include('front.account.common.sidebar')
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Wishlist</h2>
                            </div>
                            <div class="card-body p-4">


                                @if ($wishlist->isNotEmpty())
                                    @foreach ($wishlist as $item)
                                        <div
                                            class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                            <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                                <a class="d-block flex-shrink-0 mx-auto me-sm-4"
                                                    href="{{ route('front.product', $item->product->slug) }}"
                                                    style="width: 10rem;">

                                                    @if (!empty(getProductImage($item->product_id)))
                                                        <img src="{{ asset('uploads/product/small/' . getProductImage($item->product_id)->image) }}"
                                                            alt="Product">
                                                    @else
                                                        <img class="card-img-top"
                                                            src="{{ asset('admin-assets/img/prod-1.jpg') }}"
                                                            alt="No image available">
                                                    @endif



                                                </a>
                                                <div class="pt-2">
                                                    <h3 class="product-title fs-base mb-2"><a
                                                            href="{{ route('front.product', $item->product->slug) }}">{{ $item->product->title }}</a>
                                                    </h3>
                                                    <div class="fs-lg text-accent pt-2">
                                                        <span
                                                            class="h5"><strong>${{ $item->product->price }}</strong></span>
                                                        @if ($item->product->compare_price > 0)
                                                            <span
                                                                class="h6 text-underline"><del>${{ $item->product->compare_price }}</del></span>
                                                        @endif



                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                                <button onclick="removeProduct({{ $item->product_id }})"
                                                    class="btn btn-outline-danger btn-sm" type="button"><i
                                                        class="fas fa-trash-alt me-2"></i>Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                    <div>
                                        <h5 class="text-center">Your wishlist is Empty..!!</h5>
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection



@section('customJs')
    <script>
        function removeProduct(id) {
            $.ajax({
                url: '{{ route('account.productRemoveFromWishlist') }}',
                type: "post",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('account.wishlist') }}"
                    } else {
                        // window.location.href = "{{ route('account.login') }}"

                        // alert(response.message)
                    }
                }

            })
        }
    </script>
@endsection
