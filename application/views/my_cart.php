<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-12">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12 mt-3 text-center text-uppercase">
            <h2>Shopping Cart</h2>
        </div>
    </div>

    <main class="row">
        <div class="col-12 bg-white py-3 mb-3">
            <div class="row">
                <div class="col-lg-8 col-md-10 col-sm-10 mx-auto table-responsive">
                    <?php if(!empty($my_cart)) {  ?>
                    <form class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover table-sm">
                                <thead>
                                
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($my_cart as $key => $items) { ?>
                                    <tr>
                                        <td>
                                            <img src="assets/images/<?php echo $items['image'] ?>" class="img-fluid">
                                        </td>
                                        <td>
                                            <i class="fa fa-inr"></i><?php echo $items['subtotal']; ?>
                                        </td>
                                        <td>
                                            <input type="number" min="1" value="<?php echo $items['qty']; ?>">
                                        </td>
                                        <td>
                                            <i class="fa fa-inr"></i><?php echo $items['subtotal']; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-link text-danger removeFromCart" data-product_details="<?php echo $key; ?>"><i class="fas fa-times"></i></button>
                                            <td style="text-align:right"> </td>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                
                                
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th><i class="fa fa-inr"></i><?php echo $this->cart->format_number($this->cart->total()); ?></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="col-12 text-right">
                            <a href="#" class="btn btn-outline-success" data-toggle="modal" data-target="#myModal">Checkout</a>
                        </div>
                    </form>
                    <?php }else { ?>
                        <hr>
                        <h4 class="text-center">Continue to shoping</h4>
                        <p class="text-center"><a href="">Home</a></p>
                        <hr>
                    <?php } ?>
                </div>
            </div>
        </div>

    </main>
    <!-- Main Content -->
</div>

<div class="modal" id="myModal">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
        
            <div class="modal-header">
                <h4 class="modal-title text-center">Checkout</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php echo form_open(SITE_URL.'my_cart/frm_checkout', array('name'=>'frm_checkout','id'=>'frm_checkout')); ?>
            <input type="hidden" name="userID" id="userID" value="<?php echo set_value('name',$this->login_info['id']);?>">
            <div class="modal-body">
                <?php 
                if(empty($this->login_info)) {
                    echo '<div class="col-md-6"><div class="form-group checkbox"> <label>Guest checkout ';
                        echo "<input type='checkbox' value='guest' id='login_quest' name='login_quest' checked disabled> </label>";
                    echo "</div></div>";
                }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo set_value('name',$this->login_info['full_name']);?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="mobile_number" name="mobile_number" class="form-control" required placeholder="Mobile Number" value="<?php echo set_value('mobile_number',$this->login_info['mobile_no']);?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="plat_house_no" name="plat_house_no" class="form-control" required placeholder="Flat/House No">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="area" name="area" class="form-control" required placeholder="Area">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="landmark" name="landmark" class="form-control" required placeholder="Landmark">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="city" name="city" class="form-control" required placeholder="City">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="pincode" name="pincode" maxlength="6" minlength="6" class="form-control" required placeholder="Pincode">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="state" name="state" class="form-control" required placeholder="State">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_book_order" class="btn btn-success" name="btn_book_order">Order</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal" id="myModalSuccess">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Order placed successfully</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
    </div>
</div>
