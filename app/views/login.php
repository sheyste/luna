<?php include_once __DIR__ . '/layout/header_simple.php'; ?>

<style>
    body {
        background-color: #f3f4f6; /* AdminCAST page background */
    }
    .login-card {
        border: none;
        border-radius: 0.5rem;
    }
    .login-card-header {
        background-color: #293145; /* AdminCAST sidebar color */
        color: #fff;
        padding: 1.5rem;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .login-card-header .h2 {
        font-weight: 600;
    }
    .login-logo {
        max-height: 200px;
        margin-bottom: 0.1rem;
    }
</style>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-lg-4 col-md-6">
            <div class="card shadow login-card">
                <div class="login-card-header text-center">
                    <!-- Logo above text -->
                    <img src="/assets/img/LUNA WHITE.png" alt="Logo" class="login-logo">
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">Sign in to continue</p>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center p-2"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control form-control-lg" id="username" name="username" required placeholder="">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/layout/footer_simple.php'; ?>
