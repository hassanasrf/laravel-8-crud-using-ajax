<!DOCTYPE html>
<html>
<head>
  <title>Laravel</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
  <div class="card text-center">
    <div class="card-header">
      Laravel 8 Crud with Ajax
    </div>
  </div>

  @yield('content')  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  
  <script type="text/javascript">
    $(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('books.index') }}",
        columns: [
        {data: 'id', name: 'id'},
        {data: 'title', name: 'title'},
        {data: 'author', name: 'author'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });
      $('#createNewBook').click(function () {
        $('#saveBtn').val("create-book");
        $('#book_id').val('');
        $('#bookForm').trigger("reset");
        $('#modelHeading').html("Create New Book");
        $('#ajaxModel').modal('show');
      });
      $('body').on('click', '.editBook', function () {
        var book_id = $(this).data('id');
        $.get("{{ route('books.index') }}" +'/' + book_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Book");
          $('#saveBtn').val("edit-book");
          $('#ajaxModel').modal('show');
          $('#book_id').val(data.id);
          $('#title').val(data.title);
          $('#author').val(data.author);
        })
      });
      $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');

        $.ajax({
          data: $('#bookForm').serialize(),
          url: "{{ route('books.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {

            $('#bookForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

          },
          error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
          }
        });
      });

      $('body').on('click', '.deleteBook', function () {

        var book_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
          type: "DELETE",
          url: "{{ route('books.store') }}"+'/'+book_id,
          success: function (data) {
            table.draw();
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      });

    });
  </script>
</body>
</html>