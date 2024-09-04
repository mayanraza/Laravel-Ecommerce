<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">
    <h1>Yo have recieved a contact email</h1>

    <p>Name: {{ $emailData['name'] }}</p>
    <p>Email: {{ $emailData['email'] }}</p>
    <p>Subject: {{ $emailData['subject'] }}</p>
    <p>Message: {{ $emailData['message'] }}</p>



</body>

</html>
