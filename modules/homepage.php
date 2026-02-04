<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: $env->base_url" . "/?router=login");
    exit();
}

// Restriction
restrict($db);

// Get all users
$logged_user_id = $_SESSION['user_id'];

$sql = "SELECT id, username, phone_number FROM users WHERE NOT id = $logged_user_id";
$run = $db->query($sql);
$results = $run->fetch_all(MYSQLI_ASSOC);

$users = $results;

// foreach ($users as $user) {
//     echo $user['phone_number'];
// }

var_dump(count($users));

?>

<div class="homepage">
    <h2>Homepage</h2>

    <?php if (count($users) > 0): ?>
        <div class="users-container">

            <?php foreach ($users as $user): ?>
                <a href="#"><?php echo $user['username'] ?> <?php echo $user['phone_number'] ?></a><br><br>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>