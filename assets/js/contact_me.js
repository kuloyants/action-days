$(function() {

    $("input,select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            //event.preventDefault(); // prevent default submit behaviour
            //$.ajax({
            //    url: "?section=registration",
            //    type: "POST",
            //    data: $form.serialize(),
            //    cache: false,
            //    success: function(response) {
            //        //if ($(response).find('form')) {
            //        //    var form = $(response).find('form');
            //        //    $('form').replaceWith(form);
            //        //    form = $('form select.bfh-countries');
            //        //    form.bfhcountries(form.data());
            //        //} else {
				//	//
            //        //}
			//
            //        //$('html').html(response)
			//
			//
			//
			//
			//
            //    },
            //    error: function() {
            //        // Fail message
            //        $('#success').html("<div class='alert alert-danger'>");
            //        $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            //            .append("</button>");
            //        $('#success > .alert-danger').append("<strong>Sorry " + firstName + ", it seems that my mail server is not responding. Please try again later!");
            //        $('#success > .alert-danger').append('</div>');
            //        //clear all fields
            //        $('#contactForm').trigger("reset");
            //    },
            //})
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
