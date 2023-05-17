<!DOCTYPE html>
<html lang="en">
<?php
include_once("../include/config.php");
include_once("../include/connect.php");
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sysname ?></title>

    <script src="../request/source/jquery-3.6.4.js"></script>
    <script src="../request/source/sweetalert/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="../request/source/datatables/node_modules/datatables.net/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="../request/source/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../request/source/sweetalert/node_modules/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="../request/source/datatables/node_modules/datatables.net-dt/css/jquery.dataTables.css">

</head>

<body>
    <div class="p-5">
        <div class="bg-info p-3">
            <div class="row">
                <div class="text-center p-3 col-lg-8">
                    <h2>STUDENT INFORMATION TABLE</h2>
                </div>
                <div class="p-3 col-lg-4  d-flex justify-content-end">
                    <button class=" btn w-50 btn-success btn-lg" onclick="addNewRecord()">ADD</button>
                </div>
            </div>
            <table id="myTable" class="table table-striped table-responsive text-start align-middle table-light">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Image</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Image</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <script>
        function addNewRecord() {
            Swal.fire({
                title: "Add New Student",
                html: `
                        <form id="add_form" class="mt-1">
                            <input type="hidden" name="add_new_record" value="1"/> 
                            
                            <div class="form-floating mb-3 row w-100">
                                <div class="col-sm-8 d-flex align-items-end">
                                    <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
                                </div>
                                <div class="col-sm-4"><img id="profile" src="../request/source/images/user.png" width="64px" height="64px"></div>
                            </div>
      
                            <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Enter Username" required>
                            <label for="floatingUsername">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Enter Email" required>
                            <label for="floatingEmail">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="text" name="firstName" class="form-control" id="floatingFirstName" placeholder="Enter First Name" required>
                            <label for="floatingFirstName">First Name</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="text" name="middleName" class="form-control" id="floatingMiddleName" placeholder="Enter Middle Name">
                            <label for="floatingMiddleName">Middle Name</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="text" name="lastName" class="form-control" id="floatingLastName" placeholder="Enter Last Name" required>
                            <label for="floatingLastName">Last Name</label>
                            </div>
                            <div class="form-floating mb-3">
                            <input type="date" name="dateOfBirth" class="form-control" id="floatingDateOfBirth" required>
                            <label for="floatingDateOfBirth">Date of Birth</label>
                            </div>
                        </form>
                    `,
                showCancelButton: true,
                confirmButtonText: "Save",
                cancelButtonText: "Cancel",
                focusConfirm: true,
                allowOutsideClick: false,
                didOpen: () => {
                    const imageInput = $("#imageInput");
                    imageInput.on("change", function() {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            $("#profile").attr("src", e.target.result);
                            console.log(e.target.result)
                        };
                        reader.readAsDataURL(this.files[0]);
                    });
                },
                preConfirm: () => {
                    $.ajax({
                        url: "../request/server/record_action.php",
                        method: "POST",
                        data: $("#add_form").serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.status == "success") {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'User Added',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                $('#myTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Add User Failed',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Error',
                                text: `An error occurred: ${status} - ${error}`,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });
        }

        function editRow(id) {
            image_flag = false;
            $.ajax({
                type: "POST",
                url: "../request/server/record.php",
                data: {
                    user_id: id
                },
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        title: "Edit User Information",
                        html: `
                                <form id="edit_form" class="mt-5">
                                    <input type="hidden" name="edit_id" value="${id}"/>
                                    <div class="form-floating mb-3 row w-100">
                                        <div class="col-sm-4"><img id="profile" src="../request/source/images/user.png" width="64px" height="64px"></div>
                                        <div class="col-sm-8">
                                            <label for="imageInput" class="form-label">Upload new picture</label>
                                            <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
                                        </div>
                                    </div>
                            
                                    <div class="form-floating mb-3">
                                        <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Enter Username" value="${response.username}" required>
                                        <label for="floatingUsername">Username</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Enter Email" value="${response.email}" required>
                                        <label for="floatingEmail">Email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="firstName" class="form-control" id="floatingFirstName" placeholder="Enter First Name" value="${response.first_name}" required>
                                        <label for="floatingFirstName">First Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="middleName" class="form-control" id="floatingMiddleName" placeholder="Enter Middle Name" value="${response.middle_name}">
                                        <label for="floatingMiddleName">Middle Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="lastName" class="form-control" id="floatingLastName" placeholder="Enter Last Name" value="${response.last_name}" required>
                                        <label for="floatingLastName">Last Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="date" name="dateOfBirth" class="form-control" id="floatingDateOfBirth" value="${response.date_of_birth}" required>
                                        <label for="floatingDateOfBirth">Date of Birth</label>
                                    </div>
                                    </form>
                            `,
                        showCancelButton: true,
                        confirmButtonText: "Save Changes",
                        cancelButtonText: "Cancel",
                        focusConfirm: true,
                        allowOutsideClick: false,
                        willClose: function() {
                            return false;
                        },
                        didOpen: () => {
                            const imageInput = $("#imageInput");
                            imageInput.on("change", function() {
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    $("#profile").attr("src", e.target.result);
                                    console.log(e.target.result)
                                };
                                reader.readAsDataURL(this.files[0]);
                                image_flag = true;
                            });

                            const imageId = response.image_id;

                            $.ajax({
                                url: "../request/server/image.php",
                                method: "POST",
                                data: {
                                    image_id: imageId
                                },
                                success: function(result) {
                                    if (result) {
                                        $("#profile").attr("src", "data:image/jpeg;base64," + result);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Fetch Unsuccessful',
                                        text: "Fetching image failed",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            });
                        },
                        preConfirm: () => {
                            if (image_flag) {
                                const imageInput = $("#imageInput")[0];
                                const file = imageInput.files[0];
                                image_id = 0;
                                if (file) {
                                    const formData = new FormData();
                                    formData.append("image", file);
                                    formData.append("user_id", id);

                                    $.ajax({
                                        type: "POST",
                                        url: "../request/server/image.php",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        dataType: "json",
                                        success: function(response) {
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'success',
                                                title: 'Upload Successful',
                                                text: response.message,
                                                showConfirmButton: false,
                                                timer: 3000
                                            });

                                            $('#myTable').DataTable().ajax.reload();
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'error',
                                                title: 'Upload Unsuccessful',
                                                text: response.message,
                                                showConfirmButton: false,
                                                timer: 3000
                                            });
                                        }
                                    });
                                }
                            }
                            $.ajax({
                                url: "../request/server/record_action.php",
                                method: "POST",
                                data: $("#edit_form").serialize(),
                                dataType: "json",
                                success: function(response) {
                                    if (response.status == "success") {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Update Successful',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                        $('#myTable').DataTable().ajax.reload();
                                    } else {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'error',
                                            title: 'Update Unsuccessful',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Error',
                                        text: `An error occurred: ${status} - ${error}`,
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }

        function deleteRow(id, username) {
            Swal.fire({
                title: 'Delete User',
                text: `Are you sure you want to delete the user '${username}'?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                focusCancel: true,
                preConfirm: () => {
                    console.log("Delete row with ID:", id);
                    $.ajax({
                        url: '../request/server/record_action.php',
                        method: 'POST',
                        data: {
                            delete_id: id,
                            "username": username
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == "success") {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Delete Successful',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                $('#myTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Error',
                                text: `An error occurred: ${status} - ${error}`,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    });
                }
            });
        }


        $(document).ready(function() {
            var counter = 1;
            $('#myTable').DataTable({
                responsive: true,
                "ajax": {
                    "url": "../request/server/table.php",
                    "dataSrc": ""
                },
                "columns": [{
                        "data": null,
                        "title": "",
                        "render": function(data, type, row) {
                            return counter++;
                        }
                    },
                    {
                        "data": "image_id",
                        "title": "Image",
                        render: function(data, type, row) {
                            var imageData;
                            if (row.image_id != 0) {
                                $.ajax({
                                    url: '../request/server/image.php',
                                    method: 'POST',
                                    data: {
                                        image_id: row.image_id
                                    },
                                    async: false,
                                    success: function(result) {
                                        if (result) {
                                            imageData = "data:image/jpeg;base64," + result;
                                        } else {
                                            imageData = "../request/source/images/user.png";
                                        }
                                    }
                                });
                            } else {
                                imageData = "../request/source/images/user.png";
                            }
                            return '<img src="' + imageData + '" width="50" height="50">';
                        }


                    },
                    {
                        "data": "username",
                        "title": "Username"
                    },
                    {
                        "data": "email",
                        "title": "Email"
                    },

                    {
                        "data": "first_name",
                        "title": "First Name"
                    },
                    {
                        "data": "middle_name",
                        "title": "Middle Name"
                    },
                    {
                        "data": "last_name",
                        "title": "Last Name"
                    },
                    {
                        "data": "date_of_birth",
                        "title": "Date of Birth"
                    },
                    {
                        "data": null,
                        "title": "Actions",
                        "render": function(data, type, row) {
                            return `
                                    <div class="row w-auto h-50">
                                        <div class="col-sm-6 px-0">
                                            <button class="btn btn-primary w-100 btn-lg m-0" onclick="editRow(${row.user_id})">EDIT</button>
                                        </div>
                                        <div class="col-sm-6 px-2">
                                            <button class="btn btn-danger w-100 btn-lg m-0" onclick="deleteRow(${row.user_id}, \'${row.username}\')">DELETE</button>
                                        </div>
                                    </div>
                                    `;
                        }
                    }
                ]
            });

        });
    </script>

</body>

</html>