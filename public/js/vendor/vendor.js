$.validator.addMethod(
    "validEmail", function (value, element) {
        var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        return pattern.test(value);
    },'Please enter a valid email address.'
);
(function($) {
    $.fn.flash_message = function(options) {
      
      options = $.extend({
        text: 'Done',
        time: 1000,
        how: 'before',
        class_name: ''
      }, options);
      
      return $(this).each(function() {
        if( $(this).parent().find('.flash_message').get(0) )
          return;
        
        var message = $('<span />', {
          'class': 'flash_message ' + options.class_name,
          text: options.text
        }).hide().fadeIn('fast');
        
        $(this)[options.how](message);
        
        message.delay(options.time).fadeOut('normal', function() {
          $(this).remove();
        });
        
      });
    };
})(jQuery);
$('#sessionForm').validate({
    rules : {
        name: {
            required: true,
            maxlength: 30,
        },
        event_id: {
            required: true,
        },
        date: {
            required: true,
        },
        start_time: {
            required: true,
        },
        end_time: {
            required: true,
        },
        description: {
            required: true,
        },
    },
    messages:{
        name:{
            required: "Please enter session name.",
            maxlength: "Session name may not be greater than 30 characters.",
        },
        event_id:{
            required: "Please select event.",
        },
        date:{
            required: "Please select date.",
        },
        start_time:{
            required: "Please select session start time.",
        },
        end_time:{
            required: "Please select session end time.",
        },
        description:{
            required: "Please enter session description.",
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

$('#addProfileForm').validate({
    rules : {
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
            email: true,
        },
    },
    messages:{
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
    },
});

$('#editVendorProfile').validate({
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
                email: true,
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

    