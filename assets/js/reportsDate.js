var date = new Date(), y = date.getFullYear(), m = date.getMonth();
var firstDay = new Date(y, m - 1, 1).toISOString().slice(0, 10);
var lastDay = new Date(y, m, 0).toISOString().slice(0, 10);
let today = new Date().toISOString().slice(0, 10);
var start_date = document.excel.start_date;
var end_date = document.excel.end_date;
var excel_form = document.excel;
document.excel.start_date.value = firstDay;
document.excel.end_date.value = lastDay;
document.getElementById('start_date').setAttribute("max", today);
document.getElementById('end_date').setAttribute("max", today);


function startDateCheck() {
    if (start_date.value < end_date.value) {
        document.getElementById('start_date_feed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('start_date_feed').innerHTML = '';
        document.getElementById('end_date_feed').innerHTML = '';
        return true;
    } else
    {
        document.getElementById('start_date_feed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('start_date_feed').innerHTML = 'Fecha inicial mayor a la fecha final';
        return false;
    }
}

function endDateCheck() {
    if (start_date.value < end_date.value) {
        document.getElementById('end_date_feed').className = 'valid-feedback d-block texInval margin_valid';
        document.getElementById('end_date_feed').innerHTML = '';
        document.getElementById('start_date_feed').innerHTML = '';
        return true;
    } else
    {
        document.getElementById('end_date_feed').className = 'invalid-feedback d-block texInval margin_valid';
        document.getElementById('end_date_feed').innerHTML = 'Fecha final menor a la fecha inicial';
        return false;
    }
}

function formValidation() {
    const form = document.getElementById("reports_form");
    form.action = '../back_end/excel.php?report=gral';
    let end_date_check = endDateCheck();
    let start_date_check = startDateCheck();
    if (end_date_check === true && start_date_check === true) {
        excel_form.submit();
        return true;
    } else {
        return false;
    }
}    

function formValidation2() {
    const form = document.getElementById("reports_form");
    form.action = '../back_end/excel.php?report=time';
    let end_date_check = endDateCheck();
    let start_date_check = startDateCheck();
    if (end_date_check === true && start_date_check === true) {
        excel_form.submit();
        return true;
    } else {
        return false;
    }
}  