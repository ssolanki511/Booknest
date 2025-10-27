$(document).ready(function() {
    $('.password-eye').click(function() {
        let type = $('#password').attr('type');
        if (type === 'password') {
            type = 'text';
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            type = 'password';
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
        $('#password').attr('type', type);
    });

    $.validator.addMethod('noSpace', function(value, element){
        return this.optional(element) || /^[^\s]+$/i.test(value);
    }, "No spaces are allowed.");

    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
        },
        messages: {
            email: {
                required: "Please Enter Email",
                email: "Please Enter A Valid Email Address"
            },
            password: {
                required: "Please Enter Password",
            },
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
            if (element.attr('name') == "password") {
                error.insertAfter('.error-password');
            }
        }
    });

    $('#registerForm').validate({
        rules: {
            username: {
                required: true,
                minlength: 3,
                maxlength: 20,
                noSpace:true,
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            }
        },
        messages: {
            username: {
                required: "Please Enter Username",
                minlength: "Username Must Be At Least 3 Characters Long",
                maxlength: "Username Must Not Exceed 20 Characters"
            },
            email: {
                required: "Please Enter Email",
                email: "Please Enter A Valid Email Address"
            },
            password: {
                required: "Please Enter Password",
                minlength: "Password Must Be At Least 8 Characters Long",
                maxlength: "Password Must Not Exceed 20 Characters Long",
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
            if (element.attr('name') == 'password') {
                error.insertAfter('.error-password');
            }
        }
    });

    $('#changePassword').validate({
        rules: {
            current_password: {
                required: true,
            },
            n_password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            c_password: {
                required: true,
                equalTo: "#n_password"
            }
        },
        messages: {
            current_password: {
                required: "Please Enter Current Password",
            },
            n_password: {
                required: "Please Enter New Password",
                minlength: "Password Must Be At Least 8 Characters Long",
                maxlength: "Password Must Not Exceed 20 Characters",
            },
            c_password: {
                required: "Please Confirm Your Password",
                equalTo: "Passwords Do Not Match"
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('.error-field');
            error.insertAfter(element);
        }
    });

    $('#forgotForm').validate({
        rules: {
            email: {
                required: true,
                email: true,
            }
        },
        messages: {
            email: {
                required: "Please Enter Your Email",
                email: "Please Enter Valid Email Address"
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);

        }
    });

    $('#resetPasswordForm').validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            confirmPassword: {
                required: true,
                equalTo: "#password"
            },
        },
        messages: {
            password: {
                required: "Please Enter New Password",
                minlength: "Password Must Be At Least 8 Characters Long",
                maxlength: "Password Must Not Exceed 20 Characters Long",
            },
            confirmPassword: {
                required: "Please Enter Confirm Password",
                equalTo: "Password Do Not Match"
            },
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
        }
    });

    $('#editUserForm').validate({
        rules: {
            username: {
                required: true,
                minlength: 3,
                noSpace:true,
                maxlength: 20,
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            username: {
                required: "Please Enter Username",
                minlength: "Username Must Be At Least 3 Characters Long",
                maxlength: "Username Must Not Exceed 20 Characters"
            },
            email: {
                required: "Please Enter Email",
                email: "Please Enter A Valid Email Address"
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
        }
    });

    $('#contactForm').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 250
            },
        },
        messages: {
            name: {
                required: "Please Enter Username"
            },
            email: {
                required: "Please Enter Email",
                email: "Please Enter A Valid Email Address"
            },
            message: {
                required: "Please Enter Message",
                minlength: "Message Must Be At Least 10 Characters Long",
                maxlength: "Message Must Not Exceed 250 Characters"
            },
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
        }
    });

    $('#reviewForm').validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            description: {
                required: true,
                minlength: 10,
                maxlength: 400
            },
            rpage:{
                required: true
            }
        },
        messages: {
            title: {
                required: "Please Enter Review Title",
                minlength: "Title Must Be At Least 3 Characters Long",
                maxlength: "Title Must Not Exceed 50 Characters"
            },
            description: {
                required: "Please Enter Review Description",
                minlength: "Description Must Be At Least 10 Characters Long",
                maxlength: "Description Must Not Exceed 400 Characters"
            },
            rating:{
                required: "Please Select Rating."
            }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass('error-field');
            error.insertAfter(element);
            if (element.attr('name') == 'rating') {
                error.insertAfter('.rating-error');
            }
        }
    });

})