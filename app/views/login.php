<?php include_once __DIR__ . '/layout/header_simple.php'; ?>

<style>
    :root {
        --primary-color: #6c5ce7;
        --secondary-color: #e8f0e8;
        --text-color: #333;
        --light-text-color: #fff;
        --error-color: #ff4d4d;
        --success-color: #28a745;
    }

    body {
        background-color: var(--secondary-color);
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        display: flex;
        width: 100%;
        max-width: 960px; /* Adjusted max-width */
        height: 600px; /* Fixed height */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .login-banner {
        background: linear-gradient(135deg, #033b35, #d5932f, #788e87);
        color: var(--light-text-color);
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 50%;
    }

    .login-banner img {
        max-width: 250px;
    }

    .login-form-container {
        padding: 60px;
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .mobile-logo {
        display: none; /* Hidden by default */
        max-width: 150px;
        margin-bottom: 30px;
    }

    .login-form-container h2 {
        font-size: 2.2rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
        text-align: center; /* Center align */
    }

    .login-form-container .sub-heading {
        color: #777;
        margin-bottom: 30px;
        text-align: center; /* Center align */
    }

    .form-group {
        margin-bottom: 25px;
        position: relative;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .password-wrapper {
        position: relative;
    }

    #togglePassword {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
    }

    .btn-login {
        background-color: var(--primary-color);
        color: var(--light-text-color);
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-login:hover {
        background-color: #5a4cdb;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 992px) {
        .login-banner {
            display: none;
        }
        .login-form-container {
            width: 100%;
            padding: 40px;
            justify-content: center;
            align-items: center; /* Center items for mobile view */
        }
        .login-container {
            flex-direction: column;
            height: auto;
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            border-radius: 0;
            box-shadow: none;
        }
        .mobile-logo {
            display: block; /* Show logo on mobile */
        }
    }
</style>

<div class="login-container">
    <div class="login-banner">
        <img src="/assets/img/LUNA WHITE.png" alt="Logo">
    </div>
    <div class="login-form-container">
        <img src="/assets/img/LUNA.png" alt="Mobile Logo" class="mobile-logo">
        <h2>Sign In</h2>
        <p class="sub-heading"> </p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="/login" method="POST" style="width: 100%; max-width: 400px;">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </div>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('bi-eye');
    });
</script>

<?php include_once __DIR__ . '/layout/footer_simple.php'; ?>
