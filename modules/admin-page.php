<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: $env->base_url" . "/?router=login");
    exit();
}

// Restriction
restrict($env, $db);

// Get all users
$sql = "SELECT id, username, phone_number, created_at FROM users WHERE is_admin = 0";
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

<div class="admin-page">
    <h2>Admin Page</h2>

    <form method="POST">
        <button type="submit">Logout</button>
    </form>

    <?php if (count($users) > 0): ?>
        <div class="users-container">

            <?php foreach ($users as $user): ?>
                <div class="user">
                    <h4>Username: <?php echo $user['username'] ?></h4>
                    <h4>Phone number: <?php echo $user['phone_number'] ?></h4>
                    <h4>Created at: <?php echo $user['created_at'] ?></h4>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>