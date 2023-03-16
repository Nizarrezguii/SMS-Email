<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clients</title>
    <!-- Tempusdominus Bootstrap 4 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 p-5 border-2 border border-secondary rounded">
                <h2>Client Data :</h2>
                <form action="{{ route('import-client')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Select file :</label>
                    <input type="file" name="file" class="form-control">
                    <div class="mt-5">
                        <button class="btn btn-success" type="submit">Submit</button>
                        <a href="{{ route('export-client')}}" class="btn btn-primary float-right">Export Excel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
