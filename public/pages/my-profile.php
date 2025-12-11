<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /sagaswap/public/pages/login.php");
    exit;
}

// database connection
$mysqli = new mysqli("localhost", "root", "", "sagaswap");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare and execute query for multiple columns
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("
    SELECT u.username, u.email, u.created_at, u.id, m.name AS municipality_name
    FROM users u
    LEFT JOIN municipalities m ON u.municipality_id = m.id
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $created_at, $id, $municipality_name);
$stmt->fetch();
$stmt->close();
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="da">

<head>
    <title>Brugerprofil</title>
    <link rel="icon" href="/sagaswap/public/images/sagaswap-icon.ico" />
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
    <link rel="stylesheet" href="/sagaswap/public/css/styles.css" />
    <link rel="stylesheet" href="/sagaswap/public/css/my-profile.css" />
</head>

<body>
    <div id="container">
        <header>
            <?php include('../includes/header-header.php');?>
        </header>
        <main>
            <div class="main-main">
                <div class="content-container">
                    <div class="main-content">
                        <div class="head-content">
                            <h1 class="main-text">Min profil</h1>
                            <h2 class="main-text"><?php echo "Velkommen til ".htmlspecialchars($username);?></h2>
                        </div>
                        <div class="body-content">
                            <div class="profile-head">
                                <h3>Ny annonce</h3>
                            </div>
                            <div class="profile-body">
                            </div>
                        </div>
                        <div class="body-content">
                            <div class="profile-head">
                                <h3>Mine annoncer</h3>
                            </div>
                            <div class="profile-body">
                            </div>
                        </div>
                        <div class="body-content">
                            <div class="profile-head">
                                <h3>Mine handler</h3>
                            </div>
                            <div class="profile-body">
                            </div>
                        </div>
                        <div class="body-content">
                            <div class="profile-head">
                                <h3>Profil indstillinger</h3>
                            </div>
                            <div class="profile-body">
                                <div class="user-profile">
                                    <div class="profile-body-group">
                                        <div class="profile-body-column">
                                            <p>Brugernavn:</p>
                                            <p>E-mailadresse:</p>
                                            <p>Kommune:</P>
                                        </div>
                                        <div class="profile-body-column">
                                            <p><?php echo htmlspecialchars($username); ?></p>
                                            <p><?php echo htmlspecialchars($email); ?></p>
                                            <p><?php echo htmlspecialchars($municipality_name); ?></p>
                                        </div>
                                    </div>
                                    <div class="profile-body-group two">
                                    </div>
                                    <div class="profile-body-group three">
                                        <div class="id-created-group">
                                            <div class="profile-body-column">
                                                <p>Bruger id:</p>
                                                <p>Bruger oprettet:</P>
                                            </div>
                                            <div class="profile-body-column">
                                                <p><?php echo htmlspecialchars($id); ?></p>
                                                <p><?php echo date("d m Y", strtotime(htmlspecialchars($created_at))); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <form action="/sagaswap/public/actions/delete-user.php" method="POST"
                                            onsubmit="return confirm('Er du sikker på, at du vil slette din konto permanent? Dette kan ikke fortrydes!');">
                                            <input type="hidden" name="user_id"
                                                value="<?php echo htmlspecialchars($id); ?>">
                                            <button type="submit" class="delete-user">Slet min bruger og alt
                                                data!</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include('../includes/main-header-menu.php');?>
                </div>
            </div>
        </main>
        <footer>
            <?php include('../includes/footer-footer.php');?>
        </footer>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>