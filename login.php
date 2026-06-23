<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mydb");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users
        WHERE username='$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    if (
        password_verify(
            $password,
            $row["password"]
        )
    ) {

        $_SESSION["user_id"] =
        $row["id"];

        $_SESSION["user"] =
        $username;

        $_SESSION["role"] =
        $row["role"];

        header("Location: dashboard.php");
        exit();

    } else {

        echo "Invalid Password";

    }

} else {

    echo "User Not Found";

}
}
$conn->close();
?>

<form method="POST" action="login.php">

    <h2>Login</h2>

    <input type="text" name="username" placeholder="Username" required>
    <br><br>

    <input type="password" name="password" placeholder="Password" required>
    <br><br>

    <button type="submit">Login</button>

</form>