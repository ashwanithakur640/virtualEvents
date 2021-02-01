$('#changePasswordForm').validate({
    rules : {
        current_password: {
            required: true,
            minlength: 8,
            maxlength: 16,
        },

        new_password: {
            required: true,
            minlength: 8,
            maxlength: 16,
        },

        confirm_password: {
            required: true,
            minlength: 8,
            maxlength: 16,
            equalTo: "#new_password",
        },

    },
    messages:{
        current_password:{
            required: "Please enter your current password.",
            minlength: "Current password must be at least 8 characters.",
            maxlength: "Current password may not be greater than 16 characters.",
        },
        new_password:{
            required: "Please enter your new password.",
            minlength: "New password must be at least 8 characters.",
            maxlength: "New password may not be greater than 16 characters.",
        },
        confirm_password:{
            required: "Please enter your confirm password.",
            minlength: "Confirm password must be at least 8 characters.",
            maxlength: "Confirm password may not be greater than 16 characters.",
            equalTo: "New password and confirm password does not match.",
        },
    },
});

