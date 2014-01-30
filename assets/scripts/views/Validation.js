define(['Backbone'], function(api){

	/*****************************************************************************************************************

		To use:

			You will need to assign an ID onto the fields corresponding error message starting with: js-valid-
			With the ending being the same as the ID of the the field before it.

			To set a field to be required to be validated - you just need to use the HTML5 tag 'required' and the 
			javascript will detect this and do the relevent validation.

			To validate the type of field - for example email, telephone, text - you will need to specify this in the
			inputs type, which also makes the form HTML5 ready.

			So the final HTML example will be that of below:
			
				<input type="text" name="post[name]" id="name" required="true"/>
				<span id="js-valid-name">Name must not be empty</span>

				<input type="tel" name="post[tel]" id="tel" required="true"/>
				<span id="js-valid-tel">Tel must not be empty</span>

				<input type="Email" name="post[Email]" id="Email" required="true"/>
				<span id="js-valid-Email">Email must be valid</span>

			The submit button will need the class of 'js-submit button' - for it to trigger any validation.

	*/

	return Backbone.View.extend({

        initialize: function(){

        	this.form_element = '#js-form';
            this.form = $(this.form_element);
            this.error = false;
        },
        
        el: $('body'),
        
        events: {
        	'click .js-submit-form' : 'process_form'
        },

        /**
         * Action to process the form, and do all validations
         * and submittions of the actual form
         */
        process_form: function(e) {

        	var target = $(e.target);

        	// If the submit button has a data-form attribute
        	// we action a different form - this is to allow multiple
        	// forms on one page
        	if (!!target.attr('data-form')) {
        		this.form_element = '#'+target.attr('data-form');
        	}
        	
        	this.validate_fields();

        	if (this.error) {
        		e.preventDefault();
        	} else {
        		$("html, body").animate({ scrollTop: this.form.offset().top }, 300);
        	}

            e.preventDefault();
        },

        /**
         * Loop through the assocaited forms inputs
         * and trigger to validate each individual input
         */
        validate_fields: function() {
            console.log($(this.form_element));
        	$(this.form_element+' input').each(_.bind(function(input, item) {

	        	this.handle_field(item);

        	}, this));

        },

        /**
         * Handles a given input object and validates
         * the field against what type of field it is.
         *
         * This sets the object property this.error to the result
         * of the validation
         * 
         * @param object item
         */
        handle_field: function(item) {

        	var input = $(item);

        	if (!!input.attr('required')) {

        		// Relies on the input having the id set - to target the correct error message
        		var error_message = $('#js-valid-'+input.attr('id'));
        		var value = input.val();
        		var valid = true;

        		// First check the field is never empty
        		valid = this.validate_empty(value);

        		switch (input.attr('type')) {
        			case 'email':
        				valid = this.validate_email(value);
        			break;

        			case 'tel':
        				valid = this.validate_number(value);
        			break;

        			case 'checkbox':
        				valid = this.validate_checkbox(input);
        			break;
        		}

        		if (!valid) {
        			error_message.show();
        		} else {
        			error_message.hide();
        		}

        	}
        },

        /**
         * Validate that a field is not empty
         *
         * @param string value - the value of the input
         * @return boolean
         */
        validate_empty: function(value) {
        	return (value !== "");
        },

        /**
         * Validates whether a checkbox field has been ticked
         *
         * @param object input
         * @return boolean
         */
        validate_checkbox: function(input) {
        	return (input.attr('checked') == 'checked');
        },

        /**
         * Validates that a field value is a number
         *
         * @param string value
         * @return boolean
         */
        validate_number: function(value) {
        	return isNaN(value);
        },

        /**
         * Validates a field against a email valid regex 
         *
         * @param string value
         * @return boolean
         */
        validate_email: function(value) {
            return /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(value);
        }

    });

});