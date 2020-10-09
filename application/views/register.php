<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-12">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12 mt-3 text-center text-uppercase">
            <h2>Register</h2>
        </div>
    </div>

    <main class="row">
        <div class="col-lg-6 col-md-8 col-sm-12 mx-auto bg-white py-3 mb-4">
            <div class="row">
                <div class="col-12">
                    <?php echo form_open(SITE_URL.'home/frm_register', array('name'=>'frm_register','id'=>'frm_register')); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Select Gender</label>
                                    <?php
                                        $gerder = GENDER;
                                         unset($gerder['0']);
                                        echo form_dropdown('gender', $gerder, set_value('gender'), 'class="form-control" id="gender"');
                                        echo form_error('gender');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Mobile Number</label>
                                    <input type="number" maxlength="10" minlength="10" id="mobile_number" name="mobile_number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" minlength="5" id="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input type="password" minlength="5" id="password_confirm" name="password_confirm" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="agree" name="agree" value="accept" class="form-check-input" required>
                                <label for="agree" class="form-check-label ml-2">I agree to Terms and Conditions</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" id="btn_register" name="btn_register" class="btn btn-outline-dark">Register</button>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

    </main>
    <!-- Main Content -->
</div>