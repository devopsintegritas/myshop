<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-12">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12 mt-3 text-center text-uppercase">
            <h2>Login</h2>
        </div>
    </div>

    <main class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 mx-auto bg-white py-3 mb-4">
            <div class="row">
                <div class="col-12">
                    <?php echo form_open(SITE_URL.'login/frm_login', array('name'=>'frm_login','id'=>'frm_login')); ?>
                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="number" id="mobile_no" name="mobile_no" maxlength="10" minlength="10" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label ml-2">Remember Me</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="btn_login" name="btn_login" class="btn btn-outline-dark">Login</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

    </main>
    <!-- Main Content -->
</div>