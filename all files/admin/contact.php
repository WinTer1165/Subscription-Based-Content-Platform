<?php
session_start();
include '../php/config.php';

// Checking if admin logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Deleting message
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM contactmessages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Message deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting message.";
    }

    $stmt->close();
    header("Location: ../admin/contact.php");
    exit();
}


$sql = "SELECT * FROM contactmessages ORDER BY date_submitted DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages | Streamora Admin</title>
    <link rel="stylesheet" href="../admin-css/admin-message.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Streamora Admin</h1>
            <nav>
                <ul>
                    <li><a href="../admin/dashboard.html">Dashboard</a></li>
                    <li><a href="../admin/user.html">Manage Users</a></li>
                    <li><a href="../admin/contact.php" class="active">View Message</a></li>
                    <li><a href="../admin/upload.html">Upload Video</a></li>
                    <li><a href="../php/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>Contact Messages</h1>

            <?php if (isset($_SESSION['message'])) { ?>
                <div class="message">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php } ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                            <td><?php echo $row['date_submitted']; ?></td>
                            <td>
                                <a class="btn btn-delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php if ($result->num_rows == 0) { ?>
                <p>No messages found.</p>
            <?php } ?>
        </div>
    </main>

    <?php $conn->close(); ?>
    <footer>
        <div class="container">
            <p>&copy; 2024 Streamora. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>