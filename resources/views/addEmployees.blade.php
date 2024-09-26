@extends('layout')

@section('content')

<!-- Add Employee Modal -->
<div class="modal fade" id="AddEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="AddEmployeeForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <ul class="alert alert-danger d-none" id="save_errorList"></ul>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Enter Your Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlImage1" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="exampleFormControlImage1" required>
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
<!-- End Employee Modal -->

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
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="Enter Your Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="edit_email" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="edit_image">
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
{{-- End Edit Modal --}}

{{-- Records Table --}}
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

                        <div class="mb-3">
                            <input type="text" id="search" placeholder="Search by name or email" class="form-control d-inline-block" style="width: auto; display: inline;">
                            <button id="search-btn" class="btn btn-dark btn-md">Search</button>
                        </div>
                        <table class="table align-middle mb-0 bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th>Images</th>
                                    <th>
                                        Name 
                                        <i class="fas fa-sort-up sort-icon" data-sort-by="name" data-sort-order="asc" style="cursor: pointer;"></i>
                                        <i class="fas fa-sort-down sort-icon" data-sort-by="name" data-sort-order="desc" style="cursor: pointer;"></i>
                                    </th>
                                    <th>
                                        Email 
                                        <i class="fas fa-sort-up sort-icon" data-sort-by="email" data-sort-order="asc" style="cursor: pointer;"></i>
                                        <i class="fas fa-sort-down sort-icon" data-sort-by="email" data-sort-order="desc" style="cursor: pointer;"></i>
                                    </th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            

                            <tbody>
                                <!-- Employee records will be populated here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Records Table End --}}

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add Employee
        $(document).on('submit', '#AddEmployeeForm', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "/add", // Adjust this URL as needed
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 400) {
                        $('#save_errorList').html("").removeClass("d-none");
                        $.each(response.errors, function(key, err_value) {
                            $('#save_errorList').append('<li>' + err_value + '</li>');
                        });
                    } else if (response.status == 200) {
                        $('#save_errorList').html("").addClass("d-none");
                        $('#AddEmployeeModal').modal('hide');  
                        $('#AddEmployeeForm')[0].reset();
                        $('#successMessage').removeClass('d-none').html('<li>' + response.message + '</li>');
                        fetchEmployees(); // Refresh the employee list
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });



        // Fetch Employees
        function fetchEmployees(page = 1, search = '', sort_by = 'id', sort_order = 'asc') {
            $.ajax({
                type: "GET",
                url: "/fetch_employees?page=" + page + "&search=" + search + "&sort_by=" + sort_by + "&sort_order=" + sort_order,
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('tbody').html('');
                        $.each(response.employees.data, function(key, item) {
                            $('tbody').append(
                                '<tr>' +
                                '<td><img class="rounded-circle" src="/uploads/employeesImages/' + item.image + '" width="50" height="50" /></td>' +
                                '<td>' + item.name + '</td>' +
                                '<td>' + item.email + '</td>' +
                                '<td><a href="#" class="edit-btn btn btn-sm btn-secondary" data-id="' + item.id + '">Edit</a></td>' +
                                '<td><a href="#" class="delete-btn btn btn-sm btn-dark" data-id="' + item.id + '">Delete</a></td>' +
                                '</tr>'
                            );
                        });
        
                        // Handle pagination
                        let pagination = '<nav aria-label="Page navigation"><ul class="pagination">';
        
                        if (response.employees.current_page > 1) {
                            pagination += `<li class="page-item"><button class="page-btn btn btn-link" data-page="${response.employees.current_page - 1}">Previous</button></li>`;
                        }
        
                        for (let i = 1; i <= response.employees.last_page; i++) {
                            pagination += `<li class="page-item ${response.employees.current_page === i ? 'active' : ''}"><button class="page-btn btn btn-link" data-page="${i}">${i}</button></li>`;
                        }
        
                        if (response.employees.current_page < response.employees.last_page) {
                            pagination += `<li class="page-item"><button class="page-btn btn btn-link" data-page="${response.employees.current_page + 1}">Next</button></li>`;
                        }
        
                        pagination += '</ul></nav>';
                        $('#pagination').html(pagination);
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        }


        $(document).on('click', '#search-btn', function() {
            const search = $('#search').val();
            fetchEmployees(1, search);
        });

        $(document).on('click', '.sort-icon', function() {
            const sort_by = $(this).data('sort-by');
            const sort_order = $(this).data('sort-order');
            fetchEmployees(1, $('#search').val(), sort_by, sort_order);
        });

        $(document).on('click', '.page-btn', function() {
            const page = $(this).data('page');
            fetchEmployees(page, $('#search').val());
        });

        fetchEmployees(); // Initial fetch

        // Edit Employee
        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            let employeeId = $(this).data('id');
            $('#EditEmployeeModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit-employee/" + employeeId,
                success: function(response) {
                    if (response.status == 404) {
                        $('#EditEmployeeModal').modal('hide');
                        alert(response.message);
                    } else if (response.status == 200) {
                        $('#edit_name').val(response.employee.name);
                        $('#edit_email').val(response.employee.email);
                        $('#edit_employee_id').val(employeeId);
                    }
                }
            });
        });

        // Update Employee
        $(document).on('submit', '#EditEmployeeForm', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let employeeId = $('#edit_employee_id').val();

            $.ajax({
                type: "POST",
                url: "/update-employee/" + employeeId, // Adjust this URL as needed
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 400) {
                        $('#update_errorList').html("").removeClass("d-none");
                        $.each(response.errors, function(key, err_value) {
                            $('#update_errorList').append('<li>' + err_value + '</li>');
                        });
                    } else if (response.status == 200) {
                        $('#update_errorList').html("").addClass("d-none");
                        $('#EditEmployeeModal').modal('hide');
                        $('#EditEmployeeForm')[0].reset();
                        $('#successMessage').removeClass('d-none').html('<li>' + response.message + '</li>');
                        fetchEmployees(); // Refresh the employee list
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });

        // Delete Employee (optional)
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let employeeId = $(this).data('id');

            if (confirm('Are you sure you want to delete this employee?')) {
                $.ajax({
                    type: "DELETE",
                    url: "/delete-employee/" + employeeId, // Adjust this URL as needed
                    success: function(response) {
                        $('#successMessage').removeClass('d-none').html('<li>' + response.message + '</li>');
                        fetchEmployees(); // Refresh the employee list
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            }
        });

    });
</script>

@endsection
