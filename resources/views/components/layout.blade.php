<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <header>
        <div class="Header py-3 container-fluid">
            <nav class="row justify-content-center">
                <a class="col-auto " href="{{route('uploadImage')}}">Upload Image</a>
                <a class="col-auto mx-2" href="{{route('getImage')}}">Get Image</a>
                <a class="col-auto" href="{{route('allKeys')}}">All Keys</a>
                <a class="col-auto mx-2" href="{{route('configration')}}">Configration</a>
                <a class="col-auto" href="{{route('statistics')}}">Statistics</a>
            </nav>
        </div>
    </header>
    <main>
        {{$slot}}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="build/assets/app.4ccba2af.js"></script>

</body>
</html>
