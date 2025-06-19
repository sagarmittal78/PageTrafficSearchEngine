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


        <h1>Search Results!</h1>
           <br>
        <div style="m-5">
         <div class="row">
            <div class="col-6" style="width: auto;">
  <a target="_blank" href="{{url('/export_all_csv')}}" style="width:auto;" class="btn btn-warning">Export All Data In CSV</a>
           
            </div>
            <div class="col-6">
<a  href="{{url('/')}}" style="width:auto;" class="btn btn-primary">Search Again</a>

            </div>
         </div>
            
                     
           
        </div>

        <br>
        <div class="accordion" id="accordionExample">
            @php
                $count = 1;
            @endphp
            @foreach ($allResults as $result)
                <div class="accordion-item">

                    <h2 class="accordion-header" id="heading{{ $count }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $count }}" aria-expanded="false"
                            aria-controls="collapse{{ $count }}">
                            <strong>#Query {{ $count }}</strong> &nbsp; - {{ $result['query'] }} &nbsp; &nbsp;
                            @if ($result['status'] == 'success')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                    class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red"
                                    class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            @endif



                        </button>
                        <span style="font-size: 12px;
    margin-inline: 20px;">{{ $result['no_of_result'] }} Results
                            Found!</span>

                    </h2>
                    <div id="collapse{{ $count }}" class="accordion-collapse collapse "
                        aria-labelledby="heading{{ $count }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            @if ($result['status'] == 'success')
                            <a target="_blank" href="{{url('/export_csv/'.$result['uid'])}}" class="btn btn-warning">Export CSV</a>
                            <div style="overflow-x: auto; width: 100%;">
                            <table class="table table-responsive  table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>URL</th>
                                            <th>Domain</th>
                                            <th>Snippet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($result['results'] as $data)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $data['title'] }}</td>
                                                <td><a href="{{ $data['link'] }}"
                                                        target="_blank">{{ $data['link'] }}</a></td>
                                                <td>{{ $data['domain'] }}</td>
                                                <td>{{ $data['snippet'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>  
                            @else
                                {{ $result['message'] }}
                            @endif
                        </div>
                    </div>
                </div>
                <br><br>
                @php
                    $count++;
                @endphp
            @endforeach
        </div>
        <br><br>
    </div>

    {{-- {{ json_encode($allResults) }} --}}
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
                '<div class="col-9"><div class="mb-3"><label for="query" class="form-label">Search Query</label><input type="text" class="form-control" id="query" name="query[]" required></div></div><div class="col-3 " style="align-self: end;"><button type="button" onclick="removeRow(' +
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
