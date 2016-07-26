$(document).ready(function() {

  $("#signup").validate({  
   //set required fields  
    rules: {            
      username: {
        required: true,
        minlength: 4
      },    
      email: {
            required: true,
            email: true
      },      
      password: {
            required: true,
            minlength: 8
      },
      confirm_password: {
        required: true,
        minlength: 8,
        equalTo: "#password"
      }
    },
    //set error messages
    messages: {          
      username: {
        required: "Please provide a username.",
        minlength: "Your username must be at least 4 characters long."
      },  
      email: {
        required: "Please provide an email address.",
        email: "Please enter a valid email address."
      }, 
      password: {
        required: "Please provide a password.",
        minlength: "Your password must be at least 8 characters long."
      },
      confirm_password: {
        required: "Please provide a password.",
        minlength: "Your password must be at least 8 characters long.",
        equalTo: "Please enter the same password as above."
      }               
    },        
    errorPlacement: function ( error, element ) {
          // Add the `help-block` class to the error element
          error.addClass( "help-block" );
          error.insertAfter( element );          
        },
    //toggle error and success css styles
    highlight: function(element) {
        $(element).parent().addClass("has-error");
    },
    unhighlight: function(element) {
        $(element).parent().removeClass("has-error");
    }            
   });

  $("#login").validate({  
     //set required fields  
      rules: {            
        username: "required",          
        password: "required"
      },
      //set error messages
      messages: {         
        username: "Please enter your username.",   
        password: "Please provide your password."
      },        
      errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");
            error.insertAfter(element);          
          },
      //toggle error and success css styles
      highlight: function(element) {
          $(element).parent().addClass("has-error");
      },
      unhighlight: function(element) {
          $(element).parent().removeClass("has-error");
      }            
    });

    $("#profile").validate({  
     //set required fields  
      rules: {            
        email: {
          required: true,
          email: true
        },          
        firstname: "required",
        lastname: "required",
        street: "required",
        city: "required",
        state: "required",
        zip: "required"
      },
      //set error messages
      messages: {
        firstname: "Please enter your first name.",
        lastname: "Please enter your last name.",     
        email: "Please enter your email address.",   
        street: "Please enter your street address.",
        city: "Please enter your city.",
        state: "Please select your state.",
        zip: "Please enter your zipcode."            
      },        
      errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.insertAfter( element );          
          },
      //toggle error and success css styles
      highlight: function(element) {
          $(element).parent().addClass("has-error");
      },
      unhighlight: function(element) {
          $(element).parent().removeClass("has-error");
      }            
    });

    $("#addclass, #modifyclass").validate({  
     //set required fields  
      rules: {            
        classname: "required",
        startdate: {
          required: true,
          dateISO: true
        },
        enddate: {
          required: true,
          dateISO: true
        },        
        price: {
          required: true,
          number: true
        },        
        location: "required",
        category: "required",
        desc: "required"
      },
      //set error messages
      messages: {
        classname: "Please enter a title.",
        startdate: "Please enter a start date. Format should be YYYY-MM-DD",     
        enddate: "Please enter an end date. Format should be YYYY-MM-DD",   
        price: {
          required: "Please enter a price.",
          number: "Please enter a numeric value."
        },   
        location: "Please select a location.",
        category: "Please select a category.",
        desc: "Please enter a description."   
      },        
      errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );             

            if (element.attr("name") == "price")
            {                
                $(".input-group").parent().addClass( "has-error" );
                error.insertAfter(element.parent(".input-group"));
            }
            else
            {
                error.insertAfter(element);
            }
              
          },
      
      //toggle error and success css styles
      highlight: function(element) {
          $(element).parent().addClass("has-error");
          $(".input-group").parent().addClass( "has-error" );
          
      },
      unhighlight: function(element) {
          $(element).parent().removeClass("has-error");
          $(".input-group").parent().removeClass( "has-error" );
      }            
    });

    $("#participant").validate({  
     //set required fields  
      rules: {            
        firstname: "required",
        lastname: "required",
        age: "required",
        grade: "required",
        location: "required"
      },
      //set error messages
      messages: {
        firstname: "Please enter a first name.",
        lastname: "Please enter a last name.",     
        age: "Please select an age",   
        grade: "Please select a grade.",
        location: "Please select a location.",
        desc: "Please enter a class description."   
      },        
      errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.insertAfter( element );          
          },
      //toggle error and success css styles
      highlight: function(element) {
          $(element).parent().addClass("has-error");
      },
      unhighlight: function(element) {
          $(element).parent().removeClass("has-error");
      }            
    });

    $("#reset").validate({  
   //set required fields  
    rules: {            
      username: "required",          
      email: "required"      
    },
    //set error messages
    messages: {          
      username: "Please provide your username.",   
      email: "Please provide an email address."                   
    },        
    errorPlacement: function ( error, element ) {
          // Add the `help-block` class to the error element
          error.addClass( "help-block" );
          error.insertAfter( element );          
        },
    //toggle error and success css styles
    highlight: function(element) {
        $(element).parent().addClass("has-error");
    },
    unhighlight: function(element) {
        $(element).parent().removeClass("has-error");
    }            
   });

    $("#chgpswd").validate({  
   //set required fields  
    rules: {            
      password: {
            required: true,
            minlength: 8
      },
      confirm_password: {
        required: true,
        minlength: 8,
        equalTo: "#password"
      }
    },
    //set error messages
    messages: {   
      password: {
        required: "Please provide a password.",
        minlength: "Your password must be at least 8 characters long."
      },
      confirm_password: {
        required: "Please provide a password.",
        minlength: "Your password must be at least 8 characters long.",
        equalTo: "Please enter the same password as above."
      }               
    },        
    errorPlacement: function ( error, element ) {
          // Add the `help-block` class to the error element
          error.addClass( "help-block" );
          error.insertAfter( element );          
        },
    //toggle error and success css styles
    highlight: function(element) {
        $(element).parent().addClass("has-error");
    },
    unhighlight: function(element) {
        $(element).parent().removeClass("has-error");
    }            
   });


  });



