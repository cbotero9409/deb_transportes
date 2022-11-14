function formValidation()
{
    var uname = document.registration.name;
    var uemail = document.registration.email;
    var passid = document.registration.password;
    var uid = document.registration.user;
    var umsex = document.registration.radio1;
    var ufsex = document.registration.radio1;

    if (allLetter(uname))
    {
        if (ValidateEmail(uemail))
        {
            if (password_validation(passid))
            {
                if (user_validation(uid))
                {
                    if (validsex(umsex, ufsex))
                    {
                    }
                }
            }
        }
        return true;
    } else {
        return false;
    }
}

function allLetter(uname)
{
    var letters = /^[A-Za-z]+$/;
    if (uname.value.match(letters))
    {
        uname.className = "form-control pgtion is-valid";
        document.getElementById('userfeed').className = 'valid-feedback d-block texInval';
        document.getElementById('userfeed').innerHTML = 'Nombre válido';
        return true;
    } else
    {
        uname.className = "form-control pgtion is-invalid";
        document.getElementById('userfeed').className = 'invalid-feedback d-block texInval';
        document.getElementById('userfeed').innerHTML = 'Nombre inválido';
        uname.focus();
        return false;
    }
}
function ValidateEmail(uemail)
{
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (uemail.value.match(mailformat))
    {
        uemail.className = "form-control pgtion is-valid";
        document.getElementById('mailfeed').className = 'valid-feedback d-block texInval';
        document.getElementById('mailfeed').innerHTML = 'Correo válido';
        return true;
    } else
    {
        uemail.className = "form-control pgtion is-invalid";
        document.getElementById('mailfeed').className = 'invalid-feedback d-block texInval';
        document.getElementById('mailfeed').innerHTML = 'Correo inválido';
        uemail.focus();
        return false;
    }
}

function password_validation(passid)
{
    var passid_len = passid.value.length;
    var lowerCaseLetters = /[a-z]/g;
    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;
    var specialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

    if (passid_len >= 6)
    {
        if (passid.value.match(specialChars))
        {
            if (passid.value.match(lowerCaseLetters))
            {
                if (passid.value.match(upperCaseLetters))
                {
                    if (passid.value.match(numbers))
                    {
                        passid.className = "form-control pgtion is-valid";
                        document.getElementById('passfeed').className = 'valid-feedback d-block texInval';
                        document.getElementById('passfeed').innerHTML = 'Contraseña válido';
                        return true;
                    }
                }
            }
        }
    } else
    {
        passid.className = "form-control pgtion is-invalid";
        document.getElementById('passfeed').className = 'invalid-feedback d-block texInval';
        document.getElementById('passfeed').innerHTML = 'Ingrese una contraseña válida';
        passid.focus();
        return false;
    }

}

function user_validation(uid)
{
    var uid_len = uid.value.length;
    if (uid_len === 0 || uid_len >= 12 || uid_len < 5)
    {
        uid.className = "form-control pgtion is-invalid";
        document.getElementById('idfeed').className = 'invalid-feedback d-block texInval';
        document.getElementById('idfeed').innerHTML = 'Usuario inválido';
        uid.focus();
        return false;
    } else
    {
        uid.className = "form-control pgtion is-valid";
        document.getElementById('idfeed').className = 'valid-feedback d-block texInval';
        document.getElementById('idfeed').innerHTML = 'Usuario válido';
        return true;
    }

}

function validsex(umsex, ufsex)
{
    x = 0;

    if (umsex.checked)
    {
        x++;
    }
    if (ufsex.checked)
    {
        x++;
    }
    if (x === 0)
    {
        return false;
    } else
    {
        return true;
    }
}
