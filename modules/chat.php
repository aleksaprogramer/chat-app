<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: " . $env->base_url . "?router=login");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . $env->base_url . "?router=homepage");
    exit();
}

if ($_SESSION['user_id'] === $_GET['id']) {
    header("Location: " . $env->base_url . "?router=homepage");
    exit();
}

// Getting user one
$user_one_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = $user_one_id";
$run = $db->query($sql);
$user_one = $run->fetch_assoc();

// Getting user two
$user_two_id = mysqli_real_escape_string($db, $_GET['id']);
$sql = "SELECT username FROM users WHERE id = $user_two_id";
$run = $db->query($sql);
$user_two = $run->fetch_assoc();

if (!$user_two) {
    header("Location: " . $env->base_url . "?router=homepage");
    exit();
}

?>

<div class="chat">
    <div class="container">
        <h2>Chat with <?php echo $user_two['username'] ?></h2>

        <a href="<?php echo $env->base_url . "?router=homepage" ?>" class="back-to-homepage-link">Back to homepage</a>

        <form>
            <input type="hidden" id="username" value="<?php echo $user_one['username'] ?>">
            <input type="text" placeholder="Message" id="message">
            <button type="submit" id="send-btn">Send</button>
        </form>

        <div id="messages-output"></div>
    </div>
</div>