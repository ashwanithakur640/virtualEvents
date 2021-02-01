$.validator.addMethod(
    "validEmail", function (value, element) {
        var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        return pattern.test(value);
    },'Please enter a valid email address.'
);

$('#registerVendorForm').validate({
        rules : {
            first_name: {
                required: true,
                lettersonly: true,
                minlength: 3,
                maxlength: 10,
            },
            middle_name: {
                lettersonly: true,
                minlength: 3,
                maxlength: 10,
            },
            last_name: {
                lettersonly: true,
                minlength: 3,
                maxlength: 10,
            },
            email: {
                required: true,
                email: true,
                validEmail: true,
                maxlength: 40,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
            },
            confirm_password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                equalTo: "#password",
            },
            mobile: {
                required: true,
            },
            // short_video: {
            //     required: true,
            // },
            company_business_domain: {
                required: true,
                maxlength: 100,
            },
             company_name: {
            required: true,
            maxlength: 50,
        },
        company_city_location: {
            required: true,
            maxlength: 30,
        },
        state: {
            required: true,
            maxlength: 20,
        },
        country: {
            required: true,
            maxlength: 20,
        },
        },
        messages:{
            first_name:{
                required: "Please enter your name.",
                lettersonly: "First name may only contain letters.",
                maxlength: "First name may not be greater than 10 characters.",
            },
            middle_name:{
                lettersonly: "Middle name may only contain letters.",
                maxlength: "Middle name may not be greater than 10 characters.",
            },
            last_name:{
                lettersonly: "Last name may only contain letters.",
                maxlength: "Last name may not be greater than 10 characters.",
            },
            email:{
                required: "Please enter your email.",
                maxlength: "Email may not be greater than 40 characters.",
            },
            password:{
                required: "Please enter your password.",
                minlength: "Password must be at least 8 characters.",
                maxlength: "Password may not be greater than 16 characters.",
            },
            confirm_password:{
                required: "Please enter your confirm password.",
                minlength: "Confirm password must be at least 8 characters.",
                maxlength: "Confirm password may not be greater than 16 characters.",
                equalTo: "Password and confirm password does not match.",
            },
            mobile:{
                required: "Please enter your phone number.",
            },
            // short_video: {
            //     required: "Please select a short video.",
            // },
            company_business_domain: {
                required: "Please enter your company business domain.",
                maxlength: "Company business domain may not be greater than 100 characters.",
            },
             company_name:{
            required: "Please enter company name.",
            maxlength: "Company name may not be greater than 50 characters.",
        },
        company_city_location:{
            required: "Please enter company city location.",
            maxlength: "Company city location may not be greater than 30 characters.",
        },
        state:{
            required: "Please enter state.",
            maxlength: "State may not be greater than 20 characters.",
        },
        country:{
            required: "Please enter country.",
            maxlength: "Country may not be greater than 20 characters.",
        },
        },
    });  
