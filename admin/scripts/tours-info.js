let add_tour_form = document.getElementById('add_tour_form');
    
add_tour_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_tours();
});

function add_tours() {
    let data = new FormData();
    data.append('add_tour', '');
    data.append('tour_name', add_tour_form.elements['tour_name'].value);
    data.append('tour_area', add_tour_form.elements['tour_area'].value);
    data.append('tour_price', add_tour_form.elements['tour_price'].value);
    data.append('tour_quantity', add_tour_form.elements['tour_quantity'].value);
    data.append('tour_adult', add_tour_form.elements['tour_adult'].value);
    data.append('tour_children', add_tour_form.elements['tour_children'].value);
    data.append('tour_desc', add_tour_form.elements['tour_desc'].value);

    let features = [];

    add_tour_form.elements['tour_features'].forEach(el => {
        if (el.checked) {
            features.push(el.value);
        }
    });

    data.append('tour_features', JSON.stringify(features));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'УСПІХ! ДАНИЙ ТУР УСПІШНО ДОДАНО ДО КАТАЛОГУ!');
            add_tour_form.reset();
            get_tours();
            $('#add_tour').modal('hide');
        } else if (this.responseText == 'tour_added') {
            alert('error', '');
        } else {
            alert('error', '');
        }
    };
    xhr.send(data);
}

function get_tours() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('tour-data').innerHTML = this.responseText;
    }

    xhr.send('get_tour');
}

let edit_tour_form = document.getElementById('edit_tour_form');

function edit_tour(tour_id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        let data = JSON.parse(this.responseText);
        edit_tour_form.elements['tour_name'].value = data.tourdata.tour_name;
        edit_tour_form.elements['tour_area'].value = data.tourdata.tour_area;
        edit_tour_form.elements['tour_price'].value = data.tourdata.tour_price;
        edit_tour_form.elements['tour_quantity'].value = data.tourdata.tour_quantity;
        edit_tour_form.elements['tour_adult'].value = data.tourdata.tour_adult;
        edit_tour_form.elements['tour_children'].value = data.tourdata.tour_children;
        edit_tour_form.elements['tour_desc'].value = data.tourdata.tour_desc;
        edit_tour_form.elements['tour_id'].value = data.tourdata.tour_id;

        edit_tour_form.elements['tour_features'].forEach(el => {
            if (data.features.includes(Number(el.value))) {
                el.checked = true;
            }
        });
    }
    xhr.send('get_tour_info=' + tour_id);
}

edit_tour_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_tour();
});

function submit_edit_tour() {
    let data = new FormData();
    data.append('edit_tour', '');
    data.append('tour_id', edit_tour_form.elements['tour_id'].value);
    data.append('tour_name', edit_tour_form.elements['tour_name'].value);
    data.append('tour_area', edit_tour_form.elements['tour_area'].value);
    data.append('tour_price', edit_tour_form.elements['tour_price'].value);
    data.append('tour_quantity', edit_tour_form.elements['tour_quantity'].value);
    data.append('tour_adult', edit_tour_form.elements['tour_adult'].value);
    data.append('tour_children', edit_tour_form.elements['tour_children'].value);
    data.append('tour_desc', edit_tour_form.elements['tour_desc'].value);

    let features = [];

    edit_tour_form.elements['tour_features'].forEach(el => {
        if (el.checked) {
            features.push(el.value);
        }
    });

    data.append('tour_features', JSON.stringify(features));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'УСПІХ! ДАНИЙ ТУР УСПІШНО ОНОВЛЕНО В КАТАЛОЗІ!');
            edit_tour_form.reset();
            get_tours();
            $('#edit_tour').modal('hide');
        } else if (this.responseText == 'tour_added') {
            alert('error', '');
        } else {
            alert('error', 'ПОМИЛКА! МОЖЛИВО ТУР БУВ ДОДАНО УСПІШНО, АЛЕ НЕ БУЛО ОБРАНО КЛЮЧОВІ ОЗНАКИ! РЕДАГУВАННЯ НЕ МОЖЛИВЕ!');
        }
    };
    xhr.send(data);
}

function change_status(tour_id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'НА РАЗІ ТУР ОТРИМАВ СТАТУС ВІДОБРАЖЕННЯ : ' + (val == 1 ? '🟢 ВКЛ.' : '🔴 ВИКЛ.'));
            get_tours();
        } else {
            alert('error', 'ПОМИЛКА ПІД ЧАС ПРОЦЕСУ ЗМІНИ СТАТУСУ РОБОТИ САЙТУ!');
        }
    }

    xhr.send('change_status=' + tour_id + '&value=' + val);
}

let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_image();
});

function add_image() {
    let data = new FormData();
    data.append('image', add_image_form.elements['image'].files[0]);
    data.append('tour_id', add_image_form.elements['tour_id'].value);
    data.append('add_image', '');
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    
    xhr.onload = function () {  
        if (this.responseText == 'inv_img') {
            alert('error', 'ПОМИЛКА! ДОЗВОЛЯЄТЬСЯ ДОДАВАТИ ЗОБРАЖЕННЯ ФОРМАТУ JPG, АБО PNG!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'ПОМИЛКА! РОЗМІР ДОДАВАНОГО ЗОБРАЖЕННЯ МАЄ БУТИ МЕНШЕ НІЖ 10 МБ!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ДОДАВАННІ ЗОБРАЖЕННЯ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!');
        } else {
            alert('success', 'ЗМІНИ ДО ДАНИХ УСПІШНО ВИКОНАНІ! ФОТО ЗАВАНТАЖЕНО!', 'image-alert');
            tour_images(add_image_form.elements['tour_id'].value, document.querySelector("#tour_image .modal-title").innerText);
            add_image_form.reset();
        }
    }
    xhr.send(data);
}

function tour_images(id, tname) {
    document.querySelector("#tour_image .modal-title").innerText = tname;
    add_image_form.elements['tour_id'].value = id;
    add_image_form.elements['image'].value = '';

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
       document.getElementById('tour-image-data').innerHTML = this.responseText;
    }

    xhr.send('get_tour_images='+id);
}

function rem_image(img_id, tour_id) {
    let data = new FormData();
    data.append('image_id', img_id);
    data.append('tour_id', tour_id);
    data.append('rem_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function() {
        if(this.responseText == 1) {
            alert('success', 'УСПІХ! ФОТО ДО ДАНОГО ТУРУ УСПІШНО ВИДАЛЕНО!', 'image-alert');
            tour_images(tour_id, document.querySelector("#tour_image .modal-title").innerText);
        } else {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ВИДАЛЕННІ ЗОБРАЖЕННЯ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!', 'image-alert');
        }
    }
    xhr.send(data);
}

function thumb_image(img_id, tour_id) {
    let data = new FormData();
    data.append('image_id', img_id);
    data.append('tour_id', tour_id);
    data.append('thumb_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function() {
        if(this.responseText == 1) {
            alert('success', 'УСПІХ! ФОТО ДО ДАНОГО ТУРУ УСПІШНО ОБРАНО ДЛЯ ВІДОБРАЖЕННЯ!', 'image-alert');
            tour_images(tour_id, document.querySelector("#tour_image .modal-title").innerText);
        } else {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ОБИРАННІ ЗОБРАЖЕННЯ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!', 'image-alert');
        }
    }
    xhr.send(data);
}

function remove_tour(tour_id) {
    if(confirm("УВАГА! ВИ ВПЕВНЕНІ ЩО ВИ ХОЧЕТЕ ВИДАЛИТИ ДАНИЙ ТУР?")) {
        let data = new FormData();
        data.append('tour_id', tour_id);
        data.append('remove_tour', '');

        let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function() {
        if(this.responseText == 1) {
            alert('success', 'УСПІХ! ДАНИЙ ТУР УСПІШНО ВИДАЛЕНО З КАТАЛОГУ!');
            get_tours();
        } else {
            alert('error', 'ПОМИЛКА! ВІДБУЛАСЯ ПОМИЛКА ПРИ ВИДАЛЕННІ ДАНОГО ТУРУ С КАТАЛОГУ. СЕРВЕР НЕ НАДАВ ВІДПОВІДЬ!');
        }
    }
    xhr.send(data);
    }
}

window.onload = function () {
    get_tours();
}