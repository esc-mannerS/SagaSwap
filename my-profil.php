<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// database connection
$mysqli = new mysqli("localhost", "root", "", "sagaswap");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare and execute query for multiple columns
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT username, email, id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind result variables for each selected column
$stmt->bind_result($username, $email, $id);
$stmt->fetch();
$stmt->close();
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="da">

<head>
    <title>Brugerprofil</title>
    <link rel="icon" href="sagaswap-icon.ico" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SagaSwap Danmarks Open Source Marketplads" />
    <meta name="keywords" content="SagaSwap, Marketplace, Open Source" />
    <meta name="author" content="esc-mannerS" />
    <meta http-equiv="Content-Security-Policy" content="
      default-src 'self'; 
      style-src 'self'; 
      script-src 'self';
      font-src 'self';" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="my-profil.css" />
</head>

<body>
    <div id="container">
        <header>
            <?php include('include/header-header.php');?>
        </header>
        <main>
            <div class="main-main">
                <div class="content-container">
                    <div class="main-content">
                        <div class="head-content">
                            <h1 class="main-text">Min profil</h1>
                            <h2 class="main-text"><?php echo "Velkommen til ".htmlspecialchars($username);?></h2>
                        </div>
                        <div class="body-content profil">
                            <div class="profil-head">
                                <h3>Brugerprofil</h3>
                            </div>
                            <div class="profil-body">
                                <div class="user-profil">
                                    <div class="profil-header-column">
                                        <p>Brugernavn:</p>
                                        <p>E-mailadresse:</p>
                                        <p>Bruger id:</P>
                                    </div>
                                    <div class="profil-header-column">
                                        <p><?php echo htmlspecialchars($username); ?></p>
                                        <p><?php echo htmlspecialchars($email); ?></p>
                                        <p><?php echo htmlspecialchars($id); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body-content profil">
                            <div class="profil-head">
                                <h3>Ny annonce</h3>
                            </div>
                            <div class="profil-body">
                            </div>
                        </div>
                        <div class="body-content profil">
                            <div class="profil-head">
                                <h3>Mine annoncer</h3>
                            </div>
                            <div class="profil-body">
                            </div>
                        </div>
                        <div class="body-content profil">
                            <div class="profil-head">
                                <h3>Mine handler</h3>
                            </div>
                            <div class="profil-body">
                            </div>
                        </div>
                        <div class="body-content profil">
                            <div class="profil-head">
                                <h3>Profli indstillinger</h3>
                            </div>
                            <div class="profil-body">
                            </div>
                        </div>
                    </div>
                    <?php include('include/main-header-menu.php');?>
                </div>
            </div>
        </main>
        <footer>
            <?php include('include/footer-footer.php');?>
        </footer>
    </div>
    <script src="script.js"></script>
</body>

</html>