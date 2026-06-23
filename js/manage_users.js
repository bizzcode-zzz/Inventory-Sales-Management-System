 
/* =========================================
  Load users
========================================= */
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

}); 
/* =========================================
  Delete users
========================================= */

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

/* =========================================
  Edit users
========================================= */
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

 

function resetPassword(id) {

    let newPassword =
    prompt(
        "Enter New Password"
    );

    if (
        !newPassword
    ) {
        return;
    }

    fetch(
        "api_reset_password.php",
        {
            method: "POST",

            headers: {
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
            "id=" + id +
            "&password=" +
            encodeURIComponent(
                newPassword
            )
        }
    )

    .then(res => res.text())

    .then(data => {

        alert(data);

    });

}