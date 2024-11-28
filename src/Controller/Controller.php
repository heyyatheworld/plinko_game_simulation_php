<?php
namespace MyApp\Controller;

use MyApp\Model\User;

class UserController {
    public function show($id) {
        $user = new User();
        $data = $user->getUser($id);
        include '../View/user_view.php'; // Передача данных в представление
    }
}
?>

****************

<?php
namespace MyApp\Controller;

use MyApp\Model\User;

class UserController {
    public function show($id) {
        $userModel = new User();
        $user = $userModel->getUser($id);

        if ($user) {
            include '../View/user_view.php'; // Подключение представления с данными пользователя
        } else {
            echo "Пользователь не найден.";
        }
    }
}
?>
