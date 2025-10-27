$(document).ready(function() {
    // book form
    $.validator.addMethod("filesize", function (value, element, param) {
        if (element.files.length > 0) {
            return element.files[0].size <= param;
        }
        return true;
    }, "File size must not exceed {0} bytes.");

    $.validator.addMethod('noSpace', function(value, element){
        return this.optional(element) || /^[^\s]+$/i.test(value);
    }, "No spaces are allowed.");

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

    
    $('#book-from').validate({
        rules: {
            book_name: {
                required: true,
                minlength: 3,
                maxlength: 80,
            },
            book_author: {
                required :true,
                lettersOnly: true
            },
            price: {
                required: true,
                number: true,
            },
            discount: {
                range: [0, 100]
            },
            book_desc: {
                required: true,
                minlength: 20
            },
            book_cgry: {
                required: true
            },
            book_cover: {
                required: function () {
                    return $("#book-from").data("edit-mode") === true;
                },
                accept: "image/*",
                filesize: 1 * 1000000
            },
            book_file: {
                required: function () {
                    return $("#book-from").data("edit-mode") === true; 
                },
                accept: "application/pdf",
                filesize: 20 * 1000000
            }   
        },
        messages: {
            book_name: {
                required: "Please Enter Book Title",
                minlength: "Book Title Must Be At Least 3 Characters Long",
                maxlength: "Book Title Must Not Exceed 80 Characters",
            },
            book_author: {
                required: "Please Enter Author Name",
                lettersOnly: "Author Name Must Contain Letters Only"
            },
            price: {
                required: "Please Enter Book Price",
                number: "Please Enter a Valid Numeric Value for Price"
            },
            discount: {
                range: "Discount Percentage Must Be Between 0 and 100"
            },
            book_desc: {
                required : "Please Enter Book Description",
                minlength: "Book Description Must Be At Least 20 Characters Long"
            },
            book_cgry: {
                required: "Please Select Book Cetagory"
            },
            book_cover: {
                required: "Please select a book cover.",
                accept: "Only image files are allowed.",
                filesize: "The book cover must not exceed 1MB."
            },
            book_file: {
                required: "Please select a book PDF.",
                accept: "Only PDF files are allowed.",
                filesize: "The book file must not exceed 20MB."
            } 
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
            if(element.attr('name') == "book_cgry"){
                error.insertAfter('.error-cgry-box');
            }
            if(element.attr('name') == "book_cover"){
                error.insertAfter('.error-book-cover');
            }
            if(element.attr('name') == "book_file"){
                error.insertAfter('.error-book-pdf');
            }
        }
    });

    // banner form
    $('#banner-form').validate({
        rules: {
            banner_name: {
                required: true,
                minlength: 5,
                maxlength: 30
            },
            banner_image: {
                required: true,
                accept: "image/*",
                filesize: 1 * 1000000
            }        
        },
        messages: {
            banner_name: {
                required: "Please Enter Banner Title",
                minlength: "Banner Title Must Be At Least 5 Characters Long",
                maxlength: "Banner Title Must Not Exceed 30 Characters",
            },
            banner_image: {
                required: "Please Select Banner Image",
                accept: "Please Select Image File Only",
                filesize: "File Size Must be Less Than 1 Mb"
            }   
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

    //coupon form
    $.validator.addMethod("datesBefore", function(value, element, params) {
        var startDate = $(params[0]).val(); 
        var endDate = value; 
        return new Date(startDate) <= new Date(endDate);
    }, "End date must be after the start date.");

    $('#coupon-form').validate({
        rules: {
            coupon_title: {
                required: true,
                minlength: 2,
                maxlength: 10
            },
            coupon_code: {
                required: true,
                minlength:6,
                maxlength:6
            },
            coupon_start: {
                required: true
            },
            coupon_end: {
                required: true,
            },
            type: {
                required: true,
            },
            coupon_parecent: {
                required: true,
                range:[1,100]
            },
            coupon_price: {
                required: true,
            }
        },
        messages: {
            coupon_title: {
                required: "Please Enter Coupon Title",
                minlength: "Coupon Title Must Be At Least 3 Characters Long",
                maxlength: "Coupon Title Must Not Exceed 10 Characters",
            },
            coupon_code: {
                required: "Please Enter Coupon Code",
                minlength: "Coupon Code Must Be 6 Characters",
                maxlength: "Coupon Code Must Be 6 Characters",
            },
            coupon_start: {
                required: "Please Select Coupon Start Date."
            },
            coupon_end: {
                required: "Please Select Coupon End Date.",
            },
            type: {
                required: "Please Select Coupon Type",
            },
            coupon_parecent:{
                required: "Please Enter Discount In Parcentage",
                range: "Enter Value In 1 to 100"
            },
            coupon_price: {
                required: "Please Enter Discount In Value",
            }
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
            if(element.attr('name') == "type"){
                error.insertAfter('.error-coupon-type');
            }
            if(element.attr('name') == "coupon_parecent"){
                error.insertAfter('.error-parecent');
            }
            if(element.attr('name') == "coupon_price"){
                error.insertAfter('.error-price');
            }
        }
    });

    //footer link form
    $('#link-form').validate({
        rules: {
            link_upld: {
                required: true,
                url: true
            },  
        },
        messages: {
            link_upld: {
                required: "Please Enter URL",
                url: "Please Enter Valid URL"
            },
            
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            element.addClass('.error');
            error.insertAfter(element);
        }
    });
    
    //admin password change form
    $('#admin_password_edit').validate({
        rules: {
            old_pswd: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            new_pswd: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            confirm_pswd: {
                required: true,
                equalTo: "#new_pswd"
            },  
        },
        messages: {
            old_pswd: {
                required: "Please Enter New Password",
                minlength: "Password Must Be At Least 8 Characters Long",
                maxlength: "Password Must Not Exceed 20 Characters Long",
            },
            new_pswd: {
                required: "Please Enter New Password",
                minlength: "Password Must Be At Least 8 Characters Long",
                maxlength: "Password Must Not Exceed 20 Characters Long",
            },
            confirm_pswd: {
                required: "Please Enter Confirm Password",
                equalTo: "Password Do Not Match"
            },
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

    //admin edit form
    $('#admin_edit').validate({
        rules: {
            uname: {
                required: true,
                noSpace:true,
                minlength: 5,
                maxlength: 30
            },
            email: {
                required: true,
                email: true
            }   
        },
        messages: {
            uname: {
                required: "Please Enter Your Name",
                minlength: "Name Must Be At Least 5 Characters Long",
                maxlength: "Name Must Not Exceed 30 Characters",
            },
            email: {
                required: "Please Enter Email",
                email: "Please Enter Valid Email Address"
            }  
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

    //user form
    $('#contact_us').validate({
        rules: {
            about_us: {
                required: true,
                minlength: 3,
                maxlength: 300
            },
            address: {
                required: true,
                minlength: 5,
                maxlength: 60,
            },
            p_number:{
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            email:{
                required: true,
                email: true
            }
        },
        messages: {
            about_us: {
                required: "Please Enter About Us Info",
                minlength: "Info Must Be At Least 3 Characters Long",
                maxlength: "Info Must Not Exceed 300 Characters"
            },
            address: {
                required: "Please Enter Address",
                minlength: "Address Must Be At Least 5 Characters Long",
                maxlength: "Address Must Not Exceed 60 Characters Long"
            },
            p_number:{
                required: "Please Enter Password",
                minlength: "Phone Number Must be 10 digits",
                maxlength: "Phone Number Must be 10 digits",
            },
            email:{
                required: "Please Enter Email",
                email: "Please Enter A Valid Email Address"
            }
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

    $('#message-form').validate({
        rules: {
            message:{
                required: true,
                minlength: 5,
                maxlength: 260
            }
        },
        messages: {
            message:{
                required: "Please Enter Message",
                minlength: "Message Must Be At Least 5 Characters Long",
                maxlength: "Message Must Not Exceed 260 Characters Long"
            }
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

    $('#category-form').validate({
        rules: {
            category_title:{
                required: true,
                minlength: 2,
                maxlength: 20
            }
        },
        messages: {
            category_title:{
                required: "Please Enter Category Title",
                minlength: "Title Must Be At Least 2 Characters Long",
                maxlength: "Title Must Not Exceed 20 Characters Long"
            }
            
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('.error');
            error.insertAfter(element);
        }
    });

});