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

 <script>

function loadUsers() {

    fetch("fetch_users.php")
    .then(res => res.text())
    .then(data => {

        document.getElementById(
            "userList"
        ).innerHTML = data;

    });

}

document.addEventListener(
    "DOMContentLoaded",
    loadUsers
);

document
.getElementById("userForm")
.addEventListener(
"submit",

function(e) {

    e.preventDefault();

    let formData =
    new FormData(this);

    fetch(
        "api_add_user.php",
        {
            method: "POST",
            body: formData
        }
    )

    .then(res => res.text())

    .then(data => {

        if (data === "success") {

            alert("User Added");

            this.reset();

            loadUsers();

        }

    });

}); // <-- IMPORTANTE

function deleteUser(id) {

    if (
        !confirm(
            "Delete this user?"
        )
    ) {
        return;
    }

    fetch(
        "api_delete_user.php",
        {
            method: "POST",

            headers: {
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
            "id=" + id
        }
    )

    .then(res => res.text())

    .then(data => {

        alert(data);

        loadUsers();

    });

}


function editUser(
    id,
    username,
    role
) {

    document.getElementById(
        "editId"
    ).value = id;

    document.getElementById(
        "editUsername"
    ).value = username;

    document.getElementById(
        "editRole"
    ).value = role;

}

document
.getElementById(
"editUserForm"
)

.addEventListener(
"submit",

function(e) {

    e.preventDefault();

    let formData =
    new FormData(this);

    fetch(
        "api_update_user.php",
        {
            method: "POST",
            body: formData
        }
    )

    .then(res => res.text())

    .then(data => {

        if (
            data === "success"
        ) {

            alert(
                "User Updated"
            );

            loadUsers();

        }

    });

});

</script>