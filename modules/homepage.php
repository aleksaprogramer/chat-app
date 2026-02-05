<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: $env->base_url" . "/?router=login");
    exit();
}

// Get all users
$logged_user_id = $_SESSION['user_id'];

$sql = "SELECT id, username, phone_number FROM users WHERE NOT id = $logged_user_id";
$run = $db->query($sql);
$results = $run->fetch_all(MYSQLI_ASSOC);

$users = $results;

// Handling request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_destroy();
    header("Location: $env->base_url" . "/?router=login");
    exit();
}

?>

<div class="homepage">
    <div class="container">
        <h2>Homepage</h2>

        <form method="POST">
            <button type="submit">Logout</button>
        </form>

        <?php if (count($users) > 0): ?>
            <div class="users-container">

                <?php foreach ($users as $user): ?>
                    <a href="<?php echo $env->base_url . "/?router=chat&id=" . $user['id'] ?>"><span>User:</span> <?php echo $user['username'] ?></a>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>
</div>