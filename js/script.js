function httpGet(url) {
    return new Promise(function(resolve, reject) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if (this.status === 200) {
                resolve(JSON.parse(this.response));
            } else {
                const error = new Error(this.statusText);
                error.code = this.status;
                reject(error);
            }
        };
        xhr.onerror = function() {
            reject(new Error("Network Error"));
        };
        xhr.send();
    });
}

let params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            let a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );


function formatDate(date) {
    let day = date.getDate();
    if (day < 10) day = '0' + day;
    let mount = date.getMonth() + 1;
    if (mount < 10) mount = '0' + mount;
    let year = date.getFullYear();
    if (year < 10) year = '0' + year;
    return day + '.' + mount + '.' + year;
}

function formatPassport(number) {
    number = number.split('')
    number.splice(4, 0, ' ')
    return number.join('')
}

function formatPhone(number) {
    number = number.split('')
    number.splice(1, 0, '(')
    number.splice(5, 0, ')')
    number.splice(9, 0, '-')
    number.splice(12, 0, '-')
    return number.join('')
}

function age(birthday) {
    let currentDate = new Date();
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
    birthday = new Date(birthday);
    let checkBirthday= new Date(currentDate.getFullYear(), birthday.getMonth(), birthday.getDate());
    let age = currentDate.getFullYear() - birthday.getFullYear();
    if (currentDate < checkBirthday) { age = age-1; }
    return age;
}

const cardNone = (btn, id) => {
    const cardNone = document.getElementById(id)
    btn.value === 'Подробнее' ? btn.value = 'Скрыть' : btn.value = 'Подробнее'
    cardNone.classList.toggle("none")
}

const staffDelete = id => {
    let check = prompt('Для удаления карточки введите delete');
    (check === 'delete') ? deleteEntry(id) : alert('Проверочное слово введено не верно')
    function deleteEntry(id) {
        httpGet('/requests/requests.php?deleteStaff=' + id)
            .then(
                response => {
                    (response !== []) ? alert('Карточка удалена') : alert(response)
                    document.getElementById("article"+id).remove();
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const loadingIndex = () => {
    httpGet('/requests/requests.php?fullStaff=true')
        .then(
            response => {
            let staff = '';
            response.map(value => {
                let fio = value.FIO.split(' ')
                staff += `<article class="card" id="article${value.idStaff}">
                <h3>${value.FIO}</h3>
                <div style="display: flex">
                    <div class="column"><p>Должность: ${value.position}</p><p>Отдел: ${value.department}</p></div>
                    <div class="column"><p>Телефон: ${formatPhone(value.phone)}</p><p>Возраст: ${age(value.birthday)} (${formatDate(new Date(value.birthday))})</p></div>
                </div>
                <div id="${value.idStaff}" class="hiddenCard none">
                    <div class="column"><p>Профессия: ${value.profession}</p><p>Образование: ${value.institution}</p><p>Дата приема на работу: ${formatDate(new Date(value.employmentDate))}</p></div>
                    <div class="column"><p>Пол: ${value.sex}</p><p>Паспорт: ${formatPassport(value.passport)}</p><p>Адрес: ${value.address}</p></div>
                </div>
                <div style="height: 50px"></div>
                <input type="button" class="moreDetails" onclick="cardNone(this, ${value.idStaff})" value="Подробнее">
                <a href="/staff.php?id=${value.idStaff}&&name=${fio[1]}&&surname=${fio[0]}&&middlename=${fio[2]}&&passport=${value.passport}&&address=${value.address}&&phone=${value.phone}&&birthday=${value.birthday}&&employmentDate=${value.employmentDate}&&sex=${value.sex}&&education=${value.institution}&&profession=${value.profession}&&positions=${value.position}&&department=${value.department}"><button type="button" class="change" title="изменить"><i class="fal fa-pen-alt"></i></button></a>
                <button type="button" class="delete" title="удалить" onclick="staffDelete(${value.idStaff})"><i class="fad fa-trash-alt"></i></button>
                </article>`
            })
            $('#cards').append(staff)
        },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allPositions=true')
        .then(
            response => {
            let position = '';
            response.map(value => position += `<option value="${value['position']}">${value['position']}</option>`)
            $('#position').append(position)
        },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allDepartments=true')
        .then(
            response => {
            let department = '';
            response.map(value => department += `<option value="${value['department']}">${value['department']}</option>`)
            $('#department').append(department)
        },
            error => console.log(`Rejected: ${error}`)
        );
}

function find() {
    let search = document.getElementById("search").value;
    let position = document.getElementById("position").value;
    let department = document.getElementById("department").value;
    let ageFrom = document.getElementById("ageFrom").value;
    let ageUpTo = document.getElementById("ageUpTo").value;
    httpGet(`/requests/requests.php?findStaff=1&&fio=${search}&&position=${position}&&department=${department}&&ageFrom=${ageFrom}&&ageUpTo=${ageUpTo}`)
        .then(
            response => {
                if(response.length === 0){
                    $('#cards').empty().append('<div class="noResults"><span>Результатов не найдено</span></div>')
                    $('#amountsResult').empty().append(``)
                }else{
                    let staff = '';
                    for (let key in response) {
                        let fio = response[key].FIO.split(' ')
                        staff += `<article class="card" id="article${response[key].idStaff}">
                        <h3>${response[key].FIO}</h3>
                        <div style="display: flex">
                            <div class="column"><p>Должность: ${response[key].position}</p><p>Отдел: ${response[key].department}</p></div>
                            <div class="column"><p>Телефон: ${formatPhone(response[key].phone)}</p><p>Возраст: ${age(response[key].birthday)} (${formatDate(new Date(response[key].birthday))})</p></div>
                        </div>
                        <div id="${response[key].idStaff}" class="hiddenCard none">
                            <div class="column"><p>Профессия: ${response[key].profession}</p><p>Образование: ${response[key].institution}</p><p>Дата приема на работу: ${formatDate(new Date(response[key].employmentDate))}</p></div>
                            <div class="column"><p>Пол: ${response[key].sex}</p><p>Паспорт: ${formatPassport(response[key].passport)}</p><p>Адрес: ${response[key].address}</p></div>
                        </div>
                        <div style="height: 50px"></div>
                        <input type="button" class="moreDetails" onclick="cardNone(this, ${response[key].idStaff})" value="Подробнее">
                        <a href="/staff.php?id=${response[key].idStaff}&&name=${fio[1]}&&surname=${fio[0]}&&middlename=${fio[2]}&&passport=${response[key].passport}&&address=${response[key].address}&&phone=${response[key].phone}&&birthday=${response[key].birthday}&&employmentDate=${response[key].employmentDate}&&sex=${response[key].sex}&&education=${response[key].institution}&&profession=${response[key].profession}&&positions=${response[key].position}&&department=${response[key].department}"><button type="button" class="change" title="изменить"><i class="fal fa-pen-alt"></i></button></a>
                        <button type="button" class="delete" title="удалить" onclick="staffDelete(${response[key].idStaff})"><i class="fad fa-trash-alt"></i></button>
                        </article>`
                    }
                    $('#cards').empty().append(staff)
                    if (response.length === undefined){
                        $('#amountsResult').empty().append(`<div class="amountsResult"><span>Найдено совпадений: ${Object.keys(response).length}</span><div style="margin-top: 20px"><a href="/">Сбросить</a></div></div>`)
                    }else{
                        $('#amountsResult').empty().append(`<div class="amountsResult"><span>Найдено совпадений: ${response.length}</span><div style="margin-top: 20px"><a href="/">Сбросить</a></div></div>`)
                    }
                }
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const education = () =>{
    httpGet('/requests/requests.php?allEducation=true')
        .then(
            response => {
                let table = '<table><tr><th>ID</th><th>Запись</th><th></th><th></th></tr>';
                response.map(value => table += `<tr><td>${value['idEducation']}</td><td>${value['institution']}</td><td><button type="button" class="changeRecording" title="изменить" onclick="changeEducation(${value['idEducation']}, '${value['institution']}')"><i class="fal fa-pen-alt"></i></button></td><td><button type="button" class="deleteRecording" title="удалить" onclick="deleteEducation(${value['idEducation']})"><i class="fad fa-trash-alt"></i></button></td></tr>`)
                table += '</table>'
                $('#data').empty().append(table)
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const professions = () =>{
    httpGet('/requests/requests.php?allProfessions=true')
        .then(
            response => {
                let table = '<table><tr><th>ID</th><th>Запись</th><th></th><th></th></tr>';
                response.map(value => table += `<tr><td>${value['idProfession']}</td><td>${value['profession']}</td><td><button type="button" class="changeRecording" title="изменить" onclick="changeProfessions(${value['idProfession']}, '${value['profession']}')"><i class="fal fa-pen-alt"></i></button></td><td><button type="button" class="deleteRecording" title="удалить" onclick="deleteProfessions(${value['idProfession']})"><i class="fad fa-trash-alt"></i></button></td></tr>`)
                table += '</table>'
                $('#data').empty().append(table)
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const positions = () =>{
    httpGet('/requests/requests.php?allPositions=true')
        .then(
            response => {
                let table = '<table><tr><th>ID</th><th>Запись</th><th></th><th></th></tr>';
                response.map(value => table += `<tr><td>${value['idPositions']}</td><td>${value['position']}</td><td><button type="button" class="changeRecording" title="изменить" onclick="changePosition(${value['idPositions']}, '${value['position']}')"><i class="fal fa-pen-alt"></i></button></td><td><button type="button" class="deleteRecording" title="удалить" onclick="deletePositions(${value['idPositions']})"><i class="fad fa-trash-alt"></i></button></td></tr>`)
                table += '</table>'
                $('#data').empty().append(table)
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const departments = () =>{
    httpGet('/requests/requests.php?allDepartments=true')
        .then(
            response => {
                let table = '<table><tr><th>ID</th><th>Запись</th><th></th><th></th></tr>';
                response.map(value => table += `<tr><td>${value['idDepartment']}</td><td>${value['department']}</td><td><button type="button" class="changeRecording" title="изменить" onclick="changeDepartments(${value['idDepartment']}, '${value['department']}')"><i class="fal fa-pen-alt"></i></button></td><td><button type="button" class="deleteRecording" title="удалить" onclick="deleteDepartments(${value['idDepartment']})"><i class="fad fa-trash-alt"></i></button></td></tr>`)
                table += '</table>'
                $('#data').empty().append(table)
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const deleteEducation = (id) => {
    let check = prompt('Для удаления записи введите delete');
    (check === 'delete') ? deleteEntry(id) : alert('Проверочное слово введено не верно')
    function deleteEntry(id) {
        httpGet(`/requests/requests.php?deleteEducation=${id}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись удалена') : alert(response)
                    education()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const deleteProfessions = (id) => {
    let check = prompt('Для удаления записи введите delete');
    (check === 'delete') ? deleteEntry(id) : alert('Проверочное слово введено не верно')
    function deleteEntry(id) {
        httpGet(`/requests/requests.php?deleteProfessions=${id}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись удалена') : alert(response)
                    professions()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const deletePositions = (id) => {
    let check = prompt('Для удаления записи введите delete');
    (check === 'delete') ? deleteEntry(id) : alert('Проверочное слово введено не верно')
    function deleteEntry(id) {
        httpGet(`/requests/requests.php?deletePosition=${id}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись удалена') : alert(response)
                    positions()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const deleteDepartments = (id) => {
    let check = prompt('Для удаления записи введите delete');
    (check === 'delete') ? deleteEntry(id) : alert('Проверочное слово введено не верно')
    function deleteEntry(id) {
        httpGet(`/requests/requests.php?deleteDepartments=${id}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись удалена') : alert(response)
                    departments()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const change = (id, value) => {
    $('#addRecordH3').empty().append('Изменение записи')
    document.getElementById("addID").value = id;
    document.getElementById("addValue").value = value;
    document.getElementById("addRecord").value = 'Изменить'
}

const changeEducation = (id, value) =>{
    change(id, value)
    document.getElementById("addRecord").setAttribute("onclick",`dataChange(${id})`)
}

function dataChange(id) {
    let value = document.getElementById("addValue").value
    httpGet(`/requests/requests.php?updateEducation=${id}&&updateEducationValue=${value}`)
        .then(
            response => {
                (response !== []) ? alert('Запись обнавлена') : alert(response)
                education()
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const changeProfessions = (id, value) =>{
    change(id, value)
    document.getElementById("addRecord").setAttribute("onclick",`dataProfessions(${id})`)
}

function dataProfessions(id) {
    let value = document.getElementById("addValue").value
    httpGet(`/requests/requests.php?updateProfessions=${id}&&updateProfessionsValue=${value}`)
        .then(
            response => {
                (response !== []) ? alert('Запись обнавлена') : alert(response)
                professions()
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const changePosition = (id, value) =>{
    change(id, value)
    document.getElementById("addRecord").setAttribute("onclick",`dataPosition(${id})`)
}

function dataPosition(id) {
    let value = document.getElementById("addValue").value
    httpGet(`/requests/requests.php?updatePosition=${id}&&updatePositionValue=${value}`)
        .then(
            response => {
                (response !== []) ? alert('Запись обнавлена') : alert(response)
                positions()
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const changeDepartments = (id, value) =>{
    change(id, value)
    document.getElementById("addRecord").setAttribute("onclick",`dataDepartments(${id})`)
}

function dataDepartments(id) {
    let value = document.getElementById("addValue").value
    httpGet(`/requests/requests.php?updateDepartments=${id}&&updateDepartmentsValue=${value}`)
        .then(
            response => {
                (response !== []) ? alert('Запись обнавлена') : alert(response)
                departments()
            },
            error => console.log(`Rejected: ${error}`)
        );
}

function educationChange() {
    let value = document.getElementById("addValue").value
    if (value !== ''){
        httpGet(`/requests/requests.php?newEducation=${value}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись добавленна') : alert(response)
                    education()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

function professionsChange() {
    let value = document.getElementById("addValue").value
    if (value !== ''){
        httpGet(`/requests/requests.php?newProfessions=${value}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись добавленна') : alert(response)
                    professions()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

function positionsChange() {
    let value = document.getElementById("addValue").value
    if (value !== ''){
        httpGet(`/requests/requests.php?newPosition=${value}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись добавленна') : alert(response)
                    positions()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

function departmentsChange() {
    let value = document.getElementById("addValue").value
    if (value !== ''){
        httpGet(`/requests/requests.php?newDepartments=${value}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись добавленна') : alert(response)
                    departments()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

//маска телефона
$(function(){
    $("#phone").mask("89999999999");
});

//маска паспорта
$(function(){
    $("#passport").mask("9999999999");
});

const loadingStaff = () => {
    httpGet('/requests/requests.php?allSexs=true')
        .then(
            response => {
                let sex = '';
                let check = params['sex']
                response.map(value => (value['sex'] === check) ? sex += `<option selected value="${value['idSex']}">${value['sex']}</optionselected>` : sex += `<option value="${value['idSex']}">${value['sex']}</option>`)
                $('#idSex').append(sex)
            },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allEducation=true')
        .then(
            response => {
                let institution = '';
                let check = params['education']
                response.map(value => (value['institution'] === check) ? institution += `<option selected value="${value['idEducation']}">${value['institution']}</option>` : institution += `<option value="${value['idEducation']}">${value['institution']}</option>`)
                $('#idEducation').append(institution)
            },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allProfessions=true')
        .then(
            response => {
                let professions = '';
                let check = params['profession']
                response.map(value => (value['profession'] === check) ? professions += `<option selected value="${value['idProfession']}">${value['profession']}</option>` : professions += `<option value="${value['idProfession']}">${value['profession']}</option>`)
                $('#idProfession').append(professions)
            },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allPositions=true')
        .then(
            response => {
                let position = '';
                let check = params['positions']
                response.map(value => (value['position'] === check) ? position += `<option selected value="${value['idPositions']}">${value['position']}</option>` : position += `<option value="${value['idPositions']}">${value['position']}</option>`)
                $('#idPositions').append(position)
            },
            error => console.log(`Rejected: ${error}`)
        );
    httpGet('/requests/requests.php?allDepartments=true')
        .then(
            response => {
                let department = '';
                let check = params['department']
                response.map(value => (value['department'] === check) ? department += `<option selected value="${value['idDepartment']}">${value['department']}</option>` : department += `<option value="${value['idDepartment']}">${value['department']}</option>`)
                $('#idDepartment').append(department)
            },
            error => console.log(`Rejected: ${error}`)
        );
    if (undefined !== params['id']){
        document.getElementById("id").value = params['id']
    }
    if (undefined !== params['name']){
        document.getElementById("name").value = params['name']
    }
    if (undefined !== params['surname']){
        document.getElementById("surname").value = params['surname']
    }
    if (undefined !== params['middlename']){
        document.getElementById("middlename").value = params['middlename']
    }
    if (undefined !== params['passport']){
        document.getElementById("passport").value = params['passport']
    }
    if (undefined !== params['address']){
        document.getElementById("address").value = params['address']
    }
    if (undefined !== params['phone']){
        document.getElementById("phone").value = params['phone']
    }
    if (undefined !== params['birthday']){
        document.getElementById("birthday").value = params['birthday']
    }
    if (undefined !== params['employmentDate']){
        document.getElementById("employmentDate").value = params['employmentDate']
    }
    if (undefined !== params['sex']){
        document.getElementById("idSex").value = params['sex']
    }
    if (undefined !== params['education']){
        document.getElementById("idEducation").value = params['education']
    }
    if (undefined !== params['profession']){
        document.getElementById("idProfession").value = params['profession']
    }
    if (undefined !== params['positions']){
        document.getElementById("idPositions").value = params['positions']
    }
    if (undefined !== params['department']){
        document.getElementById("idDepartment").value = params['department']
    }
}

function newStaff() {
    const value = dataStaff()
    if(value){
        httpGet(`/requests/requests.php?newStaff=true&&name=${value.name}&&surname=${value.surname}&&middlename=${value.middlename}&&passport=${value.passport}&&address=${value.address}&&phone=${value.phone}&&birthday=${value.birthday}&&employmentDate=${value.employmentDate}&&idEducation=${value.idEducation}&&idProfession=${value.idProfession}&&idSex=${value.idSex}&&idPositions=${value.idPositions}&&idDepartment=${value.idDepartment}`)
            .then(
                response => {
                    (response !== []) ? alert('Кадр добавлен') : alert(response)
                    location="/";
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

function changeStaff() {
    const id = params['id']
    const value = dataStaff()
    if(value){
        httpGet(`/requests/requests.php?updateStaff=true&&id=${id}&&name=${value.name}&&surname=${value.surname}&&middlename=${value.middlename}&&passport=${value.passport}&&address=${value.address}&&phone=${value.phone}&&birthday=${value.birthday}&&employmentDate=${value.employmentDate}&&idEducation=${value.idEducation}&&idProfession=${value.idProfession}&&idSex=${value.idSex}&&idPositions=${value.idPositions}&&idDepartment=${value.idDepartment}`)
            .then(
                response => {
                    (response !== []) ? alert('Данные кадра изменены') : alert(response)
                    location="/";
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}

const dataStaff = () => {
    const staff = {
        name:           document.getElementById("name").value,
        surname:        document.getElementById("surname").value,
        middlename:     document.getElementById("middlename").value,
        passport:       document.getElementById("passport").value,
        address:        document.getElementById("address").value,
        phone:          document.getElementById("phone").value,
        birthday:       document.getElementById("birthday").value,
        idSex:          document.getElementById("idSex").value,
        employmentDate: document.getElementById("employmentDate").value,
        idEducation:    document.getElementById("idEducation").value,
        idProfession:   document.getElementById("idProfession").value,
        idPositions:    document.getElementById("idPositions").value,
        idDepartment:   document.getElementById("idDepartment").value,
    }
    if (staff.surname.length < 2){
        alert('Слишком короткая фамилия')
        document.getElementById("surname").focus();
        return false;
    }
    if (staff.name.length < 2){
        alert('Слишком короткое имя')
        document.getElementById("name").focus();
        return false;
    }
    if (staff.middlename.length < 2){
        alert('Слишком короткое отчество')
        document.getElementById("middlename").focus();
        return false;
    }
    if (staff.passport.length !== 10){
        alert('Неверно введен паспорт')
        document.getElementById("passport").focus();
        return false;
    }
    if (staff.address.length <= 3){
        alert('Слишком короткий адрес')
        document.getElementById("address").focus();
        return false;
    }
    if (staff.phone.length !== 11){
        alert('Неверно введен номер телефона')
        document.getElementById("phone").focus();
        return false;
    }
    if (staff.birthday === ''){
        alert('Пропущена дата рождения')
        document.getElementById("birthday").focus();
        return false;
    }
    if (staff.employmentDate === ''){
        alert('Пропущена дата приема на работу')
        document.getElementById("employmentDate").focus();
        return false;
    }
    return staff;
}

const admin = () =>{
    httpGet('/requests/requests.php?allAdmin=true')
        .then(
            response => {
                let table = '<table><tr><th>ID</th><th>Логин</th><th>Пароль</th><th></th><th></th></tr>';
                response.map(value => table += `<tr><td>${value['idUser']}</td><td>${value['login']}</td><td>${value['password']}</td><td><button type="button" class="changeRecording" title="изменить" onclick="changeUser(${value['idUser']}, '${value['login']}', '${value['password']}')"><i class="fal fa-pen-alt"></i></button></td><td><button type="button" class="deleteRecording" title="удалить" onclick="deleteUsers(${value['idUser']})"><i class="fad fa-trash-alt"></i></button></td></tr>`)
                table += '</table>'
                $('#data').empty().append(table)
            },
            error => console.log(`Rejected: ${error}`)
        );
}

const changeUser = (id, login, password) => {
    $('#addRecordH3').empty().append('Изменение записи')
    document.getElementById("addID").value = id;
    document.getElementById("addLogin").value = login;
    document.getElementById("addPassword").value = password;
    document.getElementById("addRecord").setAttribute("onclick",`dataChangeUser(${id})`)
    document.getElementById("addRecord").value = 'Изменить'
}

function dataChangeUser(id) {
    let login = document.getElementById("addLogin").value
    let password = document.getElementById("addPassword").value
    httpGet(`/requests/requests.php?updateUser=${id}&&updateLogin=${login}&&updatePassword=${password}`)
        .then(
            response => {
                (response !== []) ? alert('Запись обнавлена') : alert(response)
                admin()
            },
            error => console.log(`Rejected: ${error}`)
        );
}

function newUser() {
    let login = document.getElementById("addLogin").value
    let password = document.getElementById("addPassword").value
    if (login !== '' && password !== ''){
        httpGet(`/requests/requests.php?newUser=${login}&&password=${password}`)
            .then(
                response => {
                    (response !== []) ? alert('Запись добавленна') : alert(response)
                    admin()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }else{
        alert('error')
    }
}

const deleteUsers = (id) => {
    let check = prompt('Для удаления пользователя введите delete');
    (check === 'delete') ? deleteUser(id) : alert('Проверочное слово введено не верно')
    function deleteUser(id) {
        httpGet(`/requests/requests.php?deleteUser=${id}`)
            .then(
                response => {
                    (response !== []) ? alert('Пользователь удален') : alert(response)
                    admin()
                },
                error => console.log(`Rejected: ${error}`)
            );
    }
}
