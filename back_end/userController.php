<?php

include_once 'userClass.php';
include_once 'confirmVerification.php';

class userController {

    function createUser() {

        $confirm = new confirmVerification();

        $name = $_POST['name'];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $username = $_POST["user"];
        $gender = $_POST["radio1"];

        $file_name = $_FILES["photo"]["name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $pass_sa = $confirm->confirm_string($password);

        $user[0] = $confirm->confirm_string($name);
        $user[1] = $confirm->confirm_email($email);
        $user[2] = password_hash($pass_sa, PASSWORD_DEFAULT);
        $user[3] = $confirm->confirm_string($username);
        $user[4] = $confirm->confirm_string($gender);
        $user[5] = $user[3] . '.' . $ext;
        $user[6] = date("Y-m-d H:i:s");

        move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/img_user/$user[5]");

        $model = new userClass();
        if ($model->create($user)) {
            header("Location: ../views/users.php?creation=true");
            return "created";
        }
        return "save error";
    }

    function readUser() {

        $model = new userClass();
        $all_users = $model->read(1);
        $i = 1;
        foreach ($all_users as $user) {
            print "<tr>
            <td class='profile_user'> <img src='../assets/img_user/$user[picture]'class='rounded-circle w-100' alt='Cinque Terre'> </td>
            <td><strong>$user[name]</strong><br>$user[email]</td>
            <td><a href='modifyUser.php?id=$user[id]'><i id='pencil' class='fas fa-pencil-alt px-2'></i></a></td>
            <td><a href='users.php?type=delet&id=$user[id]'><i id='pencil' class='far fa-trash-alt'></i></a></td>
            </tr>";
            $i++;
        }
    }

    function readOne($id) {
        $model = new userClass();
        if ($row = $model->read("id = $id")) {
            foreach ($row as $result) {
                
            }
            return $result;
        }
        return "error lectura";
    }

    function login() {
        $model = new userClass();

        $user = $_POST['user'];
        $password = $_POST['pwd'];
        if ($row = $model->read("user_name = '$user'")) {
            foreach ($row as $result) {
                
            }
        }

        if ($result['user_name'] === $user) {
            $isPasswordCorrect = password_verify($password, $result['password']);
            if ($isPasswordCorrect) {
                session_start();
                $_SESSION['timeout'] = time();
                $_SESSION['name'] = $result['name'];
                $_SESSION['picture'] = $result['picture'];
                $_SESSION['id'] = $result['id'];

                if ($_POST['remember'] && !isset($_COOKIE['login_ok'])) {
//                    $user_enc = password_hash($user, PASSWORD_DEFAULT);
//                    $password_enc = password_hash($password, PASSWORD_DEFAULT);

                    setcookie("id", $result['id'], time() + 220, "/");
                    setcookie("login_user", $user, time() + 220, "/");
                    setcookie("login_password", $password, time() + 220, "/");
                }

                header("Location: ../views/info.php");
            } else {
                header("Location: ../index.php?access=false");
            }
        } else {
            header("Location: ../index.php?access=false");
        }
    }

    function cookies() {
        $model = new userClass();
        $id = $_COOKIE["id"];

        if (!isset($_COOKIE["login_ok"])) {

            if ($row = $model->read("id = '$id'")) {
                foreach ($row as $result) {
                    
                }
            }
//            $isUserCorrect = password_verify($_COOKIE["login_user"], $result['user_name']);
            $isPasswordCorrect = password_verify($_COOKIE["login_password"], $result['password']);

            if ($_COOKIE["login_user"] === $result['user_name'] && $isPasswordCorrect) {
                setcookie("login_ok", 'ok', time() + 220, "/");
                setcookie("name", $result['name'], time() + 220, "/");
                setcookie("picture", $result['picture'], time() + 220, "/");
                return $result;
            }
        }
    }

    function loginGeneral() {

        if (isset($_COOKIE['login_user'])) {
            if ($_COOKIE['login_user'] !== '') {
                $this->cookies();
            }
        }
        if (!isset($_SESSION['name']) && $_COOKIE['login_ok'] !== 'ok') {
            die(header("location: ../index.php"));
        }
//        if (isset($_SESSION['timeout'])) {
//            if ($_SESSION['timeout'] + 10 < time()) {
//                $this->logout();
//            }
//        }
    }

    function logout() {
        setcookie("login_ok", "", time() - 3600, "/");
        setcookie("login_user", "", time() - 3600, "/");
        setcookie("login_password", "", time() - 3600, "/");
        setcookie("name", "", time() - 3600, "/");
        setcookie("picture", "", time() - 3600, "/");
        session_start();
        session_unset();
        session_destroy();
        header("location: ../index.php");
        exit();
    }

    function updateUser($id) {

        $confirm = new confirmVerification();

        $name = $_POST['name'];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $username = $_POST["user"];
        $gender = $_POST["radio1"];

        $file_name = $_FILES["photo"]["name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $pass_sa = $confirm->confirm_string($password);

        $user[0] = $id;
        $user[1] = $confirm->confirm_string($name);
        $user[2] = $confirm->confirm_email($email);
        $user[3] = password_hash($pass_sa, PASSWORD_DEFAULT);
        $user[4] = $confirm->confirm_string($username);
        $user[5] = $confirm->confirm_string($gender);
        $user[6] = $user[4] . '.' . $ext;
        $user[7] = date("Y-m-d H:i:s");

        move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/img_user/$user[6]");

        $model = new userClass();
        if ($model->update($user)) {
            header("Location: ../views/users.php?modification=true");
            return "modified";
        }
        return "error";
    }

    function deleteUser($id) {
        $model = new userClass();
        $user = $model->read("id=$id");
        foreach ($user as $result) {
            
        }
        if ($model->delet($id)) {
            $photo = $result['picture'];
            if (unlink("../assets/img_user/$photo")) {
                header("Location: ../views/users.php?delet=true");
                return "delet";
            }
            return "error";
        }
    }

}

$user = new userController();
if (isset($_GET['type'])) {
    if ($_GET['type'] == "create") {
        $createUser = $user->createUser();
    } elseif ($_GET['type'] == "modify") {
        $modifyteUser = $user->updateUser($_GET['id']);
    } elseif ($_GET['type'] == "login") {
        $login = $user->login();
    } elseif ($_GET['type'] == "logout") {
        $login = $user->logout();
    }
}  
