var username = document.forms['register-form']['username'];
var email = document.forms['register-form']['email'];
var password = document.forms['register-form']['password'];
var password_confirmation = document.forms['register-form']['passwordConfirmation'];

var username_error = document.getElementById('usernameError');
var email_error = document.getElementById('emailError');
var password_error = document.getElementById('passwordError');
var password_confirmation_error = document.getElementById('passwordConfirmationError');

username.addEventListener('blur', nameVerify, true);
email.addEventListener('blur', emailVerify, true);
password.addEventListener('blur', passwordVerify, true);


function Validate(){

    if(username.value == ''){
        username.style.border = '2px solid red';
        username_error.textContent = 'Username is required';
        username.focus();
        console.log('Username is required.');
        
        return false;
    }

    if(email.value == ''){
        email.style.border = '2px solid red';
        email_error.textContent = 'Email is required';
        email.focus();
        console.log('Email is required.');

        return false;
    }

    if(password.value.length < 6 || password.value == ''){
        password.style.border = '2px solid red';
        password_error.textContent = 'Password must be at least 6 characters.';
        password.focus();
        console.log('Password should be at least 6 characters long');

        return false;
    }

    if(password.value != password_confirmation.value){
        password.style.border = '2px solid red';
        password_confirmation.style.border = '2px solid red'
        password_confirmation_error.innerHTML = 'The passwords do not match';
        console.log('The passwords do not match.');

        return false;
    }

}

function nameVerify(){

    if(username.value != ''){
        username.style.border = 'none';
        username_error.innerHTML = '';
  
        return true;
    }
}

function emailVerify(){

    if(username.value != ''){
        email.style.border = 'none';
        email_error.innerHTML = '';
  
        return true;
    }
}

function passwordVerify(){

    if(password.value != '' && password.value.length >= 6){
        password.style.border = 'none';
        password_error.innerHTML = '';
  
        return true;
    } 

    if(password.value === password_confirmation.value && password.value.length >= 6){
        password.style.border = 'none';
        password_confirmation.style.border = 'none';
        password_confirmation_error.innerHTML = '';

        return true;
    }
}
