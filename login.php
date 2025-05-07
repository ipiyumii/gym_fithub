
<?php include("./includes/header.php"); ?>
<body class="bg-gray-900 min-h-screen">
    <div class="flex justify-center items-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-8">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-400">FITHUB</h1>
                <p class="text-pink-400 mt-2">Welcome back, please login</p>
            </div>
            
            <!-- Login Form -->
            <form id="loginForm" action="#" method="POST" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email Address</label>
                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                            class="w-full pl-10 pr-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="flex justify-between text-sm font-medium">
                        <span class="text-gray-300">Password</span>
                        <a href="#" class="text-blue-400 hover:underline">Forgot password?</a>
                    </label>
                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="togglePassword">
                            <i class="fas fa-eye text-gray-400"></i>
                        </span>
                    </div>
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>
                </div>
                
                <!-- Two-Factor Authentication Option -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="twoFactor" name="twoFactor" type="checkbox"
                            class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                        <label for="twoFactor" class="ml-2 block text-sm text-gray-300">Use two-factor authentication</label>
                    </div>
                    <div>
                        <i class="fas fa-question-circle text-gray-400 cursor-pointer" title="Enable for extra security"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Sign In
                    </button>
                </div>
                
                <!-- Don't have account -->
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-300">
                        Don't have an account yet? <a href="register.php" class="text-blue-400 hover:underline">Register</a>
                    </p>
                </div>
                
                <!-- Social Login -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-gray-800 text-gray-400">Or continue with</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <div>
                            <a href="#" class="w-full flex items-center justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-sm font-medium text-gray-200 hover:bg-gray-600">
                                <i class="fab fa-google text-red-400"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#" class="w-full flex items-center justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-sm font-medium text-gray-200 hover:bg-gray-600">
                                <i class="fab fa-facebook text-blue-400"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#" class="w-full flex items-center justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-gray-700 text-sm font-medium text-gray-200 hover:bg-gray-600">
                                <i class="fab fa-apple text-gray-200"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form fields
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            // Simple validation
            if (!email || !password) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // If all validations pass
            alert('Login successful! Redirecting to dashboard...');
            window.location.href = 'home.php'; // Redirect to home.php
        });
    </script>