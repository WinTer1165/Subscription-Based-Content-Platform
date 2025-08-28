$(document).ready(function () {
    console.log('Document is ready.');

    $.ajax({
        url: '../php/get_admin_info.php', 
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('get_admin_info.php response:', response);
            if (!response.logged_in) { 
                window.location.href = "login.html";
            } else {
                fetchUsers();
            }
        },
        error: function (xhr, status, error) {
            console.error('Error checking admin login:', error);
            alert('An error occurred while checking login status.');
        }
    });

    function fetchUsers() {
        console.log('Fetching users...');
        $.ajax({
            url: '../php/get_users.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log('get_users.php response:', response);
                if (response.success) {
                    populateUserTable(response.users);
                } else {
                    alert('Failed to fetch users: ' + response.message);
                    if (response.redirect) {
                        window.location.href = 'login.html';
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching users:', error);
                console.error('Response:', xhr.responseText);
                alert('An error occurred while fetching users.');
            }
        });
    }

    function populateUserTable(users) {
        console.log('Populating user table with:', users);
        const tbody = $('#userTable tbody');
        tbody.empty(); // Clear existing rows

        users.forEach(function (user) {
            const row = `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.full_name}</td>
                    <td>${user.phone}</td>
                    <td>${user.subscription_level}</td>
                    <td>${user.payment_method}</td>
                    <td>
                        <button class="btn btn-delete" data-id="${user.id}">Delete</button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Handle delete user action
    $('#userTable').on('click', '.btn-delete', function () {
        const userId = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            deleteUser(userId);
        }
    });

    function deleteUser(userId) {
        console.log('Deleting user with ID:', userId);
        $.ajax({
            url: '../php/delete_user.php',
            type: 'POST',
            data: { id: userId },
            dataType: 'json',
            success: function (response) {
                console.log('delete_user.php response:', response);
                if (response.success) {
                    alert('User deleted successfully.');
                    fetchUsers(); // Refresh the user list
                } else {
                    alert('Error deleting user: ' + response.message);
                    if (response.redirect) {
                        window.location.href = 'login.html';
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error deleting user:', error);
                alert('An error occurred while deleting the user.');
            }
        });
    }
});
