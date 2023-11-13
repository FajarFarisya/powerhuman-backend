<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Company</title>
</head>
<body>
    @foreach ($users as $user)
        <p>Name: {{$user->name}}</p>
    @endforeach
    <a type="button" href="/adduser">TAMBAH DATA</a>
</body>
</html>