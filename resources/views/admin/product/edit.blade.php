@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>






    <section class="content">
        <!-- Default box -->
        <form action="" method="POST" id="productForm" name="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title" value="{{ $product->title }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                            <p class="error"></p>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{ $product->description }}</textarea>
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote"
                                                placeholder="short_description">{{ $product->short_description }}</textarea>
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Shipping and Returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote"
                                                placeholder="shipping_returns">{{ $product->shipping_returns }}</textarea>
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                    {{-- <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="related_products">Related Products</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{ $product->description }}</textarea>
                                            <p class="error"></p>

                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="product-gallery">
                            @if ($productimage->isNotEmpty())
                                @foreach ($productimage as $item)
                                    <div class="col-md-3" id="image-row-{{ $item->id }}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{ $item->id }}">
                                            <img src="{{ asset('uploads/product/large/' . $item->image) }}"
                                                class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onclick="deleteImage({{ $item->id }})"
                                                    class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price" value="{{ $product->price }}">
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price"
                                                value="{{ $product->compare_price }}">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku" value="{{ $product->sku }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode" value="{{ $product->barcode }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes"
                                                    {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>

                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                            <p class="error"></p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Block
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">select category</option>
                                        @if ($category->isNotEmpty())
                                            @foreach ($category as $item)
                                                <option {{ $product->category_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>

                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">select sub category</option>
                                        @if ($subcategory->isNotEmpty())
                                            @foreach ($subcategory as $item)
                                                <option {{ $product->sub_category_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">

                                        @if ($brand->isNotEmpty())
                                            @foreach ($brand as $item)
                                                <option {{ $product->brand_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>





                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option {{ $product->is_featured == 'No' ? 'selected' : '' }} value="No">No
                                        </option>
                                        <option {{ $product->is_featured == 'Yes' ? 'selected' : '' }} value="Yes">Yes
                                        </option>
                                    </select>
                                    <p class="error"></p>

                                </div>
                            </div>
                        </div>


                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related Product</h2>
                                <div class="mb-3">
                                    <select multiple class="related_products w-100" name="related_products[]"
                                        id="related_products">
                                        @if (!empty($related_product))
                                            @foreach ($related_product as $item)
                                                <option selected value="{{$item->id}}">{{$item->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>

                                </div>
                            </div>
                        </div>







                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>


@endsection




@section('customjs')
    <script>
        // form submission---
        $("#productForm").submit(function(event) {
            event.preventDefault();
            var element = $("#productForm");
            $("button[type=submit]").prop('disabled', true)

            $.ajax({
                url: '{{ route('products.update', $product->id) }}',
                type: "put",
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response['status'] == true) {
                        $("#error").removeClass("invalid-feedback").html("")
                        $("input[type=text],select,input[type=number]").removeClass("is-invalid")

                        window.location.href = "{{ route('products.index') }}"
                    } else {
                        var errors = response["errors"];
                        // if (errors['title']) {
                        //     $("#title")
                        //         .addClass('is-invalid')
                        //         .siblings('p')
                        //         .addClass('invalid-feedback')
                        //         .html(errors['title']);
                        // } else {
                        //     $("#title")
                        //         .removeClass('is-invalid')
                        //         .siblings('p')
                        //         .removeClass('invalid-feedback')
                        //         .html("");
                        // }
                        $("#error").removeClass("invalid-feedback").html("")
                        $("input[type=text],select,input[type=number]").removeClass("is-invalid")

                        $.each(errors, function(key, value) {
                            $(`#${key}`)
                                .addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);

                        })
                    }


                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong")
                }
            })
        });
        // form submission---



        // select to----
        $('.related_products').select2({
            ajax: {
                url: '{{ route('products.getProducts') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function(data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });
        // select to----







        // category-subcategory dropdown functionality--------
        $('#category').change(function() {
            var category_id = $(this).val()
            $.ajax({
                url: '{{ route('products-subcategories.index') }}',
                type: "get",
                data: {
                    category_id: category_id
                },
                dataType: "json",
                success: function(response) {
                    // console.log(response)
                    // dropdown k 1st ko option select nhe krna---
                    $('#sub_category').find("option").not(":first").remove();

                    $.each(response["subcategories"], function(key, item) {
                        $('#sub_category').append(
                            `<option value='${item.id}'>${item.name}</option>`);
                    });
                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong")
                }
            })
        })
        // category-subcategory functionality--------







        // slug -------------------
        $('#title').change(function() {
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








        // Drop Zone-----------------
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url: "{{ route('products-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            params: {
                "product_id": '{{ $product->id }}'
            },
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                var html = `<div class="col-md-3" id="image-row-${response.image_id}">
            <div class="card">
                <input type="hidden" name="image_array[]" value="${response.image_id}">
                <img src="${response.ImagePath}" class="card-img-top" alt="...">
                <div class="card-body">
                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Remove</a>
                </div>
            </div>
        </div>`;
                $('#product-gallery').append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        // delete image-------
        function deleteImage(id) {
            $("#image-row-" + id).remove();
            if (confirm("are you sure want to delete this image..??")) {
                $.ajax({
                    url: '{{ route('products-images.delete') }}',
                    type: 'delete',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == true) {
                            alert(response.message)
                        } else {
                            alert(response.message)
                        }
                    }
                })
            }

        }
        // delete image-------
    </script>
@endsection