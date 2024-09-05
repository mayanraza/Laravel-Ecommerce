@extends('admin.layouts.app')



@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ratings</h1>
                </div>
               
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>




    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('products.productRatings') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" value="{{ Request::get('list_search') }}" name="list_search"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Product</th>
                                <th>Rating</th>
                                <th>Reviews</th>
                                <th>Name</th>
                                <th width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($ratings->isNotEmpty())
                                @foreach ($ratings as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="#">{{ $item->title }}</a></td>
                                        <td>{{ number_format($item->rating, 2) }}</td>
                                        <td>{{ $item->comment }}</td>
                                        <td>{{ $item->username }}</td>


                                        <td>
                                            @if ($item->status == 1)
                                                <a href="javascript:void(0)" onclick="changeStatus(0,{{ $item->id }})">
                                                    <svg class="text-success-500 h-6 w-6 text-success"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" onclick="changeStatus(1,{{ $item->id }})">
                                                    <svg class="text-danger-500 h-6 w-6 text-danger"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Record not Found...</td>
                                </tr>
                            @endif


                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $ratings->links() }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('customjs')
    <script>
        function changeStatus(status, productId) {
            if (confirm("Are you sure  you want to change status..??")) {
                $.ajax({
                    url: '{{ route('products.changeRatingStatus') }}',
                    type: "post",
                    data: {
                        status: status,
                        productId: productId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            window.location.href = "{{ route('products.productRatings') }}";
                        }

                    }
                })
            }

        }
    </script>
@endsection
