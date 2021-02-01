$.validator.addMethod(
    "validEmail", function (value, element) {
        var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        return pattern.test(value);
    },'Please enter a valid email address.'
);

$('#contactUs').validate({
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
        mobile: {
            required: true,
        },
        description: {
            required: true,
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
        mobile:{
            required: "Please enter your phone number.",
        },
        description:{
            required: "Please enter your message.",
        },
    },
});

$('#faqForm').validate({
    rules : {
        question: {
            required: true,
        },
    },
    messages:{
        question:{
            required: "Please enter your question.",
        },
    },
});