<?php
ob_start(); // Output buffering to prevent header errors
session_start();
  include("config/db.php");

$error = "";
$success = "";

// ------------------- 1. REGISTER LOGIC -------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Email check karein
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "Ye Email pehle se registered hai!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sss", $name, $email, $hashed_password);

            if (mysqli_stmt_execute($insert_stmt)) {
                $success = "Account ban gaya hai! Ab aap Sign In tab par ja kar Login kar sakte hain.";
            } else {
                $error = "Registration fail ho gaya! Dobara koshish karein.";
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    } else {
        $error = "Saare fields fill karna zaroori hai!";
    }
}

// ------------------- 2. LOGIN LOGIC -------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $_SESSION['user_id']   = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_role'] = $row['role'];

                // LOGIN SUCCESSFUL -> Redirect to main website
                header("Location: ../index.php"); 
                exit();
            } else {
                $error = "Galat Password!";
            }
        } else {
            $error = "Email nahi mila! Pehle Register karein.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Please saare fields fill karein.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sounds - Register & Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DarkPan Template CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        
                        <!-- Header Title -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="text-primary"><i class="fa fa-music me-2"></i>Sounds</h3>
                        </div>

                        <!-- Error/Success Alerts -->
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if(!empty($success)): ?>
                            <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <!-- Toggle Tabs (Register First, Login Second) -->
                        <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab" aria-controls="pills-register" aria-selected="true">Sign Up</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-controls="pills-login" aria-selected="false">Sign In</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            
                            <!-- 1. REGISTER FORM (Pehlay Dikhayega) -->
                            <div class="tab-pane fade show active" id="pills-register" role="tabpanel" aria-labelledby="pills-register-tab">
                                <form action="" method="POST">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name" class="form-control" id="regName" placeholder="Full Name" required>
                                        <label for="regName">Full Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control" id="regEmail" placeholder="name@example.com" required>
                                        <label for="regEmail">Email Address</label>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <input type="password" name="password" class="form-control" id="regPassword" placeholder="Password" required>
                                        <label for="regPassword">Password</label>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-primary py-3 w-100 mb-2">Create Account</button>
                                </form>
                            </div>

                            <!-- 2. LOGIN FORM (Jab Switch Karein) -->
                            <div class="tab-pane fade" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
                                <form action="" method="POST">
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control" id="loginEmail" placeholder="name@example.com" required>
                                        <label for="loginEmail">Email Address</label>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Password" required>
                                        <label for="loginPassword">Password</label>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-primary py-3 w-100 mb-2">Sign In & Go To Website</button>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>