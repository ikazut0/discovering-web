let general_data, contacts_data;

let general_s_form = document.getElementById('general_s_form');
let site_title_inp = document.getElementById('site_title_inp');
let contacts_s_form = document.getElementById('contacts_s_form');
let carousel_s_form = document.getElementById('carousel_s_form');
let carousel_picture_inp = document.getElementById('carousel_picture_inp');

function get_general() {
    let site_title = document.getElementById('site_title');
    let shutdown_toggle = document.getElementById('shutdown-toggle');
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        general_data = JSON.parse(this.responseText);
        site_title.innerText = general_data.site_title;
        site_title_inp.value = general_data.site_title;

        shutdown_toggle.checked = general_data.site_shutdown === 1;
        shutdown_toggle.value = general_data.site_shutdown;
    };

    xhr.send('get_general');
}

general_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    upd_general(site_title_inp.value);
});

function upd_general(site_title_val) {
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        var myModal = document.getElementById('general-s');
        var modal = bootstrap.Modal.getInstance(myModal);

        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'ЗМІНИ ДО ДАНИХ УСПІШНО ВИКОНАНІ!');
            get_general();
        } else {
            alert('error', 'ПОМИЛКА ПІД ЧАС ПРОЦЕСУ ЗБЕРЕЖЕННЯ ДАНИХ!');
        }
    };

    xhr.send('site_title=' + site_title_val + '&upd_general');
}

function upd_shutdown(val) {
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'НА РАЗІ САЙТ ОТРИМАВ СТАТУС : ' + (val == 1 ? '🟢 ВКЛ.' : '🔴 ВИКЛ.'));
            get_general();
        } else {
            alert('error', 'ПОМИЛКА ПІД ЧАС ПРОЦЕСУ ЗМІНИ СТАТУСУ РОБОТИ САЙТУ!');
        }
    };

    xhr.send('upd_shutdown=' + val);
}

function get_contacts() {
    let contacts_p_id = ['contact_address', 'contact_phone_one', 'contact_phone_two', 'contact_email', 'contact_facebook', 'contact_instagram', 'contact_iframe'];
    let iframe = document.getElementById('contact_iframe');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        contacts_data = JSON.parse(this.responseText);
        contacts_data = Object.values(contacts_data);

        for (let i = 0; i < contacts_p_id.length; i++) {
            document.getElementById(contacts_p_id[i]).innerText = contacts_data[i + 1];
        }
        iframe.src = contacts_data[7];
        contacts_inp(contacts_data);
    };

    xhr.send('get_contacts');
}

function contacts_inp(data) {
    let contacts_inp_id = ['contact_address_inp', 'contact_phone_one_inp', 'contact_phone_two_inp', 'contact_email_inp', 'contact_facebook_inp', 'contact_instagram_inp', 'contact_iframe_inp'];
    for (let i = 0; i < contacts_inp_id.length; i++) {
        document.getElementById(contacts_inp_id[i]).value = data[i + 1];
    }
}

contacts_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    upd_contacts();
});

function upd_contacts() {
    let index = ['contact_address', 'contact_phone_one', 'contact_phone_two', 'contact_email', 'contact_facebook', 'contact_instagram', 'contact_iframe'];

    let contacts_inp_id = ['contact_address_inp', 'contact_phone_one_inp', 'contact_phone_two_inp', 'contact_email_inp', 'contact_facebook_inp', 'contact_instagram_inp', 'contact_iframe_inp'];

    let data_str = "";

    for (let i = 0; i < index.length; i++) {
        data_str += index[i] + "=" + document.getElementById(contacts_inp_id[i]).value + '&';
    }
    data_str += "upd_contacts";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        var myModal = document.getElementById('contacts-s');
        var modal = bootstrap.Modal.getInstance(myModal);

        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'ЗМІНИ ДО ДАНИХ УСПІШНО ВИКОНАНІ!');
            get_contacts();
        } else {
            alert('error', 'ПОМИЛКА ПІД ЧАС ПРОЦЕСУ ЗБЕРЕЖЕННЯ ДАНИХ!');
        }
    };

    xhr.send(data_str);
}

carousel_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_image();
});

function add_image() {
    let data = new FormData();
    data.append('picture', carousel_picture_inp.files[0]);
    data.append('add_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('carousel-s');
        var modal = new bootstrap.Modal(document.getElementById('myModal'));
        modal.hide();

        if (this.responseText == 'inv_img') {
            alert('error', 'ПОМИЛКА! ДОЗВОЛЯЄТЬСЯ ДОДАВАТИ ЗОБРАЖЕННЯ ФОРМАТУ JPG, АБО PNG!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'ПОМИЛКА! РОЗМІР ДОДАВАНОГО ЗОБРАЖЕННЯ МАЄ БУТИ МЕНШЕ НІЖ 10 МБ!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ДОДАВАННІ ЗОБРАЖЕННЯ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!');
        } else {
            alert('success', 'ЗМІНИ ДО ДАНИХ УСПІШНО ВИКОНАНІ! ФОТО ЗАВАНТАЖЕНО!');
            carousel_picture_inp.value = '';
            get_carousel();
        }
    }
    xhr.send(data);
}

function get_carousel() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('carousel-data').innerHTML = this.responseText;
    }
    xhr.send('get_carousel');
}

function rem_image(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'ЗМІНИ ДО ДАНИХ УСПІШНО ВИКОНАНІ! ФОТО ВИДАЛЕНО!');
            get_carousel();
        } else {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ВИДАЛЕННІ ЗОБРАЖЕННЯ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!');
        }
    }
    xhr.send('rem_image=' + val);
}

window.onload = function () {
    get_general();
    get_contacts();
    get_carousel();
};