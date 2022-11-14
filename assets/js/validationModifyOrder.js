function formValidation()
{
    var insured = document.orders_form.insured;
    var address = document.orders_form.address;
    var community = document.orders_form.community;
    var phone = document.orders_form.phone;
    var receive_date = document.orders_form.receive_date;
    var claim_number = document.orders_form.claim_number;
    var contact_name = document.orders_form.contact_name;
    var contact_phone= document.orders_form.contact_phone;
    var municipality = document.orders_form.municipality;
    var other_municipality = document.orders_form.other;
    var description = document.orders_form.description;


    if (insuredCheck(insured) && addressCheck(address)  && communityCheck(community) && phoneCheck(phone) && dateCheck(receive_date) && claimNumberCheck(claim_number))
    {
        if (contactNameCheck(contact_name) && contactPhoneCheck(contact_phone) && (municipalityCheck(municipality) || otherMunicipalityCheck(other_municipality)) && descriptionCheck(description))
        {
            return true;
        } else { return false;}
    } else { return false;}
}

function insuredCheck(insured)
{
    var insured_format = /^[\w\s\ñ#-]+$/;
    if (insured.value.match(insured_format) && insured.value.length <= 50 && insured.value !== '')
    {
        insured.className = "form-control pgtion is-valid";
        document.getElementById('insuredfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('insuredfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        insured.className = "form-control pgtion is-invalid";
        document.getElementById('insuredfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('insuredfeed').innerHTML = 'Introduzca un nombre válido';
        insured.focus();
        return false;
    }
}

function addressCheck(address)
{
    var address_format = /^[\w\s\ñ().#-]+$/;
    if (address.value.match(address_format) && address.value.length <= 100 && address.value !== '')
    {
        address.className = "form-control pgtion is-valid";
        document.getElementById('addressfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('addressfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        address.className = "form-control pgtion is-invalid";
        document.getElementById('addressfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('addressfeed').innerHTML = 'Introduzca una dirección válida';
        address.focus();
        return false;
    }
}

function communityCheck(community)
{
    var community_format = /^[\a-zA-Z\s\ñ\á-ú\Á-Ú]+$/;
    if (community.value.match(community_format) && community.value.length <= 25 && community.value !== '')
    {
        community.className = "form-control pgtion is-valid";
        document.getElementById('communityfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('communityfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        community.className = "form-control pgtion is-invalid";
        document.getElementById('communityfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('communityfeed').innerHTML = 'Introduzca un barrio válido';
        community.focus();
        return false;
    }
}

function phoneCheck(phone)
{
    var phone_format = /^[\d\#+-]+$/;
    if (phone.value.match(phone_format) && phone.value.length <= 20 && phone.value !== '')
    {
        phone.className = "form-control pgtion is-valid";
        document.getElementById('phonefeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('phonefeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        phone.className = "form-control pgtion is-invalid";
        document.getElementById('phonefeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('phonefeed').innerHTML = 'Introduzca un número telefónico válido';
        phone.focus();
        return false;
    }
}

/* Asigning the default date in the required format "YYYY-MM-DD"" as the day we fill the orders form. Also giving max attribute to input to lock later dates */
let today1 = new Date().toISOString().slice(0, 10);
document.getElementById('receive_date').setAttribute("max", today1);
/*****************************************************************************************************/
function dateCheck(date)
{    
    if (date.value.length === 10 && date.value <= today1 && date.value !== '')
    {
        date.className = "form-control pgtion is-valid";
        document.getElementById('datefeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('datefeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        date.className = "form-control pgtion is-invalid";
        document.getElementById('datefeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('datefeed').innerHTML = 'Introduzca una fecha válida';
        date.focus();
        return false;
    }
}

function claimNumberCheck(claim)
{
    var claim_format= /^[\d]+?/;
    if (claim.value.match(claim_format) && claim.value.length <= 14 && claim.value !== '')
    {
        claim.className = "form-control pgtion is-valid";
        document.getElementById('claimnumberfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('claimnumberfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        claim.className = "form-control pgtion is-invalid";
        document.getElementById('claimnumberfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('claimnumberfeed').innerHTML = 'Introduzca un número de reclamación válido';
        claim.focus();
        return false;
    }
}

function contactNameCheck(contact)
{
    var contact_format = /^[\a-zA-Z\s\ñ]+$/;
    if (contact.value.match(contact_format) && contact.value.length <= 50 && contact.value !== '')
    {
        contact.className = "form-control pgtion is-valid";
        document.getElementById('contactnamefeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('contactnamefeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        contact.className = "form-control pgtion is-invalid";
        document.getElementById('contactnamefeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('contactnamefeed').innerHTML = 'Introduzca un nombre válido';
        contact.focus();
        return false;
    }
}

function contactPhoneCheck(phone)
{
    var phone_format = /^[\d\#+-]+$/;
    if (phone.value.match(phone_format) && phone.value.length <= 20 && phone.value !== '')
    {
        phone.className = "form-control pgtion is-valid";
        document.getElementById('contactphonefeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('contactphonefeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        phone.className = "form-control pgtion is-invalid";
        document.getElementById('contactphonefeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('contactphonefeed').innerHTML = 'Introduzca un número telefónico válido';
        phone.focus();
        return false;
    }
}

function municipalityCheck(munic)
{
    var other = document.getElementById('show_other');
    if (munic.value === 'Otro') {
        other.classList.remove("d-none");
        document.getElementById('municipalityfeed').innerHTML = '';
        munic.className = "form-control pgtion";
        return false;
    } else {
        other.classList.add("d-none");
        
        var munic_format = /^[\a-zA-Z\s\ñ\á-ú\Á-Ú]+$/;
        if (munic.value.match(munic_format) && munic.value.length <= 30 && munic.value !== '')
        {
            munic.className = "form-control pgtion is-valid";
            document.getElementById('municipalityfeed').className = 'valid-feedback d-block texInval margin_valid';
            document.getElementById('municipalityfeed').innerHTML = 'Dato válido';
            return true;
        } else
        {
            munic.className = "form-control pgtion is-invalid";
            document.getElementById('municipalityfeed').className = 'invalid-feedback d-block texInval margin_valid';
            document.getElementById('municipalityfeed').innerHTML = 'Escoja un municipio válido';
            munic.focus();
            return false;
        }
    }
}

function otherMunicipalityCheck(other)
{
    var other_format = /^[\a-zA-Z\s\ñ\á-ú\Á-Ú]+$/;
    document.getElementById('other').setAttribute("required", '');
    if (other.value.match(other_format) && other.value.length <= 30 && other.value !== '')
    {
        other.className = "form-control pgtion is-valid";
        document.getElementById('otherfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('otherfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        other.className = "form-control pgtion is-invalid";
        document.getElementById('otherfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('otherfeed').innerHTML = 'Inserte un municipio válido';
        other.focus();
        return false;
    }

}

function descriptionCheck(description)
{
    if (description.value.length <= 2000 && description.value !== '')
    {
        description.className = "form-control pgtion is-valid";
        document.getElementById('descriptionfeed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('descriptionfeed').innerHTML = 'Dato válido';
        return true;
    } else
    {
        description.className = "form-control pgtion is-invalid";
        document.getElementById('descriptionfeed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('descriptionfeed').innerHTML = 'Introduzca una descripción';
        description.focus();
        return false;
    }
}