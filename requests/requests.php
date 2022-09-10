<?php
$requests = array(
    "allPositions" =>   "SELECT * FROM positions ORDER BY position",
    "allDepartments" => "SELECT * FROM departments ORDER BY department",
    "allEducation" =>   "SELECT * FROM education ORDER BY institution",
    "allProfessions" => "SELECT * FROM professions ORDER BY profession",
    "allSexs" => "SELECT * FROM sexs",
    "allStaff" => "SELECT * FROM staff",
    "fullStaff" => "SELECT staff.idStaff, staff.name || ' ' || staff.surname || ' ' || staff.middlename AS FIO, staff.passport, staff.address, staff.phone, staff.birthday, staff.employmentDate, education.institution, professions.profession, sexs.sex, departments.department, positions.position FROM staff JOIN education JOIN professions JOIN sexs JOIN departments JOIN positions ON staff.idEducation = education.idEducation AND staff.idProfession = professions.idProfession AND staff.idSex = sexs.idSex AND staff.idDepartment = departments.idDepartment AND staff.idPositions = positions.idPositions",
    "deleteStaff" => "DELETE FROM staff WHERE idStaff = :id",
    "findStaff" => "SELECT staff.idStaff, staff.name || ' ' || staff.surname || ' ' || staff.middlename AS FIO, staff.passport, staff.address, staff.phone, staff.birthday, staff.employmentDate, education.institution, professions.profession, sexs.sex, departments.department, positions.position FROM staff JOIN education JOIN professions JOIN sexs JOIN departments JOIN positions ON staff.idEducation = education.idEducation AND staff.idProfession = professions.idProfession AND staff.idSex = sexs.idSex AND staff.idDepartment = departments.idDepartment AND staff.idPositions = positions.idPositions WHERE FIO LIKE :fio AND  positions.position LIKE :position AND departments.department LIKE :department",
    "deleteEducation"   => "DELETE FROM education WHERE idEducation = :id",
    "deleteProfessions" => "DELETE FROM professions WHERE idProfession = :id",
    "deletePosition"    => "DELETE FROM positions WHERE idPositions = :id",
    "deleteDepartments" => "DELETE FROM departments WHERE idDepartment = :id",
    "updateEducation"   => "UPDATE education SET institution = :value WHERE idEducation = :id",
    "updateProfessions" => "UPDATE professions SET profession = :value WHERE idProfession = :id",
    "updatePosition"    => "UPDATE positions SET position = :value WHERE idPositions = :id",
    "updateDepartments" => "UPDATE departments SET department = :value WHERE idDepartment = :id",
    "allAdmin" => "SELECT * FROM users WHERE idRole = 2",
    "updateUser"   => "UPDATE users SET login = :login, password = :password WHERE idUser = :id",
    "deleteUser" => "DELETE FROM users WHERE idUser = :id",
);
function request($request, $param){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $db->prepare($request);
        $result->execute($param);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        if ($_GET['ageFrom'] != '' or $_GET['ageUpTo'] != ''){
            if ($_GET['ageFrom'] == ''){$_GET['ageFrom'] = 0;}
            if ($_GET['ageUpTo'] == ''){$_GET['ageUpTo'] = 1000;}
            foreach ($result as $i => $value){
                $now_date = new DateTime(date('Y-m-d'));
                $old_date = new DateTime($value['birthday']);
                $interval = $now_date->diff($old_date);
                $age = $interval->format("%y");
                if ($age < $_GET['ageFrom'] or $age > $_GET['ageUpTo']){ unset($result[$i]); }
            }
            echo json_encode($result);
        }else{
            echo json_encode($result);
        }
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

if ($_GET['allPositions']){request($requests['allPositions'], []);}

if ($_GET['allDepartments']){request($requests['allDepartments'], []);}

if ($_GET['allEducation']){request($requests['allEducation'], []);}

if ($_GET['allProfessions']){request($requests['allProfessions'], []);}

if ($_GET['allSexs']){request($requests['allSexs'], []);}

if ($_GET['allStaff']){request($requests['allStaff'], []);}

if ($_GET['fullStaff']){request($requests['fullStaff'], []);}

if ($_GET['deleteStaff']){request($requests['deleteStaff'], ['id'=>$_GET['deleteStaff']]);}

if ($_GET['findStaff']){request($requests['findStaff'], ['fio'=>"%{$_GET['fio']}%", 'position'=>"%{$_GET['position']}%", 'department'=>"%{$_GET['department']}%",]);}

if ($_GET['deleteEducation']){request($requests['deleteEducation'], ['id'=>$_GET['deleteEducation']]);}

if ($_GET['deleteProfessions']){request($requests['deleteProfessions'], ['id'=>$_GET['deleteProfessions']]);}

if ($_GET['deletePosition']){request($requests['deletePosition'], ['id'=>$_GET['deletePosition']]);}

if ($_GET['deleteDepartments']){request($requests['deleteDepartments'], ['id'=>$_GET['deleteDepartments']]);}

if ($_GET['updateEducation']){request($requests['updateEducation'], ['id'=>$_GET['updateEducation'], 'value'=>"{$_GET['updateEducationValue']}"]);}

if ($_GET['updateProfessions']){request($requests['updateProfessions'], ['id'=>$_GET['updateProfessions'], 'value'=>"{$_GET['updateProfessionsValue']}"]);}

if ($_GET['updatePosition']){request($requests['updatePosition'], ['id'=>$_GET['updatePosition'], 'value'=>"{$_GET['updatePositionValue']}"]);}

if ($_GET['updateDepartments']){request($requests['updateDepartments'], ['id'=>$_GET['updateDepartments'], 'value'=>"{$_GET['updateDepartmentsValue']}"]);}

if ($_GET['newEducation']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO education (institution) VALUES ('".$_GET['newEducation']."')";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['newProfessions']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO professions (profession) VALUES ('".$_GET['newProfessions']."')";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['newPosition']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO positions (position) VALUES ('".$_GET['newPosition']."')";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['newDepartments']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO departments (department) VALUES ('".$_GET['newDepartments']."')";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['newStaff']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO staff (name, surname, middlename, passport, address, phone, birthday, employmentDate, idEducation, idProfession, idSex, idPositions, idDepartment) VALUES ('".$_GET['name']."', '".$_GET['surname']."', '".$_GET['middlename']."', ".$_GET['passport'].", '".$_GET['address']."', ".$_GET['phone'].", '".$_GET['birthday']."', '".$_GET['employmentDate']."', ".$_GET['idEducation'].", ".$_GET['idProfession'].", ".$_GET['idSex'].", ".$_GET['idPositions'].", ".$_GET['idDepartment'].")";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['updateStaff']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE staff SET name = '".$_GET['name']."', surname = '".$_GET['surname']."', middlename = '".$_GET['middlename']."', passport = '".$_GET['passport']."', address = '".$_GET['address']."', phone = '".$_GET['phone']."', birthday = '".$_GET['birthday']."', employmentDate = '".$_GET['employmentDate']."', idEducation = '".$_GET['idEducation']."', idProfession = '".$_GET['idProfession']."', idSex = '".$_GET['idSex']."', idPositions = '".$_GET['idPositions']."', idDepartment = '".$_GET['idDepartment']."' WHERE idStaff = '".$_GET['id']."'";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['allAdmin']){request($requests['allAdmin'], []);}

if ($_GET['updateUser']){request($requests['updateUser'], ['id'=>$_GET['updateUser'], 'login'=>"{$_GET['updateLogin']}", 'password'=>"{$_GET['updatePassword']}"]);}

if ($_GET['newUser']){
    try {
        $db = new PDO('sqlite:kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO users (login, password, idRole) VALUES ('".$_GET['newUser']."', '".$_GET['password']."', 2)";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

if ($_GET['deleteUser']){request($requests['deleteUser'], ['id'=>$_GET['deleteUser']]);}
