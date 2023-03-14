<?php require_once "../global.php"; ?>
<form method="post" action="<?php echo $script; ?>">
    <input name="password" type="password" placeholder="password">
    <input name="submit" type="submit" value="hash">
</form>
<?php
    if (isset($_POST["submit"])) {
        $password = $_POST["password"];

        if (!validate_password($password)) {
            echo "invalid password. (must be between 8 and 40 chracters and contain only alphanumerical characters and symbols)";
        } else {
            echo "hash: " . hash_password($password);
        }
    }
?>