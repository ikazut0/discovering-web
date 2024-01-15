let feature_s_form = document.getElementById('feature_s_form');

feature_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_feature();
});

function add_feature() {
let data = new FormData();
data.append('feature_name', feature_s_form.elements['feature_name'].value);
data.append('add_feature', '');

let xhr = new XMLHttpRequest();
xhr.open("POST", "ajax/facilities.php", true);

xhr.onload = function () {
if (this.responseText == 1) {
    alert('success', 'ЗМІНИ ЗБЕРЕЖЕНО! ФОТО УСПІШНО ЗАВАНТАЖЕНЕ!');
    feature_s_form.elements['feature_name'].value = '';
    get_features();
    $('#feature-s').modal('hide'); // Close the modal after successful data addition
} 
else if(this.responseText == 'room_added') {
    alert('error', 'ЗМІНИ TEST! ФОТО TEST ЗАВАНТАЖЕНЕ!');   
}
else {
    alert('error', 'ЗМІНИ TEST! ФОТО TEST ЗАВАНТАЖЕНЕ!');
}
};

xhr.send(data);
}


function get_features() {
let xhr = new XMLHttpRequest();
xhr.open("POST", "ajax/facilities.php", true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

xhr.onload = function () {
document.getElementById('features-data').innerHTML = this.responseText;
}
xhr.send('get_features');
}

function rem_feature(val) {
let xhr = new XMLHttpRequest();
xhr.open("POST", "ajax/facilities.php", true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

xhr.onload = function () {
if (this.responseText == 1) {
    alert('success', 'ЗМІНИ ЗБЕРЕЖЕНО! ФОТО УСПІШНО ВИДАЛЕНЕ!');
    get_features();
} else {
    alert('error', 'ПОМИЛКА ВИДАЛЕННЯ ЗОБРАЖЕННЯ. СЕРВЕР НЕ ВІДПОВІДАЄ!');
}
}
xhr.send('rem_feature=' + val);
}

window.onload = function() {
get_features();
}