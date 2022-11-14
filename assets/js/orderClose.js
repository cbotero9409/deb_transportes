/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showDate(status) {
    var date = document.getElementById("show_date");
    if (status.value !== "Pendiente") {
        let today = new Date().toISOString().slice(0, 10);        
        date.classList.remove("d-none");
        document.getElementById('date').setAttribute("max", today);
        document.getElementById('date').setAttribute("value", today);
    } else {
        date.classList.add("d-none");
    }
}
