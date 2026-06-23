<?php

session_start();

 

if (
    !isset($_SESSION["role"])
    ||
    $_SESSION["role"] !== "admin"
) {

    die("Access Denied");

}

include "db.php";

?>

<h2>User Management</h2>

<form id="userForm">

    <input
        type="text"
        name="username"
        placeholder="Username"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="Password"
        required
    >

    <select name="role">

        <option value="staff">
            Staff
        </option>

        <option value="admin">
            Admin
        </option>

    </select>

    <button type="submit">

        Add User

    </button>

</form>

<hr>

<h3>Edit User</h3>

<form id="editUserForm">

    <input
        type="hidden"
        id="editId"
        name="id"
    >

    <input
        type="text"
        id="editUsername"
        name="username"
        placeholder="Username"
        required
    >

    <select
        id="editRole"
        name="role"
    >

        <option value="staff">
            Staff
        </option>

        <option value="admin">
            Admin
        </option>

    </select>

    <button type="submit">

        Update User

    </button>

</form>


<div id="userList"></div>

 <script src="js/manage_users.js"></script>