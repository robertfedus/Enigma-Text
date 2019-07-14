var username = document.forms['login-form']['username'];
var password = document.forms['login-form']['password'];

var username_error = document.getElementById('usernameError');
var password_error = document.getElementById('passwordError');

username.addEventListener('blur', userVerify, true);
password.addEventListener('blur', passVerify, true);

function Validate(){

    if(username.value == ''){
        username.style.border = '2px solid red';
        username_error.textContent = 'Username is required';
        username.focus();
        console.log('Username is required.');

        return false;
    }

    if(password.value == ''){
        password.style.border = '2px solid red';
        password_error.textContent = 'Password is required';
        password.focus();
        console.log('Password is required.')

        return false;
    }
}

function userVerify(){
    if(username.value != ''){
        username.style.border = 'none';
        username_error.innerHTML = '';
  
        return true;
    }
}

function passVerify(){
    if(password.value != ''){
        password.style.border = 'none';
        password_error.innerHTML = '';

        return true;
    }
}
