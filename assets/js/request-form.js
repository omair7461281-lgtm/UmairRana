document.addEventListener('DOMContentLoaded', function () {
  // Initialize EmailJS
  (function() {
    emailjs.init("sC-sT6eZUv0vJDb3c");
  })();

  // Character counter for textarea
  const detailsTextarea = document.getElementById('details');
  const charCount = document.getElementById('charCount');

  if (detailsTextarea && charCount) {
    detailsTextarea.addEventListener('input', function () {
      const currentLength = this.value.length;
      charCount.textContent = currentLength;

      // Change color when approaching limit
      if (currentLength > 450) {
        charCount.style.color = '#dc3545';
      } else if (currentLength > 400) {
        charCount.style.color = '#ffc107';
      } else {
        charCount.style.color = '#6c757d';
      }
    });
  }

  // Phone number validation - only allow numbers
  const phoneInput = document.getElementById('phone');
  if (phoneInput) {
    phoneInput.addEventListener('input', function (e) {
      // Remove any non-numeric characters
      this.value = this.value.replace(/[^0-9]/g, '');

      // Real-time validation
      validateField(this);
    });

    // Prevent paste of non-numeric characters
    phoneInput.addEventListener('paste', function (e) {
      e.preventDefault();
      const pastedData = e.clipboardData.getData('text');
      const numericData = pastedData.replace(/[^0-9]/g, '');
      document.execCommand('insertText', false, numericData);
    });
  }

  // Real-time validation for all form fields
  function validateField(field) {
    const formGroup = field.parentElement;
    const fieldType = field.type || field.tagName.toLowerCase();
    let isValid = false;

    // Remove existing validation states
    formGroup.classList.remove('error', 'valid');

    // Validation logic based on field type and requirements
    if (field.hasAttribute('required') || field.id === 'business' || field.id === 'details' || field.id === 'name' || field.id === 'phone') {
      if (field.value.trim()) {
        if (field.type === 'email') {
          isValid = isValidEmail(field.value);
        } else {
          isValid = true;
        }
      }
    } else if (field.id === 'service') {
      isValid = field.value !== '';
    }

    // Add appropriate class
    if (isValid) {
      formGroup.classList.add('valid');
    } else if (field.value.trim() || field.value !== '') {
      // Only show error if user has entered something but it's invalid
      if (field.type === 'email' && field.value.trim()) {
        formGroup.classList.add('error');
      }
    }
  }

  // Add input event listeners to all form fields for real-time validation
  const formFields = document.querySelectorAll('input, select, textarea');
  formFields.forEach(field => {
    if (field.id !== 'countryCode') { // Skip country code as it has special handling
      field.addEventListener('input', function () {
        validateField(this);
      });

      field.addEventListener('blur', function () {
        validateField(this);
      });
    }
  });

  // Country code search functionality
  const countryCode = document.getElementById('countryCode');
  const countryCodeValue = document.getElementById('countryCodeValue');
  const countryDropdown = document.getElementById('countryDropdown');

  // Comprehensive country list with 3-letter codes
  const countries = [
    { code: '+93', name: 'AFG' },
    { code: '+355', name: 'ALB' },
    { code: '+213', name: 'DZA' },
    { code: '+376', name: 'AND' },
    { code: '+244', name: 'AGO' },
    { code: '+54', name: 'ARG' },
    { code: '+374', name: 'ARM' },
    { code: '+61', name: 'AUS' },
    { code: '+43', name: 'AUT' },
    { code: '+994', name: 'AZE' },
    { code: '+973', name: 'BHR' },
    { code: '+880', name: 'BGD' },
    { code: '+375', name: 'BLR' },
    { code: '+32', name: 'BEL' },
    { code: '+501', name: 'BLZ' },
    { code: '+229', name: 'BEN' },
    { code: '+975', name: 'BTN' },
    { code: '+591', name: 'BOL' },
    { code: '+387', name: 'BIH' },
    { code: '+267', name: 'BWA' },
    { code: '+55', name: 'BRA' },
    { code: '+673', name: 'BRN' },
    { code: '+359', name: 'BGR' },
    { code: '+226', name: 'BFA' },
    { code: '+257', name: 'BDI' },
    { code: '+855', name: 'KHM' },
    { code: '+237', name: 'CMR' },
    { code: '+1', name: 'CAN' },
    { code: '+238', name: 'CPV' },
    { code: '+236', name: 'CAF' },
    { code: '+235', name: 'TCD' },
    { code: '+56', name: 'CHL' },
    { code: '+86', name: 'CHN' },
    { code: '+57', name: 'COL' },
    { code: '+269', name: 'COM' },
    { code: '+242', name: 'COG' },
    { code: '+243', name: 'COD' },
    { code: '+506', name: 'CRI' },
    { code: '+385', name: 'HRV' },
    { code: '+53', name: 'CUB' },
    { code: '+357', name: 'CYP' },
    { code: '+420', name: 'CZE' },
    { code: '+45', name: 'DNK' },
    { code: '+253', name: 'DJI' },
    { code: '+670', name: 'TLS' },
    { code: '+593', name: 'ECU' },
    { code: '+20', name: 'EGY' },
    { code: '+503', name: 'SLV' },
    { code: '+240', name: 'GNQ' },
    { code: '+291', name: 'ERI' },
    { code: '+372', name: 'EST' },
    { code: '+251', name: 'ETH' },
    { code: '+679', name: 'FJI' },
    { code: '+358', name: 'FIN' },
    { code: '+33', name: 'FRA' },
    { code: '+241', name: 'GAB' },
    { code: '+220', name: 'GMB' },
    { code: '+995', name: 'GEO' },
    { code: '+49', name: 'DEU' },
    { code: '+233', name: 'GHA' },
    { code: '+30', name: 'GRC' },
    { code: '+502', name: 'GTM' },
    { code: '+224', name: 'GIN' },
    { code: '+245', name: 'GNB' },
    { code: '+592', name: 'GUY' },
    { code: '+509', name: 'HTI' },
    { code: '+504', name: 'HND' },
    { code: '+852', name: 'HKG' },
    { code: '+36', name: 'HUN' },
    { code: '+354', name: 'ISL' },
    { code: '+91', name: 'IND' },
    { code: '+62', name: 'IDN' },
    { code: '+98', name: 'IRN' },
    { code: '+964', name: 'IRQ' },
    { code: '+353', name: 'IRL' },
    { code: '+972', name: 'ISR' },
    { code: '+39', name: 'ITA' },
    { code: '+225', name: 'CIV' },
    { code: '+81', name: 'JPN' },
    { code: '+962', name: 'JOR' },
    { code: '+7', name: 'KAZ' },
    { code: '+254', name: 'KEN' },
    { code: '+686', name: 'KIR' },
    { code: '+965', name: 'KWT' },
    { code: '+996', name: 'KGZ' },
    { code: '+856', name: 'LAO' },
    { code: '+371', name: 'LVA' },
    { code: '+961', name: 'LBN' },
    { code: '+266', name: 'LSO' },
    { code: '+231', name: 'LBR' },
    { code: '+218', name: 'LBY' },
    { code: '+423', name: 'LIE' },
    { code: '+370', name: 'LTU' },
    { code: '+352', name: 'LUX' },
    { code: '+853', name: 'MAC' },
    { code: '+389', name: 'MKD' },
    { code: '+261', name: 'MDG' },
    { code: '+265', name: 'MWI' },
    { code: '+60', name: 'MYS' },
    { code: '+960', name: 'MDV' },
    { code: '+223', name: 'MLI' },
    { code: '+356', name: 'MLT' },
    { code: '+692', name: 'MHL' },
    { code: '+222', name: 'MRT' },
    { code: '+230', name: 'MUS' },
    { code: '+52', name: 'MEX' },
    { code: '+691', name: 'FSM' },
    { code: '+373', name: 'MDA' },
    { code: '+377', name: 'MCO' },
    { code: '+976', name: 'MNG' },
    { code: '+382', name: 'MNE' },
    { code: '+212', name: 'MAR' },
    { code: '+258', name: 'MOZ' },
    { code: '+95', name: 'MMR' },
    { code: '+264', name: 'NAM' },
    { code: '+674', name: 'NRU' },
    { code: '+977', name: 'NPL' },
    { code: '+31', name: 'NLD' },
    { code: '+64', name: 'NZL' },
    { code: '+505', name: 'NIC' },
    { code: '+227', name: 'NER' },
    { code: '+234', name: 'NGA' },
    { code: '+683', name: 'NIU' },
    { code: '+47', name: 'NOR' },
    { code: '+968', name: 'OMN' },
    { code: '+92', name: 'PAK' },
    { code: '+680', name: 'PLW' },
    { code: '+507', name: 'PAN' },
    { code: '+675', name: 'PNG' },
    { code: '+595', name: 'PRY' },
    { code: '+51', name: 'PER' },
    { code: '+63', name: 'PHL' },
    { code: '+48', name: 'POL' },
    { code: '+351', name: 'PRT' },
    { code: '+974', name: 'QAT' },
    { code: '+40', name: 'ROU' },
    { code: '+7', name: 'RUS' },
    { code: '+250', name: 'RWA' },
    { code: '+685', name: 'WSM' },
    { code: '+378', name: 'SMR' },
    { code: '+966', name: 'SAU' },
    { code: '+221', name: 'SEN' },
    { code: '+381', name: 'SRB' },
    { code: '+248', name: 'SYC' },
    { code: '+232', name: 'SLE' },
    { code: '+65', name: 'SGP' },
    { code: '+421', name: 'SVK' },
    { code: '+386', name: 'SVN' },
    { code: '+27', name: 'ZAF' },
    { code: '+82', name: 'KOR' },
    { code: '+34', name: 'ESP' },
    { code: '+94', name: 'LKA' },
    { code: '+249', name: 'SDN' },
    { code: '+597', name: 'SUR' },
    { code: '+46', name: 'SWE' },
    { code: '+41', name: 'CHE' },
    { code: '+963', name: 'SYR' },
    { code: '+886', name: 'TWN' },
    { code: '+992', name: 'TJK' },
    { code: '+255', name: 'TZA' },
    { code: '+66', name: 'THA' },
    { code: '+228', name: 'TGO' },
    { code: '+676', name: 'TON' },
    { code: '+216', name: 'TUN' },
    { code: '+90', name: 'TUR' },
    { code: '+993', name: 'TKM' },
    { code: '+688', name: 'TUV' },
    { code: '+256', name: 'UGA' },
    { code: '+380', name: 'UKR' },
    { code: '+971', name: 'ARE' },
    { code: '+44', name: 'GBR' },
    { code: '+1', name: 'USA' },
    { code: '+598', name: 'URY' },
    { code: '+998', name: 'UZB' },
    { code: '+678', name: 'VUT' },
    { code: '+58', name: 'VEN' },
    { code: '+84', name: 'VNM' },
    { code: '+967', name: 'YEM' },
    { code: '+260', name: 'ZMB' },
    { code: '+263', name: 'ZWE' }
  ];

  // Initialize country dropdown
  function populateCountryDropdown(searchTerm = '') {
    const filteredCountries = countries.filter(country =>
      country.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      country.code.includes(searchTerm)
    );

    countryDropdown.innerHTML = '';

    if (filteredCountries.length === 0) {
      countryDropdown.innerHTML = '<div class="country-item no-results">No countries found</div>';
      return;
    }

    filteredCountries.forEach(country => {
      const item = document.createElement('div');
      item.className = 'country-item';
      item.textContent = `${country.name} ${country.code}`;
      item.onclick = () => selectCountry(country.code, country.name);
      countryDropdown.appendChild(item);
    });
  }

  function selectCountry(code, name) {
    countryCode.value = `${name} ${code}`;
    countryCodeValue.value = code;
    countryDropdown.style.display = 'none';
  }

  // Event listeners for country search
  if (countryCode && countryDropdown) {
    countryCode.addEventListener('focus', () => {
      // Clear the input when focused for searching
      countryCode.value = '';
      populateCountryDropdown();
      countryDropdown.style.display = 'block';
    });

    countryCode.addEventListener('input', (e) => {
      populateCountryDropdown(e.target.value);
      countryDropdown.style.display = 'block';
    });

    countryCode.addEventListener('blur', () => {
      setTimeout(() => {
        // If no selection was made, restore PAK as default
        if (countryCodeValue.value === '+92' && countryCode.value === '') {
          countryCode.value = 'PAK +92';
        }
        countryDropdown.style.display = 'none';
      }, 200);
    });

    // Click outside to close
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.country-code-wrapper')) {
        // If no selection was made, restore PAK as default
        if (countryCodeValue.value === '+92' && countryCode.value === '') {
          countryCode.value = 'PAK +92';
        }
        countryDropdown.style.display = 'none';
      }
    });
  }

  // Submit button click handler
  const submitBtn = document.querySelector('.btn-submit');
  if (submitBtn) {
    submitBtn.addEventListener('click', function (e) {
      e.preventDefault();
      
      // Show loading state
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
      
      // Get form data
      const formData = {
        business: document.getElementById('business').value,
        reference: document.getElementById('reference').value || 'Not provided',
        service: document.getElementById('service').options[document.getElementById('service').selectedIndex].text,
        details: document.getElementById('details').value,
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('countryCodeValue').value + ' ' + document.getElementById('phone').value,
        company: document.getElementById('company').value || 'Not provided'
      };
      
      // Send email using EmailJS
      emailjs.send('service_b5mtjxr', 'template_u8ur2vd', formData)
        .then(function(response) {
          console.log('SUCCESS!', response.status, response.text);
          showNotification(`Your request has been submitted successfully. We'll get back to you within 24 hours.`, 'success');
          
          // Refresh page after 3 seconds
          setTimeout(() => {
            window.location.reload();
          }, 3000);
        }, function(error) {
          console.log('FAILED...', error);
          showNotification('Failed to send request. Please try again or contact us directly.', 'error');
          
          // Reset button state
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Submit Request <i class="bi bi-send"></i>';
        });
    });
  }

  // Notification function
  function showNotification(message, type) {
    // Remove existing notifications
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
      existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
        <span>${message}</span>
      </div>
    `;

    // Add notification styles if not already added
    if (!document.querySelector('#notification-styles')) {
      const style = document.createElement('style');
      style.id = 'notification-styles';
      style.textContent = `
        .notification {
          position: fixed;
          top: 20px;
          right: 20px;
          z-index: 9999;
          min-width: 300px;
          max-width: 400px;
          padding: 15px 20px;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          animation: slideIn 0.3s ease-out;
        }
        .notification.success {
          background: #28a745;
          color: white;
        }
        .notification.error {
          background: #dc3545;
          color: white;
        }
        .notification-content {
          display: flex;
          align-items: center;
          gap: 10px;
        }
        .notification i {
          font-size: 20px;
        }
        @keyframes slideIn {
          from {
            transform: translateX(100%);
            opacity: 0;
          }
          to {
            transform: translateX(0);
            opacity: 1;
          }
        }
      `;
      document.head.appendChild(style);
    }

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.style.animation = 'slideIn 0.3s ease-out reverse';
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 300);
    }, 5000);
  }
});

// Step navigation functions
function nextStep() {
  // Validate step 1
  const business = document.getElementById('business');
  const service = document.getElementById('service');
  const details = document.getElementById('details');

  let isValid = true;

  // Reset previous error and valid states
  document.querySelectorAll('.form-group').forEach(group => {
    group.classList.remove('error', 'valid');
  });

  // Validate required fields
  if (!business.value.trim()) {
    business.parentElement.classList.add('error');
    isValid = false;
  } else {
    business.parentElement.classList.add('valid');
  }

  if (!service.value) {
    service.parentElement.classList.add('error');
    isValid = false;
  } else {
    service.parentElement.classList.add('valid');
  }

  if (!details.value.trim()) {
    details.parentElement.classList.add('error');
    isValid = false;
  } else {
    details.parentElement.classList.add('valid');
  }

  if (isValid) {
    showStep(2);
  } else {
    // Scroll to first error
    const firstError = document.querySelector('.form-group.error');
    if (firstError) {
      firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
}

function prevStep() {
  const currentStep = document.querySelector('.form-step.active');
  const currentStepNum = parseInt(currentStep.id.replace('step', ''));
  showStep(currentStepNum - 1);
}

function reviewStep() {
  // Validate step 2
  const name = document.getElementById('name');
  const email = document.getElementById('email');
  const phone = document.getElementById('phone');

  let isValid = true;

  // Reset previous error and valid states
  document.querySelectorAll('.form-group').forEach(group => {
    group.classList.remove('error', 'valid');
  });

  // Validate required fields
  if (!name.value.trim()) {
    name.parentElement.classList.add('error');
    isValid = false;
  } else {
    name.parentElement.classList.add('valid');
  }

  if (!email.value.trim() || !isValidEmail(email.value)) {
    email.parentElement.classList.add('error');
    isValid = false;
  } else {
    email.parentElement.classList.add('valid');
  }

  if (!phone.value.trim()) {
    phone.parentElement.classList.add('error');
    isValid = false;
  } else {
    phone.parentElement.classList.add('valid');
  }

  if (isValid) {
    // Populate review information
    document.getElementById('review-business').textContent = document.getElementById('business').value;
    document.getElementById('review-reference').textContent = document.getElementById('reference').value || 'Not provided';
    document.getElementById('review-service').textContent = document.getElementById('service').options[document.getElementById('service').selectedIndex].text;
    document.getElementById('review-details').textContent = document.getElementById('details').value;
    document.getElementById('review-name').textContent = document.getElementById('name').value;
    document.getElementById('review-email').textContent = document.getElementById('email').value;
    const countryCode = document.getElementById('countryCodeValue').value;
    const phoneNumber = document.getElementById('phone').value;
    document.getElementById('review-phone').textContent = countryCode + ' ' + phoneNumber;
    document.getElementById('review-company').textContent = document.getElementById('company').value || 'Not provided';

    showStep(3);
  } else {
    // Scroll to first error
    const firstError = document.querySelector('.form-group.error');
    if (firstError) {
      firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
}

function showStep(stepNum) {
  // Hide all steps
  document.querySelectorAll('.form-step').forEach(step => {
    step.classList.remove('active');
  });

  // Show current step
  document.getElementById('step' + stepNum).classList.add('active');

  // Update step indicator
  const steps = document.querySelectorAll('.step');
  const stepText = document.querySelector('.step-text');

  steps.forEach((step, index) => {
    if (index < stepNum) {
      step.classList.add('active');
    } else {
      step.classList.remove('active');
    }
  });

  // Update step text
  if (stepNum === 1) {
    stepText.textContent = 'Project Information';
  } else if (stepNum === 2) {
    stepText.textContent = 'Contact Information';
  } else if (stepNum === 3) {
    stepText.textContent = 'Review Your Information';
  }

  // Scroll to top of form
  document.querySelector('.quote-form-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}
