<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Sign In - HomeZen</title>
	<link rel="icon" type="image/x-icon" href="/favicon.ico">

	<!-- Bootstrap 5 CSS CDN -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Icons CDN -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<style>
		body.login-page {
			position: relative;
			min-height: 100vh;
			font-family: 'Inter', sans-serif;
			background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
			margin: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.login-content {
			width: 100%;
			max-width: 440px;
			padding: 20px;
			z-index: 2;
		}

		.login-card {
			background: #ffffff;
			border-radius: 16px;
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
			overflow: hidden;
		}

		.login-card-header {
			background: linear-gradient(135deg, #2c6f9b, #1e5578);
			padding: 30px 0 20px;
			text-align: center;
			color: #fff;
		}

		.login-card-header .logo-circle {
			width: 64px;
			height: 64px;
			background: #fff;
			border-radius: 50%;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		}

		.login-card-body {
			padding: 30px 36px 36px;
		}

		.form-control {
			border: 1.5px solid #e2e8f0;
			border-radius: 10px;
			padding: 10px 14px;
			font-size: 14px;
			color: #1e293b;
			transition: all 0.2s ease;
		}

		.form-control:focus {
			border-color: #2c6f9b;
			box-shadow: 0 0 0 4px rgba(44, 111, 155, 0.12);
		}

		.btn-primary {
			background: #2c6f9b;
			border: none;
			border-radius: 10px;
			padding: 12px;
			font-weight: 600;
			font-size: 15px;
			transition: all 0.2s ease;
		}

		.btn-primary:hover {
			background: #1e5578;
		}

		.custom-alert {
			position: fixed;
			top: 24px;
			right: 24px;
			z-index: 9999;
			min-width: 300px;
			border-radius: 10px;
			border: none;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
		}
	</style>
</head>

<body class="login-page">

	<div class="login-content">
		<div class="login-card">
			<div class="login-card-header">
				<div class="logo-circle">
					<i class="bi bi-house-heart-fill fs-3 text-primary"></i>
				</div>
				<h4 class="mt-3 fw-bold text-white mb-0">HomeZen</h4>
				<p class="small text-white-50 mb-0">Household Management System</p>
			</div>

			<div class="login-card-body">
				<div class="mb-4 text-center">
					<h5 class="fw-bold text-dark mb-1">Welcome Back</h5>
					<p class="text-muted small mb-0">Sign in to access your dashboard</p>
				</div>

				<form id="loginForm" class="row g-3">
					@csrf

					<div class="col-12">
						<label class="form-label fw-semibold text-secondary small mb-1">Email Address</label>
						<div class="input-group">
							<span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;">
								<i class="bi bi-envelope"></i>
							</span>
							<input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="name@homezen.com" autocomplete="email" required>
						</div>
						<span class="text-danger small mt-1 d-block" id="email_error"></span>
					</div>

					<div class="col-12">
						<label class="form-label fw-semibold text-secondary small mb-1">Password</label>
						<div class="input-group">
							<span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;">
								<i class="bi bi-key"></i>
							</span>
							<input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••" required>
							<button type="button" class="btn btn-light border border-start-0 text-muted" id="togglePassword" style="border-radius: 0 10px 10px 0;">
								<i class="bi bi-eye" id="togglePasswordIcon"></i>
							</button>
						</div>
						<span class="text-danger small mt-1 d-block" id="password_error"></span>
					</div>

					<div class="col-12">
						<div class="form-check mb-0">
							<input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
							<label class="form-check-label text-muted small" for="rememberMe">Remember me</label>
						</div>
					</div>

					<div class="col-12 mt-4">
						<button class="btn btn-primary w-100 shadow-sm" type="submit" id="loginButton">
							<i class="bi bi-box-arrow-in-right me-1"></i> Sign In
						</button>
					</div>
				</form>

				<div class="text-center mt-4">
					<small class="text-muted">© {{ date('Y') }} HomeZen. All rights reserved.</small>
				</div>
			</div>
		</div>
	</div>

	<!-- Vendor JS CDN Files -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#togglePassword').on('click', function() {
				const passField = $('#password');
				const icon = $('#togglePasswordIcon');
				if (passField.attr('type') === 'password') {
					passField.attr('type', 'text');
					icon.removeClass('bi-eye').addClass('bi-eye-slash');
				} else {
					passField.attr('type', 'password');
					icon.removeClass('bi-eye-slash').addClass('bi-eye');
				}
			});

			$('input').on('input', function() {
				$(this).removeClass('is-invalid');
				$('#' + $(this).attr('name') + '_error').html('');
			});

			$('#loginForm').on('submit', function(e) {
				e.preventDefault();
				$('.text-danger').html('');

				const email = $('#email').val().trim();
				const password = $('#password').val().trim();
				let isValid = true;

				if (!email) {
					$('#email_error').html('Please provide your email.');
					isValid = false;
				}
				if (!password) {
					$('#password_error').html('Please enter your password.');
					isValid = false;
				}

				if (!isValid) return;

				const btn = $('#loginButton');
				btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Authenticating...');

				$.ajax({
					url: "{{ route('login.submit') ?? '#' }}",
					type: "POST",
					data: {
						_token: "{{ csrf_token() }}",
						email: email,
						password: password,
						remember: $('#rememberMe').is(':checked') ? 1 : 0
					},
					success: function(response) {
						if (response.status === 'success') {
							showAlert('success', response.message);
							setTimeout(function() {
								window.location.href = response.redirect;
							}, 800);
						} else {
							showAlert('danger', response.message);
							btn.prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-1"></i> Sign In');
						}
					},
					error: function() {
						showAlert('danger', 'Unable to process request. Please try again.');
						btn.prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-1"></i> Sign In');
					}
				});
			});
		});

		function showAlert(type, message) {
			$('.custom-alert').remove();
			const alertBox = `
                <div class="alert alert-${type} custom-alert alert-dismissible fade show text-start" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger'} fs-5"></i>
                        <span class="fw-medium">${message}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
			$('body').append(alertBox);
			$('.custom-alert').fadeIn();
			setTimeout(function() {
				$('.custom-alert').fadeOut(function() {
					$(this).remove();
				});
			}, 4000);
		}
	</script>
</body>

</html>