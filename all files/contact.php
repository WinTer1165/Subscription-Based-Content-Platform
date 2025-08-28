<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us | Premium Content Streaming Platform</title>
    <link rel="icon" type="image/x-icon" href="../images/live-streaming.png">
    <link rel="stylesheet" href="../user-css/user-contact.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <header>
        <nav>
            <ul>

                <li><a href="browse.html" >Browse</a></li>
                <li><a href="contact.php" class="active">Contact Us</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section id="contact">
        <div class="container">
            <h1>Contact Us</h1>
            <p>If you have any questions, comments, or concerns, please fill out the form below, and we'll get back to you as soon as possible.</p>
            
            <?php
            //form submission success and fail message
            if (isset($_SESSION['form_success'])) {
                echo "<p class='form-message form-success'>{$_SESSION['form_success']}</p>";
                unset($_SESSION['form_success']);
            } elseif (isset($_SESSION['form_error'])) {
                echo "<p class='form-message form-error'>{$_SESSION['form_error']}</p>";
                unset($_SESSION['form_error']);
            }
            ?>
            <form action="contact_process.php" method="post">
                <label for="name"><i class="fas fa-user"></i> Name:</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>

                <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                <input type="email" id="email" name="email" placeholder="Your Email" required>

                <label for="subject"><i class="fas fa-tag"></i> Subject:</label>
                <input type="text" id="subject" name="subject" placeholder="Subject" required>

                <label for="message"><i class="fas fa-comment-dots"></i> Message:</label>
                <textarea id="message" name="message" rows="6" placeholder="Your Message" required></textarea>

                <button type="submit"><i class="fas fa-paper-plane"></i> Submit</button>
            </form>
        </div>
    </section>
    <script>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            window.location.href = "login.html";
        <?php } ?>
    </script>
</body>

</html>