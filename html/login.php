<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="assets/login.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>

<body class="bg-color">
    <div class="container mt-5">
        <!-- Page heading -->
        <h2 class="text-center">Login</h2>
        
        <!-- Error message display -->
        <div id="error" class="alert alert-danger" style="display: none;"></div>
        
        <!-- Login form -->
        <form id="loginForm">
            <!-- Username input -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <!-- Password input -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <!-- Submit button -->
            <button type="submit" class="button mt-3 ">Login</button>
        </form>
    </div>

    <!-- Script files -->
    <script src="login.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
</body>

</html>
