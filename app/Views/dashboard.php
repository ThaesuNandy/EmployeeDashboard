<?=$this->extend("layout")?>
  
<?=$this->section("content")?>
<nav>
    <div class="container">
        <div class="m-2 p-2">
            <h2>Employee Dashboard</h2>
        </div>
        <ul>
            <li class="nav-item">
            <a href="" class="nav-link">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<?php if (session()->has('message')){ ?>
            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                <?=session()->getFlashdata('message') ?>
            </div>
        <?php } ?>
    <?php $validation = \Config\Services::validation(); ?>
<div class="container-fluid mt-4">
    <div class="row justify-content-end">
        <div class="col-md-1">
            <button type="button" class="btn btn-success" data-bs-toggle="modal"  data-bs-target="#addEmployeeModal"><i class="fa-solid fa-user-plus"></i> Add</button>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success" data-bs-toggle="modal"  data-bs-target="#updateEmployeeModal"><i class="fa-regular fa-pen-to-square"></i> Update</button>
        </div>
        <div class="col-md-1">
            <a href="<?php echo base_url('employeeController/export'); ?>" class="btn btn-success"><i class="fa-solid fa-file-export"></i>Export</a>
        </div>
    </div>

    <div class="mt-2">
        <table class="table table-bordered table-hover" id="emplyoee-list">
        <thead>
            <tr>
                <th> Id</th>
                <th> Employee Name</th>
                <th> Email </th>
                <th> Phone </th>
                <th> Address </th>
                <th> Action </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php if($data): ?>
            <?php foreach($data as $employee): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['email']; ?></td>
                <td><?php echo $employee['phone']; ?></td>
                <td><?php echo $employee['address']; ?></td>
                <td>
                    <a href="<?php echo base_url('edit_employee/'.$employee['id']);?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-user-pen"></i> &nbsp;Edit</a>
                    <form action="<?php echo base_url('delete_employee/'.$employee['id']);?>"
                        method="post"
                        class="d-inline-block" >
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn btn-danger  btn-sm"><i class="fa-solid fa-trash-can"></i>  &nbsp;Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        </table>
        <?= $pager->makeLinks($page,$perPage, $total, 'custom_view') ?>
    </div>
</div>

<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title fs-5">Employee Information</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo base_url('employeeController/importCsvToDb');?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">  
                    <div class="mt-2">
                        <?php if (session()->has('message')){ ?>
                            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                                <?=session()->getFlashdata('message') ?>
                            </div>
                        <?php } ?>
                        <?php $validation = \Config\Services::validation(); ?>
                    </div>	
                        <div class="mb-3">
                            <input type="file" name="file" class="form-control" id="file">
                        </div>					   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button  type="submit" name="submit" class="btn btn-dark" >Upload</button>
                </div>
            </form>   
        </div>
    </div>
</div>

<!-- Update Modal HTML -->
<div id="updateEmployeeModal" class="modal fade"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title fs-5">Employee Information</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url('employeeController/modifyDatabaseWithExcel') ?>" enctype="multipart/form-data">
                <div class="modal-body">  
                    <div class="mb-3">
                        <input type="file" name="employee_excel_file">
                    </div>					   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-dark">Upload and Modify</button>
                </div>
            </form>   
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // $('#emplyoee-list').DataTable();
        $('#employeeForm').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function (response) {
                    $('#addEmployeeModal').modal('hide');
                    window.location.reload();
                    // if (response.success) {
                      
                    // } else if (response.error) {
                    //     console.log(response.error);
                    //     $('#validationErrors').html(response.error).show();
                    // }
                },
                error: function (error) {
                    $('#validationErrors').html('An error occurred while processing your request. Please try again later.').show();
                },
            });
        });
    });
</script>
<?=$this->endSection()?>