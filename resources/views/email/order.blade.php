<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">

    @if ($emailData['userType'] == 'customer')
        <h1>Thanks for your Order..!!</h1>
        <h2>Your Order Id is : #{{ $emailData['order']->id }}</h2>
    @else
        <h1>You have recieved an Order</h1>
        <h2>Order Id is : #{{ $emailData['order']->id }}</h2>
    @endif











    <h1 class="h5 mb-3">Shipping Address</h1>
    <address>
        <strong>{{ $emailData['order']->first_name . ' ' . $emailData['order']->last_name }}</strong><br>
        {{ $emailData['order']->address }}<br>
        {{ $emailData['order']->city }}, {{ $emailData['order']->zip }}, {{ $emailData['order']->country->name }}<br>
        Phone: {{ $emailData['order']->mobile }}<br>
        Email: {{ $emailData['order']->email }}
    </address>




    <h2>Product</h2>
    <table>
        <thead>
            <tr style="background: #ccc">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($emailData))
                @foreach ($emailData['order']->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>

                    </tr>
                @endforeach
            @endif

            <tr class="mt-4">
                <th colspan="3" align="right">Subtotal:</th>
                <td>${{ number_format($emailData['order']->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">
                    Discount:{{ !empty($emailData['order']->coupon_code) ? '(' . $emailData['order']->coupon_code . ')' : '' }}
                </th>
                <td>${{ number_format($emailData['order']->discount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>${{ number_format($emailData['order']->shipping, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>${{ number_format($emailData['order']->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
