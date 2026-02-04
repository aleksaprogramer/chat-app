<?php

if (isset($_SESSION['user_id'])) {
    header("Location: $env->base_url" . "/?router=homepage");
    exit();
}

// CSRF token
if (!isset($_SESSION['csrf-token'])) {
    $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
}

// Handling errors
$username_error = false;
$phone_number_error = false;
$password_error = false;
$password_confirm_error = false;

// Handling request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // XSS filtering
    $csrf_token = htmlspecialchars(trim($_POST['csrf-token']));
    $username = htmlspecialchars(trim($_POST['username']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password_confirm = htmlspecialchars(trim($_POST['password-confirm']));

    // Validating data
    if (!isset($_SESSION['csrf-token']) || !hash_equals($_SESSION['csrf-token'], $csrf_token)) {
        header("Location: $env->base_url" . "/?router=register");
        exit();

    } else if ($username === '') {
        $username_error = 'Please enter the username.';

    } else if (strlen($username) < 3) {
        $username_error = 'Username cannot have less than 3 characters.';

    } else if (strlen($username) > 60) {
        $username_error = 'Username cannot have more than 60 characters.';

    } else if ($phone_number === '') {
        $phone_number_error = 'Please enter the phone number.';

    } else if (strlen($phone_number) < 10 || strlen($phone_number) > 10) {
        $phone_number_error = 'Phone number should have 10 digits.';

    } else if (!is_numeric($phone_number)) {
        $phone_number_error = 'Phone number can only contain numbers.';

    } else if (str_contains($phone_number, '=')) {
        $phone_number_error = 'Please enter the number in a valid format.';

    } else if (str_contains($phone_number, '-')) {
        $phone_number_error = 'Please enter the number in a valid format.';

    } else if ($password === '') {
        $password_error = 'Please enter password.';

    } else if (strlen($password) < 8) {
        $password_error = 'Password cannot have less than 8 characters.';

    } else if (strlen($password) > 25) {
        $password_error = 'Password cannot have more than 25 characters';

    } else if ($password !== $password_confirm) {
        $password_confirm_error = 'Password and password confirm must match.';

    } else if ($password_confirm === '') {
        $password_confirm_error = 'Please enter password confirm.';

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL prepared statement
        $sql = "INSERT INTO users (username, phone_number, hashed_pwd)
        VALUES (?, ?, ?)";

        $run = $db->prepare($sql);
        $run->bind_param("sss", $username, $phone_number, $hashed_password);
        $run->execute();

        // Getting inserted user ID
        $user_id = mysqli_insert_id($db);

        $_SESSION['user_id'] = $user_id;
        header("Location: $env->base_url" . "/?router=homepage");
        unset($_SESSION['csrf-token']);
        exit();
    }
}

mysqli_close($db);

?>

<div class="register">
    <h2>Register</h2>

    <form method="POST">
        <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token']; ?>">
        <input type="text" placeholder="Username" name="username">
        <?php if ($username_error): ?>
            <p><?php echo $username_error; ?></p>
        <?php endif; ?>

        <input type="text" placeholder="Phone number" name="phone_number" maxlength="10">
        <?php if ($phone_number_error): ?>
            <p><?php echo $phone_number_error; ?></p>
        <?php endif; ?>

        <input type="password" placeholder="Password" name="password">
        <?php if ($password_error): ?>
            <p><?php echo $password_error; ?></p>
        <?php endif; ?>

        <input type="password" placeholder="Confirm password" name="password-confirm">
        <?php if ($password_confirm_error): ?>
            <p><?php echo $password_confirm_error; ?></p>
        <?php endif; ?>

        <button type="submit">Register</button>
    </form>
</div>