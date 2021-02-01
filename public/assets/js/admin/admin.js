$.validator.addMethod(
    "validEmail", function (value, element) {
        var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        return pattern.test(value);
    },'Please enter a valid email address.'
);

$('#editAdminProfileForm').validate({
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
    },
});

$('#createVendorForm').validate({
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
        short_video: {
            required: true,
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
        address: {
            maxlength: 100,
        },
        website: {
            maxlength: 100,
        },
        office_no: {
            maxlength: 20,
        },
        office_email_id: {
            maxlength: 50,
        },
        company_business_domain: {
            required: true,
            maxlength: 100,
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
        short_video: {
            required: "Please select a short video.",
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
        address:{
            maxlength: "Address may not be greater than 100 characters.",
        },
        website:{
            maxlength: "Website may not be greater than 100 characters.",
        },
        office_no:{
            maxlength: "Office no may not be greater than 20 characters.",
        },
        office_email_id:{
            maxlength: "Office email may not be greater than 50 characters.",
        },
        company_business_domain: {
            required: "Please enter your company business domain.",
            maxlength: "Company business domain may not be greater than 100 characters.",
        },
    },
});

$('#editVendorForm').validate({
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
        address: {
            maxlength: 100,
        },
        website: {
            maxlength: 100,
        },
        office_no: {
            maxlength: 20,
        },
        office_email_id: {
            maxlength: 50,
        },
        company_business_domain: {
            required: true,
            maxlength: 100,
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
        short_video: {
            required: "Please select a short video.",
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
        address:{
            maxlength: "Address may not be greater than 100 characters.",
        },
        website:{
            maxlength: "Website may not be greater than 100 characters.",
        },
        office_no:{
            maxlength: "Office no may not be greater than 20 characters.",
        },
        office_email_id:{
            maxlength: "Office email may not be greater than 50 characters.",
        },
        company_business_domain: {
            required: "Please enter your company business domain.",
            maxlength: "Company business domain may not be greater than 100 characters.",
        },
    },
});

$('#frontendContent').validate({
    rules : {
        page_id: {
            required: true,
        },
        title: {
            required: true,
        },
        description: {
            required: true,
        },
    },
    messages:{
        page_id:{
            required: "Please select your page.",
        },
        title:{
            required: "Please enter page title.",
        },
        description:{
            required: "Please enter page description.",
        },
    },
});

$('#eventForm').validate({
    rules : {
        name: {
            required: true,
            maxlength: 30,
        },
        title: {
            required: true,
            maxlength: 50,
        },
        category_id: {
            required: true,
        },
        start_date_time: {
            required: true,
        },
        end_date_time: {
            required: true,
        },
        description: {
            required: true,
        },
    },
    messages:{
        name:{
            required: "Please enter event name.",
            maxlength: "Event name may not be greater than 30 characters.",
        },
        title:{ 
            required: "Please enter event title.",
            maxlength: "Event title may not be greater than 50 characters.",
        },
        category_id:{ 
            required: "Please select category.",
        },
        start_date_time:{
            required: "Please select event start date & time.",
        },
        end_date_time:{
            required: "Please select event end date & time.",
        },
        description:{
            required: "Please enter event description.",
        },
    },
});

$('#editCustomersForm').validate({
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
        company_name: {
            maxlength: 50,
        },
        company_city_location: {
            maxlength: 30,
        },
        state: {
            maxlength: 20,
        },
        country: {
            maxlength: 20,
        },
        address: {
            maxlength: 100,
        },
        website: {
            maxlength: 100,
        },
        office_no: {
            maxlength: 20,
        },
        office_email_id: {
            maxlength: 50,
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
        company_name:{
            maxlength: "Company name may not be greater than 50 characters.",
        },
        company_city_location:{
            maxlength: "Company city location may not be greater than 30 characters.",
        },
        state:{
            maxlength: "State may not be greater than 20 characters.",
        },
        country:{
            maxlength: "Country may not be greater than 20 characters.",
        },
        address:{
            maxlength: "Address may not be greater than 100 characters.",
        },
        website:{
            maxlength: "Website may not be greater than 100 characters.",
        },
        office_no:{
            maxlength: "Office no may not be greater than 20 characters.",
        },
        office_email_id:{
            maxlength: "Office email may not be greater than 50 characters.",
        },
    },
});  

$('#categoryForm').validate({
    rules : {
        name: {
            required: true,
            maxlength: 30,
        },
        description: {
            required: true,
        },
    },
    messages:{
        name:{
            required: "Please enter category name.",
            maxlength: "Category name may not be greater than 30 characters.",
        },
        description:{
            required: "Please enter category description.",
        },
    },
});

$('#paymentAmount').validate({
    rules : {
        amount: {
            required: true,
        },
    },
    messages:{
        amount:{
            required: "Please enter an amount.",
        },
    },
});

$(document).ready(function() {    

});