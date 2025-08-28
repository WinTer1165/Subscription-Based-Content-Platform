$(document).ready(function () {
    const subscriptionPrices = {
        basic: "9.99",
        standard: "14.99",
        premium: "33.99",
    };

    let selectedPaymentMethod = '';

    // Check payment method
    $("#payment").on("change", function () {
        selectedPaymentMethod = $(this).val();
        if (selectedPaymentMethod === "paypal") {
            // Show the PayPal button and hide register button
            $("#paypal-button-container").show();
            $("#submit-button").hide();
            if (!$("#paypal-button-container").children().length) {
                renderPayPalButton();
            }
        } else {
            // Hide the PayPal button and show the register button
            $("#paypal-button-container").hide();
            $("#submit-button").show();
        }
    });

    // Rendering the PayPal button
    function renderPayPalButton() {
        paypal.Buttons({
            createOrder: function (data, actions) {
                const selectedSubscription = $('#subscription').val();
                const amount = subscriptionPrices[selectedSubscription] || '0.00';

                if (!selectedSubscription) {
                    alert("Please select a subscription level before proceeding with PayPal payment.");
                    return;
                }

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: "USD",
                            value: amount,
                        },
                    }],
                });
            },

            // Finalizing the transaction
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert("Payment successful by " + details.payer.name.given_name);
                    const formData = getFormData();

                    //PayPal payment details
                    formData.paymentDetails = {
                        orderID: data.orderID,
                        payerID: data.payerID,
                        paymentID: details.id,
                        status: details.status,
                        payerName: details.payer.name.given_name + ' ' + details.payer.name.surname,
                        payerEmail: details.payer.email_address
                    };

                    // Submitting the form data via AJAX
                    submitForm(formData);
                });
            },

            // Payment errors
            onError: function (err) {
                console.error("PayPal Checkout onError:", err);
                alert("An error occurred during the PayPal transaction. Please try again.");
            }
        }).render('#paypal-button-container');
    }

    // Form submission for non-PayPal payments
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        if (validateForm()) {
            if (selectedPaymentMethod === "paypal") {
                console.log("PayPal payment selected. Awaiting payment approval.");
            } else {
                const formData = getFormData();
                formData.paymentDetails = null;
                // Submit form data via AJAX
                submitForm(formData);
            }
        }
    });

    // Collecting form data
    function getFormData() {
        return {
            username: $("#username").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            fullName: $("#full-name").val(),
            phone: $("#phone").val(),
            subscription: $("#subscription").val(),
            paymentMethod: $("#payment").val(),
        };
    }

    // Submit form data via AJAX
    function submitForm(data) {
        $.ajax({
            url: '../php/register.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'login.html';
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert('An error occurred. Please try again.');
            }
        });
    }

    // form validation function
    function validateForm() {
        let isValid = true;

        // Username validation
        const username = document.getElementById('username');
        if (username.value.length < 3) {
            showError(username, 'Username must be at least 3 characters long');
            isValid = false;
        } else {
            clearError(username);
        }

        // Email validation
        const email = document.getElementById('email');
        if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        } else {
            clearError(email);
        }

        // Password validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm-password');
        if (password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters long');
            isValid = false;
        } else if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        } else {
            clearError(password);
            clearError(confirmPassword);
        }

        // Full name validation
        const fullName = document.getElementById('full-name');
        if (fullName.value.trim() === '') {
            showError(fullName, 'Full name is required');
            isValid = false;
        } else {
            clearError(fullName);
        }

        // Phone number validation
        const phone = document.getElementById('phone');
        if (!isValidPhone(phone.value)) {
            showError(phone, 'Please enter a valid 11-digit phone number');
            isValid = false;
        } else {
            clearError(phone);
        }

        // Subscription level validation
        const subscription = document.getElementById('subscription');
        if (subscription.value === '') {
            showError(subscription, 'Please select a subscription level');
            isValid = false;
        } else {
            clearError(subscription);
        }

        // Payment method validation
        const payment = document.getElementById('payment');
        if (payment.value === '') {
            showError(payment, 'Please select a payment method');
            isValid = false;
        } else {
            clearError(payment);
        }

        return isValid;
    }

    function showError(input, message) {
        const formGroup = input.parentElement;
        let errorElement = formGroup.querySelector('.error');

        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error';
            formGroup.appendChild(errorElement);
        }

        errorElement.textContent = message;
        input.classList.add('input-error');
    }

    function clearError(input) {
        const formGroup = input.parentElement;
        const errorElement = formGroup.querySelector('.error');

        if (errorElement) {
            formGroup.removeChild(errorElement);
        }

        input.classList.remove('input-error');
    }

    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    function isValidPhone(phone) {
        const re = /^[0-9]{11}$/;
        return re.test(phone);
    }
});

