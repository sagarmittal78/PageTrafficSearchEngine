<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Page Traffic Search Engine</title>
</head>

<body>
    <div class="container mt-5" style="border:1px solid black">


        <h1 style="justify-self: center;">Welcome!</h1>
        <h2 style="justify-self: center;">Page Traffic Search Engine</h2>
        <h6 style="justify-self: center;">Search your queries and export results here</h6>
        <br>
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>  <br>
        @endif
      
        <form action="{{ url('search') }}" method="POST">
            @csrf
            <div id="search_body">
                <div class="row" id="row_1">
                    <div class="col-9">
                        <div class="mb-3">
                            <label for="query" class="form-label">Search Query</label>
                            <input type="text" class="form-control" id="query" name="query[]" placeholder="Enter Search Query" required>
                        </div>
                    </div>
                    <div class="col-3 " style="align-self: end;">

                        <button type="button" onclick="addsearchrow();" class="btn btn-success mb-3">+ Add More
                            Query</button>

                    </div>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-9">

                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary mb-3"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg> Search</button>
                </div>
            </div>
        </form>
      
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var rowCount = 1;

        function addsearchrow() {
            rowCount++;
            const row = document.createElement('div');
            row.className = 'row';
            row.id = 'row_' + rowCount;

            row.innerHTML =
                '<div class="col-9"><div class="mb-3"><label for="query"  class="form-label">Search Query</label><input type="text" placeholder="Enter Search Query" class="form-control" id="query" name="query[]" required></div></div><div class="col-3 " style="align-self: end;"><button type="button" onclick="removeRow(' +
                rowCount + ');" class="btn btn-danger mb-3">- Remove Query</button></div>';
            document.getElementById('search_body').appendChild(row);
        }

        function removeRow(id) {
            const row = document.getElementById('row_' + id);
            if (row) {
                row.remove();
            }
        }
    </script>
</body>

</html>
