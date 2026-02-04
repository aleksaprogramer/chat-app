<?php

// CSRF token
if (!isset($_SESSION['csrf-token'])) {
    $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
}

// Handling errors
$phone_number_error = false;
$password_error = false;

// Handling request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // XSS filtering
    $csrf_token = htmlspecialchars(trim($_POST['csrf-token']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $password = htmlspecialchars(trim($_POST['password']));

    $filtered_phone_number = filter_var($phone_number, FILTER_SANITIZE_NUMBER_INT);

    // Validating data
    if (!isset($_SESSION['csrf-token']) || !isset($_POST['csrf-token']) || !hash_equals($_SESSION['csrf-token'], $csrf_token)) {
        header("Location: $env->base_url" . "/?router=login");
        exit();

    } else if ($phone_number === '') {
        $phone_number_error = 'Please enter phone number.';

    } else if (strlen($phone_number) < 10 || strlen($phone_number) > 10) {
        $phone_number_error = 'Phone number can only have 10 digits.';

    } else if (!is_numeric($phone_number)) {
        $phone_number_error = 'Phone number can contain only numbers.';

    } else if (str_contains($phone_number, '=')) {
        $phone_number_error = 'Phone number can contain only numbers.';

    } else if (str_contains($phone_number, '-')) {
        $phone_number_error = 'Phone number can contain only numbers.';

    } else if ($password === '') {
        $password_error = 'Please enter password.';

    } else {

        $sql = "SELECT id, hashed_pwd FROM users WHERE phone_number = $filtered_phone_number";
        $run = $db->query($sql);
        $results = $run->fetch_assoc();

        $current_user = $results;

        if (!$current_user) {
            $phone_number_error = 'Incorrect data.';

        } else if (!password_verify($password, $current_user['hashed_pwd'])) {
            $password_error = 'Incorrect data.';

        } else {

            $logged_user = $current_user;
            $_SESSION['user_id'] = $logged_user['id'];
            header("Location: $env->base_url" . "/?router=homepage");
            unset($_SESSION['csrf-token']);
            exit();
        }
    }
}

?>

<div class="login">
    <h2>Login</h2>

    <form method="POST">
        <input type="hidden" name="csrf-token" value="<?php echo $_SESSION['csrf-token'] ?>">
        <input type="text" placeholder="Phone number" name="phone_number" maxlength="10">
        <?php if ($phone_number_error): ?>
            <p><?php echo $phone_number_error; ?></p>
        <?php endif; ?>

        <input type="password" placeholder="Password" name="password">
        <?php if ($password_error): ?>
            <p><?php echo $password_error; ?></p>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>
</div>