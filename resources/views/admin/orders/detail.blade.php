@extends('admin.Layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">

            @include('admin.message')

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Shipping Address</h1>
                                    <address>
                                        <strong>{{ $order->first_name . ' ' . $order->last_name }}</strong><br>
                                        {{ $order->address }}<br>
                                        {{ $order->city }}, {{ $order->zip }}, {{ $order->country->name }}<br>
                                        Phone: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>

                                    <strong>Shipped Date</strong><br>
                                    @if (!empty($order->shipped_date))
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif

                                </div>



                                <div class="col-sm-4 invoice-col">
                                    {{-- <b>Invoice ${{ $order->id }}</b><br> --}}
                                    <br>
                                    <b>Order ID:</b> {{ $order->id }}<br>
                                    <b>Total:</b> ${{ number_format($order->grand_total, 2) }}<br>
                                    <b>Status:</b>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-warning">Shipped</span>
                                    @elseif($order->status == 'deliver')
                                        <span class="badge bg-success">Delivered</span>
                                    @else
                                        <span class="badge bg-dark">Cancelled</span>
                                    @endif
                                    <br>

                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($orderItem))
                                        @foreach ($orderItem as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>${{ number_format($item->price, 2) }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>${{ number_format($item->total, 2) }}</td>

                                            </tr>
                                        @endforeach
                                    @endif

                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">
                                            Discount:{{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}
                                        </th>
                                        <td>${{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>${{ number_format($order->shipping, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>${{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" method="POST" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $order->status == 'pending' ? 'selected' : '' }} value="pending">Pending
                                        </option>
                                        <option {{ $order->status == 'shipped' ? 'selected' : '' }} value="shipped">Shipped
                                        </option>
                                        <option {{ $order->status == 'deliver' ? 'selected' : '' }} value="deliver">
                                            Delivered
                                        </option>
                                        <option {{ $order->status == 'cancelled' ? 'selected' : '' }} value="cancelled">
                                            Cancelled</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="">Shipped Date</label>
                                    <input placeholder="Shipped Date" type="text" name="shipped_date" id="shipped_date"
                                        class="form-control" value="{{ $order->shipped_date }}">
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST" id="sendInvoiceMail" name="sendInvoiceMail">
                                <h2 class="h4 mb-3">Send Inovice Email</h2>
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('customjs')
    <script>
        // order status change---------------
        $("#changeOrderStatusForm").submit(function(event) {
            event.preventDefault();
            if (confirm("Are you want to Update Shipping status and date..?")) {

                $.ajax({
                    url: '{{ route('orders.changeOrderStatus', $order->id) }}',
                    type: "post",
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('orders.details', $order->id) }}';
                    }
                })
            }

        })
        // order status change---------------











        // order  email sent---------------
        $("#sendInvoiceMail").submit(function(event) {
            event.preventDefault();
            if (confirm("Are you want to send email..?")) {
                $.ajax({
                    url: '{{ route('orders.sendMail', $order->id) }}',
                    type: "post",
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('orders.details', $order->id) }}';
                    }
                })
            }
        })
        // order  email sent---------------









        // daate time picker-------------
        $(document).ready(function() {
            $('#shipped_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });


        // daate time picker-------------
    </script>
@endsection
