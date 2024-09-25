@extends('layout')

@section('content')


<!--Add Employee Modal-->
<div class="modal fade" id="AddEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="AddEmployeeForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <ul class="alert alert-danger d-none" id="save_errorList"></ul>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Enter Your Name">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlImage1" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="exampleFormControlImage1">
                    </div>
                    
                </div>
                <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!--End Employee Modal-->

{{-- Edit Modal --}}

<div class="modal fade" id="EditEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="EditEmployeeForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <ul class="alert alert-danger d-none" id="update_errorList"></ul>
                    <input type="text" id="edit_employee_id" class="d-none">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="Enter Your Name">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="edit_email"  aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlImage1" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="edit_image" >
                    </div>
                    
                </div>
                <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
        </div>
    </div>
</div>


{{--End Edit Modal --}}

{{-- records table --}}

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Ajax Crud</h4>
                    <a href="#" class="btn btn-secondary float-end btn-sm" data-bs-toggle="modal" data-bs-target="#AddEmployeeModal">Add Employee</a>
                </div>
                <div class="card-body">
                    <div class="alert alert-success d-none" id="successMessage"></div>
                    
                    <div class="container mt-2 p-2">
                            <h1 class="bg-light text-center">Employee Record</h1>
                            <table class="table align-middle mb-0 bg-white">
                                <thead class="bg-light">
                                  <tr>
                                    <th>Images</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                             
                            
                            </table>
                    </div>
                    


                </div>
            </div>
        </div>
    </div>
</div>

{{-- records table end --}}


@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $(document).on('submit', '#AddEmployeeForm', function(e) {
        e.preventDefault();
    
        let formData = new FormData(this);
    
        // console.log([...formData]); 
    
            $.ajax({
                type: "POST",
                url: "/add", // Adjust this URL as needed
                data: formData,
                dataType: "json",
                contentType: false, // Important for FormData
                processData: false, // Important for FormData
                success: function(response) {
                    // console.log(response); 
                   if(response.status == 400)
                   {
                    $('#save_errorList').html("");
                    $('#save_errorList').removeClass("d-none");
                    $.each(response.errors, function(key, err_value) {
                        $('#save_errorList').append('<li>' + err_value + '</li>');
                    });
                   }
                    
                   else if(response.status == 200){
                   
                    $('#save_errorList').html("");
                    $('#save_errorList').addClass("d-none");
                    $('#AddEmployeeModal').modal('hide');  
                    $('#AddEmployeeForm')[0].reset();

                    // Append success message to successMessage div
                    $('#successMessage').removeClass('d-none'); // Show success message
                    $('#successMessage').html(''); // Clear previous messages
                    $('#successMessage').append('<li>' + response.message + '</li>'); // Append new message
                    console.log(response.message);
                   }
                },
                error: function(xhr) {
                    console.error(xhr); // Handle errors here
                }
            });
        });

            

        fetchEmployees();

        function fetchEmployees() {
            $.ajax({
                    type: "GET",
                    url: "/fetch_employees", // Adjust this URL as needed
                    dataType: "json",
                    success: function(response) {
                        console.log(response); 
                        // Clear previous entries
                        $('tbody').html('');
            
                        // Populate employee records here
                        $.each(response.employees, function(key, item) { 
                            $('tbody').append(
                                '<tr>' +
                                '<td><img class="rounded-circle"  src="/uploads/employeesImages/' + item.image + '" width="50" height="50" /></td>' +
                                    '<td>' + item.name + '</td>' +
                                    '<td>' + item.email + '</td>' +
                                    '<td><a href="#" class="edit-btn btn btn-sm btn-secondary" data-id="' + item.id + '">Edit</a></td>' +
                                    '<td><a href="#" class="delete-btn btn btn-sm btn-dark" data-id="' + item.id + '">Delete</a></td>' +
                                '</tr>'  // Adjust this row structure as needed
    
                        );   
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr); // Handle errors here
                        }
                    });
            }

        
    });
    
</script>

@endsection