function showValue() {

    var checkedValue = null;
    var inputElements = document.getElementsByClassName('messageCheckbox');
    
    
        var price = document.getElementById("price2");
        var select = document.getElementById("select2");
        if (select.checked) {
            price.classList.remove("d-none");
        } else {
            price.classList.add("d-none");
        }
    


}
