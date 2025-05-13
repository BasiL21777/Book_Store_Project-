<?php
session_start();

// Check if user is logged in and has admin role
if (empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'A') {
    header("Location: shop.php");
    exit;
}

$error_Fields = array();
$avatar_errors = array();
$user = null;

// Fetch user data if ID is provided
if (isset($_GET['id'])) {
    $conn = mysqli_connect("localhost", "root", "", "book_store");
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $user = $row;
    } else {
        echo "User not found.";
        exit;
    }
    mysqli_close($conn);
} else {
    echo "No user ID provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    if (!(isset($_POST["name"]) && !empty($_POST["name"]))) {
        $error_Fields[] = "name";
    }
    if (!(isset($_POST["email"]) && !empty($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) {
        $error_Fields[] = "email";
    }
    if (!(isset($_POST["role"]) && in_array($_POST["role"], ['A', 'C']))) {
        $error_Fields[] = "role";
    }

    // Password validation (only if password fields are filled)
    $password_error = false;
    if (!empty($_POST["password"]) || !empty($_POST["confirm_password"])) {
        if ($_POST["password"] !== $_POST["confirm_password"]) {
            $error_Fields[] = "password";
            $password_error = true;
        } elseif (strlen($_POST["password"]) < 6) {
            $error_Fields[] = "password";
            $password_error = true;
        }
    }

    // Avatar handling
    $avatar_name = $_FILES['avatar']['name'];
    $avatar_type = $_FILES['avatar']['type'];
    $avatar_size = $_FILES['avatar']['size'];
    $avatar_tmp_name = $_FILES['avatar']['tmp_name'];
    $avatar_error = $_FILES['avatar']['error'];

    if ($avatar_error == 4) {
        // No new avatar uploaded, retain existing avatar
        $avatar = $user['avatar'];
    } else {
        // Validate avatar
        if ($avatar_size > 1000000) {
            $avatar_errors[] = "<div>File can't be more than 1MB</div>";
        }

        $allowed_extensions = array('jpg', 'gif', 'png', 'jpeg');
        $file_name_array = explode('.', $avatar_name);
        $file_extension = strtolower(end($file_name_array));

        if (!in_array($file_extension, $allowed_extensions)) {
            $avatar_errors[] = "<div>File not valid</div>";
        }
    }

    // Check if email is unique (excluding the current user)
    if (empty($error_Fields)) {
        $conn = mysqli_connect("localhost", "root", "", "book_store");
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT * FROM `users` WHERE email = '$email' AND id != '$id'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $error_Fields[] = "email";
            $avatar_errors[] = "<div>Email is already in use by another user</div>";
        }
        mysqli_close($conn);
    }

    // If no errors, proceed with update
    if (empty($error_Fields) && empty($avatar_errors)) {
        $conn = mysqli_connect("localhost", "root", "", "book_store");
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }

        // Prepare data
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $role = mysqli_real_escape_string($conn, $_POST["role"]);

        // Handle avatar upload if a new avatar is provided
        if ($avatar_error != 4) {
            $avatar = "/Projects/Book_store_Project/media/avatars/" . $name . "_" . $avatar_name;
            $avatar_path = $_SERVER['DOCUMENT_ROOT'] . "\\Projects\\Book_store_Project\\media\\avatars\\" . $name . "_" . $avatar_name;
            move_uploaded_file($avatar_tmp_name, $avatar_path);
        }

        // Prepare update query
        $query = "UPDATE `users` SET
                  name = '$name',
                  email = '$email',
                  role = '$role',
                  avatar = " . ($avatar ? "'$avatar'" : "avatar") . "
                  ";

        // Add password to update if provided
        if (!empty($_POST["password"]) && !$password_error) {
            $password = sha1($_POST["password"]); // Note: sha1 is used to match existing system, but password_hash is recommended
            $query .= ", password = '$password'";
        }

        $query .= " WHERE id = '$id'";

        if (mysqli_query($conn, $query)) {
            // Log if role changed to Admin
            if ($role === 'A' && $user['role'] !== 'A') {
                $ip_address = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR']);
                $log_query = "INSERT INTO `logs` (`user_id`, `ip_address`, `log_type`, `created_at`)
                              VALUES ('$id', '$ip_address', 'BE_ADMIN', CURRENT_TIMESTAMP)";
                if (!mysqli_query($conn, $log_query)) {
                    echo "Error logging admin role change: " . mysqli_error($conn);
                }
            }
            header("Location: Manager_DashBoard.php");
            exit;
        } else {
            echo mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>

<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="txt" value="<?= isset($_POST['name']) ? $_POST['name'] : $user['name'] ?>">
        <?php if (in_array('name', $error_Fields)) echo "*Please enter a valid name"; ?>
        <br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="txt" value="<?= isset($_POST['email']) ? $_POST['email'] : $user['email'] ?>">
        <?php if (in_array('email', $error_Fields)) echo "*Please enter a valid email"; ?>
        <br>

        <label for="role">Role</label>
        <select name="role" id="role">
            <option value="C" <?= $user['role'] == 'C' ? 'selected' : '' ?>>Customer</option>
            <option value="A" <?= $user['role'] == 'A' ? 'selected' : '' ?>>Admin</option>
        </select>
        <?php if (in_array('role', $error_Fields)) echo "*Please select a role"; ?>
        <br>

        <label for="password">New Password (leave blank to keep current)</label>
        <input type="password" name="password" id="password" class="txt">
        <?php if (in_array('password', $error_Fields)) echo "*Passwords must match and be at least 6 characters"; ?>
        <br>

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="txt">
        <br>

        <label for="file">Avatar:</label>
        <input type="file" name="avatar" id="file">
        <br>
        <?php
        if (!empty($avatar_errors)) {
            foreach ($avatar_errors as $error) {
                echo $error;
            }
        }
        ?>

        <input type="submit" name="update" value="Update" id="sub">
    </form>
</body>
</html>

<style>
.txt {
    border-radius: 5px;
    border: none;
    height: 25px;
    width: 200px;
    text-align: center;
}
select {
    border-radius: 5px;
    border: 1px solid gray;
    height: 25px;
    width: 200px;
}
form {
    margin-left: 30%;
    margin-top: 5%;
    padding: 15px 15px;
    width: 500px;
    background-color: rgba(227,230,243,255);
    border-radius: 10px;
    border: 1px solid black;
}
label {
    font-size: 17px;
    font-weight: 400;
}
#sub {
    margin-top: 15px;
    margin-left: 42%;
    width: 70px;
    color: whitesmoke;
    background-color: cadetblue;
    border: none;
    font-size: 17px;
    padding: 5px 5px;
    border-radius: 5px;
}
</style>
