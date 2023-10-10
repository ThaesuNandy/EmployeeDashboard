<?=$this->extend("layout")?>
  
<?=$this->section("content")?>
    <div class="container">
        <h3 class="text-center mt-5">Employee Information</h3>       
        <form action="<?= base_url('update_employee/'.$employee['id']); ?>" method="post">
            <?= csrf_field(); ?>
            <section class="row card frame mt-4 mx-5 px-5 py-5">
                <div class="row mt-3 mb-4">
                    <div class="col-md-2 offset-md-1">
                        <label for="name">
                            Employee Name
                        </label>
                    </div>
                    <div class="col-md-8 ">
                        <input type="text" name="name" id="name" class="form-control" value="<?= $employee['name']; ?>">
                    </div>
                </div>
        
                <div class="row mt-3 mb-4">
                    <div class="col-md-2 offset-md-1">
                        <label for="email">
                            Email
                        </label>
                    </div>
                    <div class="col-md-8 ">
                        <input type="email" name="email" id="email" class="form-control" value="<?= $employee['email']; ?>">
                    </div>
                </div>

                <div class="row mt-3 mb-4">
                    <div class="col-md-2 offset-md-1">
                        <label for="phone">
                            Phone Number
                        </label>
                    </div>
                    <div class="col-md-8 ">
                        <input type="text" name="phone" id="phone" class="form-control" value="<?= $employee['phone']; ?>">
                    </div>
                </div>

                <div class="row mt-3 mb-4">
                    <div class="col-md-2 offset-md-1">
                        <label for="address">
                            Address
                        </label>
                    </div>
                    <div class="col-md-8 ">
                        <textarea name="address" id="address" class="form-control"><?= $employee['address']; ?></textarea>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-2 offset-md-3">
                        <button class="btn btn-secondary" action="action" onclick="window.history.go(-1); return false;" >return</button>
                    </div>
                    <div class="col-md-6" align="right">
                        <button type="submit" class="btn btn-primary ">Update</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
    

<?=$this->endSection()?>