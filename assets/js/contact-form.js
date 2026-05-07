/**
* Contact Form Handler
* Handles form submission and displays success/error messages
*/

(function() {
  "use strict";

  // Initialize EmailJS with your public key
  emailjs.init("sC-sT6eZUv0vJDb3c");
  
  // Wait for DOM to be loaded
  document.addEventListener('DOMContentLoaded', function() {
    
    // Get the contact form
    const contactForm = document.querySelector('.php-email-form');
    
    if (contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form elements
        const loadingMessage = contactForm.querySelector('.loading');
        const sentMessage = contactForm.querySelector('.sent-message');
        const submitButton = contactForm.querySelector('button[type="submit"]');
        
        // Show loading state
        if (loadingMessage) {
          loadingMessage.style.display = 'block';
        }
        if (sentMessage) {
          sentMessage.style.display = 'none';
        }
        
        // Disable submit button
        submitButton.disabled = true;
        
        // Get form data
        const formData = new FormData(contactForm);
        const formObject = {};
        formData.forEach((value, key) => {
          formObject[key] = value;
        });
        
        // Send email using EmailJS
        emailjs.send('mailumairrana', 'Contact-Us', formObject)
          .then(function(response) {
            // Hide loading state
            if (loadingMessage) {
              loadingMessage.style.display = 'none';
            }
            
            // Show success message
            if (sentMessage) {
              sentMessage.textContent = 'Thank you! Your message has been sent successfully. Our team will get back to you shortly.';
              sentMessage.style.display = 'block';
            }
            
            // Reset form
            contactForm.reset();
            
            // Re-enable submit button
            submitButton.disabled = false;
            
            // Hide success message after 5 seconds
            setTimeout(function() {
              if (sentMessage) {
                sentMessage.style.display = 'none';
              }
            }, 5000);
            
          }, function(error) {
            // Hide loading state
            if (loadingMessage) {
              loadingMessage.style.display = 'none';
            }
            
            // Show error message
            const errorMessage = contactForm.querySelector('.error-message');
            if (errorMessage) {
              errorMessage.textContent = 'Oops! There was an error sending your message. Please try again later.';
              errorMessage.style.display = 'block';
            }
            
            // Re-enable submit button
            submitButton.disabled = false;
            
            // Hide error message after 5 seconds
            setTimeout(function() {
              if (errorMessage) {
                errorMessage.style.display = 'none';
              }
            }, 5000);
          });
        
      });
    }
    
  });

})();
