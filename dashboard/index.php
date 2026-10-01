<?php

ob_start();
session_start();

include("./config/db.php");

$error = "";
$success = "";

/* =========================
   REGISTER
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {

            $error = "Ye Email pehle se registered hai!";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert_stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert_stmt,
                "sss",
                $name,
                $email,
                $hashed_password
            );

            if (mysqli_stmt_execute($insert_stmt)) {

                $success = "Account successfully ban gaya! Ab Login tab par ja kar Sign In karein.";

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


/* =========================
   LOGIN
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, password, role FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {

            if (
                password_verify($password, $row['password'])
                ||
                $password === $row['password']
            ) {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_role'] = $row['role'];

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

    <title>DarkPan - Sounds Dashboard</title>

    <meta
        content="width=device-width, initial-scale=1.0"
        name="viewport"
    >

    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Bootstrap -->
    <link
        href="css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Style -->
    <link
        href="css/style.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container-fluid position-relative d-flex p-0">

    <!-- Sidebar -->
    <div class="sidebar pe-4 pb-3">

        <nav class="navbar bg-secondary navbar-dark">

            <a
                href="index.php"
                class="navbar-brand mx-4 mb-3"
            >
                <h3 class="text-primary">
                    <i class="fa fa-user-edit me-2"></i>
                    DarkPan
                </h3>
            </a>

            <div class="d-flex align-items-center ms-4 mb-4">

                <div class="position-relative">

                    <img
                        class="rounded-circle"
                        src="img/user.jpg"
                        alt=""
                        style="width:40px;height:40px;"
                    >

                    <div
                        class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"
                    ></div>

                </div>

                <div class="ms-3">

                    <h6 class="mb-0">
                        Portal Access
                    </h6>

                    <span>
                        Sounds User
                    </span>

                </div>

            </div>

            <div class="navbar-nav w-100">

                <a
                    href="index.php"
                    class="nav-item nav-link active"
                >
                    <i class="fa fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>

            </div>

        </nav>

    </div>


    <!-- Content -->

    <div class="content">

        <!-- Top Navbar -->

        <nav
            class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0"
        >

            <a
                href="index.php"
                class="navbar-brand d-flex d-lg-none me-4"
            >
                <h2 class="text-primary mb-0">
                    <i class="fa fa-user-edit"></i>
                </h2>
            </a>

        </nav>


        <!-- Forms -->

        <div class="container-fluid pt-4 px-4">

            <div class="row g-4 justify-content-center">

                <div class="col-12 col-xl-8">

                    <div class="bg-secondary rounded p-4 p-sm-5">


                        <!-- Error -->

                        <?php if (!empty($error)): ?>

                            <div
                                class="alert alert-danger"
                                role="alert"
                            >
                                <?php echo htmlspecialchars($error); ?>
                            </div>

                        <?php endif; ?>


                        <!-- Success -->

                        <?php if (!empty($success)): ?>

                            <div
                                class="alert alert-success"
                                role="alert"
                            >
                                <?php echo htmlspecialchars($success); ?>
                            </div>

                        <?php endif; ?>


                        <!-- Tabs -->

                        <ul
                            class="nav nav-pills nav-justified mb-4"
                            id="pills-tab"
                            role="tablist"
                        >

                            <li
                                class="nav-item"
                                role="presentation"
                            >

                                <button
                                    class="nav-link active"
                                    id="pills-register-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-register"
                                    type="button"
                                    role="tab"
                                >
                                    Register
                                </button>

                            </li>


                            <li
                                class="nav-item"
                                role="presentation"
                            >

                                <button
                                    class="nav-link"
                                    id="pills-login-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-login"
                                    type="button"
                                    role="tab"
                                >
                                    Login
                                </button>

                            </li>

                        </ul>


                        <div
                            class="tab-content"
                            id="pills-tabContent"
                        >


                            <!-- REGISTER -->

                            <div
                                class="tab-pane fade show active"
                                id="pills-register"
                                role="tabpanel"
                            >

                                <form
                                    action=""
                                    method="POST"
                                >

                                    <div class="form-floating mb-3">

                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control"
                                            id="regName"
                                            placeholder="Full Name"
                                            required
                                        >

                                        <label for="regName">
                                            Full Name
                                        </label>

                                    </div>


                                    <div class="form-floating mb-3">

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            id="regEmail"
                                            placeholder="name@example.com"
                                            required
                                        >

                                        <label for="regEmail">
                                            Email Address
                                        </label>

                                    </div>


                                    <div class="form-floating mb-4">

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            id="regPassword"
                                            placeholder="Password"
                                            required
                                        >

                                        <label for="regPassword">
                                            Password
                                        </label>

                                    </div>


                                    <button
                                        type="submit"
                                        name="register"
                                        class="btn btn-danger py-3 w-100 mb-2"
                                    >
                                        Create Account
                                    </button>

                                </form>

                            </div>


                            <!-- LOGIN -->

                            <div
                                class="tab-pane fade"
                                id="pills-login"
                                role="tabpanel"
                            >

                                <form
                                    action=""
                                    method="POST"
                                >

                                    <div class="form-floating mb-3">

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            id="loginEmail"
                                            placeholder="name@example.com"
                                            required
                                        >

                                        <label for="loginEmail">
                                            Email Address
                                        </label>

                                    </div>


                                    <div class="form-floating mb-4">

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            id="loginPassword"
                                            placeholder="Password"
                                            required
                                        >

                                        <label for="loginPassword">
                                            Password
                                        </label>

                                    </div>


                                    <button
                                        type="submit"
                                        name="login"
                                        class="btn btn-danger py-3 w-100 mb-2"
                                    >
                                        Sign In & Go To Website
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- JavaScript -->

<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>

<script src="js/main.js"></script>

</body>

</html>