$(function() {

    $('.nav-item.dropdown').mouseenter(function() {
        $(this).addClass('show');
        $(this).children('.dropdown-menu').addClass('show');
        $(this).children('.dropdown-toggle').attr('aria-expanded', 'true');
    }).mouseleave(function() {
        $(this).removeClass('show');
        $(this).children('.dropdown-menu').removeClass('show');
        $(this).children('.dropdown-toggle').attr('aria-expanded', 'false');
    });

    $('.img-small').on('mouseenter click', function() {
        var src = $(this).data('src');
        $('.img-large').css("background-image", "url('"+src+"')");
    });

    var imgLarge = $('.img-large');

    imgLarge.mousemove(function() {
        var relX = event.pageX - $(this).offset().left;
        var relY = event.pageY - $(this).offset().top;
        var width = $(this).width();
        var height = $(this).height();
        var x = (relX / width) * 100;
        var y = (relY / height) * 100;
        $(this).css("background-position", x+"% "+y+"%");
    });

    imgLarge.mouseout(function() {
        $(this).css("background-position", "center");
    });

    $( window ).resize(function() {
        setImgLarge();
        setImgSmall();
    });

    setImgLarge();
    setImgSmall();

});
$.validator.addMethod("alpha_number_dot", function(value, element) {
    return this.optional( element ) || /^[a-zA-Z0-9.]+$/.test( value );
}, "Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.");
$.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Only alphabetical characters");
$.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0 && value != "";
}, "No space please");
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
});
$.validator.addMethod("greaterThan",function (value, element, param) {
  return parseFloat(value) != 0;
},"This field required");
$.validator.addMethod("extension", function(value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, $.validator.format("Please enter a value with a valid extension."));
$.validator.addMethod("validDateFormat", function(value, element) {
     return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{4}$/);
}, "Select a date in the format dd-mm-yyyy.");

$(document).ready(function() {

    $('.removeFromCart').on('click',function(){
        let product_details = $(this).attr('data-product_details');
        if(product_details) {
            $.ajax({
                    url: 'my_cart/remove_from_cart',
                    type: 'post',
                    data: {'key':product_details},
                    dataType: 'json',
                    beforeSend: function() {},
                    complete: function() {},
                    success: function(jdata) {
                        if(jdata.status == '200') {
                            $('#header-qty').text(jdata.product_count);
                            show_alert(jdata.message, 'success',true);
                        } else {
                            show_alert(jdata.message, 'danger');
                        }
                    }
                });

        }else {
            show_alert('Somethink went wrong, please try again', 'danger');
        }
    });

    $('#frm_register').validate({
        rules: {
            name : {
                required : true
            },
            mobile_number : {
                required : true,
                digits : true,
                minlength: 10,
                maxlength: 10,
                remote: {
                    url: "home/is_username_exits",
                    type: "post",
                    data: { mobile_number: function() { return $( "#mobile_number" ).val(); } }
                }
            },
            email : {
                required : true,
                email : true
            },
            password : {
                required : true
            },
            password_confirm : {
                required : true,
                equalTo : '#password'
            },
            agree : {
                required : true
            }
        },
        messages: {
            name : {
                required : "Please enter name"
            },
            mobile_number : {
                required : "Please enter mobile number",
                digits : "Please enter valid number",
                minlength: "Please enter valid 10 digit number",
                maxlength: "Please enter valid 10 digit number",
                remote : "{0} username exists"
            },
            email : {
                required : "Please enter email ID",
                email : 'Please enter valid email ID'
            },
            password : {
                required : "Please enter password"
            },
            password_confirm : {
                required : "Please enter comfirm password",
                equalTo: "Please enter the same password as above"
            },
            agree : {
                required : "Please accept terms and conditions"
            }
        },
        submitHandler: function() {

            $.ajax({
                url: $('#frm_register').attr('action'),
                type: 'post',
                data: $('#frm_register').serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#btn_register').text('updating...').attr('disabled','disabled');
                },
                complete: function() {
                    $('#btn_register').text('Submit').removeAttr('disabled');
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus, errorThrown);
                },
                success: function(jdata) {
                    if(jdata.status == 200) {
                        $('#frm_register')[0].reset();
                        show_alert(jdata.message, 'success',true);
                    } else {
                        show_alert(jdata.message, 'danger');
                    }
                }
            });
        }
    });

    $('#frm_login').validate({
            rules: {
                mobile_no : {
                    required : true
                },
                password : {
                    required : true
                }
            },
            messages: {
                mobile_no : {
                    required : "Please enter mobile number"
                },
                password : {
                    required : "Please enter password"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: $('#frm_login').attr('action'),
                    type: 'post',
                    data: $('#frm_login').serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#btn_login').text('verifying...').attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('#btn_login').text('Verify').removeAttr('disabled');
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(textStatus, errorThrown);
                    },
                    success: function(jdata) {
                        if(jdata.status == '200') {
                            if(typeof jdata.last_visit_url != 'undefined') 
                                window.location.href = jdata.last_visit_url;
                            else
                                window.location.href = jdata.redirect_url;

                            return;
                        } else {
                            show_alert(jdata.message, 'danger');
                        }
                    }
                });
            }
    });

    $('#frm_checkout').validate({
        rules: {
            name : {
                required : true
            },
            mobile_number : {
                required : true,
                digits : true,
                minlength: 10,
                maxlength: 10
            },
            plat_house_no : {
                required : true
            },
            area : {
                required : true
            },
            landmark : {
                required : true
            },
            city : {
                required : true
            },
            pincode : {
                required : true,
                digits : true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            name : {
                required : "Please enter name"
            },
            mobile_number : {
                required : "Please enter mobile number",
                digits : "Please enter valid number",
                minlength: "Please enter valid 10 digit number",
                maxlength: "Please enter valid 10 digit number",
            },
            plat_house_no : {
                required : "Please enter Flat/House No"
            },
            area : {
                required : "Please enter Area number"
            },
            landmark : {
                required : "Please enter Landmark number"
            },
            city : {
                required : "Please enter City number"
            },
            pincode : {
                required : "Please enter pincode number",
                digits : "Please enter valid pincode",
                minlength: "Please enter valid 6 digit number",
                maxlength: "Please enter valid 6 digit number",
            }
        },
        submitHandler: function() {

            $.ajax({
                url: $('#frm_checkout').attr('action'),
                type: 'post',
                data: $('#frm_checkout').serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#btn_book_order').text('please wait...').attr('disabled','disabled');
                },
                complete: function() {
                    $('#btn_book_order').text('Submit').removeAttr('disabled');
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(textStatus, errorThrown);
                },
                success: function(jdata) {
                    if(jdata.status == 200) {
                        $('#myModal').modal('hide');
                        $('#myModalSuccess').modal('show');
                        show_alert(jdata.message, 'success',true);
                    } else {
                        show_alert(jdata.message, 'danger');
                    }
                }
            });
        }
    });
});
function setImgLarge() {
    var imgLarge = $('.img-large');
    var width = imgLarge.width();
    imgLarge.height(width * 2/3);
}

function setImgSmall() {
    var imgSmall = $('.img-small');
    var width = imgSmall.width();
    imgSmall.height(width);
}

function show_alert(content, alert_type = 'info',refresh = false) {
    if(content) {
        $.notify({ message: content  },{ type: alert_type });
        //$('#alertMessage').removeClass().addClass('alert alert-dismissible fade show alert-'+alert_type).html('<button type="button" class="close" data-dismiss="alert"><small><i class="fas fa-times"></i></small></button><i class="fas fa-check-circle mr-2"></i> '+content).show();
    }

    if(refresh) {
        setTimeout(function(){ window.location.reload(1); }, 2000);
    }

}

window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 3000);

$(document).on('click', '.addToCart', function() {
    let product_details = $(this).attr('data-product_details');
    if(product_details) {

        $.ajax({
                url: 'my_cart/add_to_cart',
                type: 'post',
                data: {'details':product_details},
                dataType: 'json',
                beforeSend: function() {},
                complete: function() {},
                success: function(jdata) {
                    if(jdata.status == '200') {
                        $('#header-qty').text(jdata.product_count);
                        show_alert(jdata.message, 'success');
                    } else {
                        show_alert(jdata.message, 'danger');
                    }
                }
            });

    }else {
        show_alert('Somethink went wrong, please try again', 'danger');
    }
});
