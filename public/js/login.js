$.validator.addMethod(
    "validEmail", function (value, element) {
        var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        return pattern.test(value);
    },'Please enter a valid email address.'
);

$('#loginForm').validate({
    rules : {
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
    },
    messages:{
        email:{
            required: "Please enter your email.",
            maxlength: "Email may not be greater than 40 characters.",
        },
        password:{
            required: "Please enter your password.",
            minlength: "Password must be at least 8 characters.",
            maxlength: "Password may not be greater than 16 characters.",
        },
    },
});  


$('#forgotPasswordForm').validate({
    rules : {
        email: {
            required: true,
            email: true,
            validEmail: true,
            maxlength: 40,
        },
    },
    messages:{
        email:{
            required: "Please enter your email.",
            maxlength: "Email may not be greater than 40 characters.",
        },
    },
});

$('#resetPasswordForm').validate({
    rules : {
        password: {
            required: true,
            minlength: 8,
            maxlength: 16,
        },
        confirm_password: {
            required: true,
            minlength: 8,
            maxlength: 16,
            equalTo: "#passwordlInput",
        },
    },
    messages:{
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
    },
});