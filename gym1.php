<?php include("./includes/header.php"); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: #0f172a;
            color: white;
        }
        
        .hero {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .animated-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            opacity: 0.3;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            filter: blur(80px);
        }
        
        .logo {
            font-weight: 700;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }
        
        .logo::after {
            content: "HUB";
            position: absolute;
            font-weight: 700;
            background: linear-gradient(90deg, #6366f1, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            left: 40px;
        }
        
        .login-btn {
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(45deg, #6366f1, #ec4899);
            transition: all 0.5s ease;
            z-index: -1;
        }
        
        .login-btn:hover::before {
            width: 100%;
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .play-btn {
            position: relative;
            transition: transform 0.3s ease;
        }
        
        .play-btn:hover {
            transform: scale(1.1);
        }
        
        .play-btn::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            z-index: -1;
            opacity: 0;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(0.95);
                opacity: 0.7;
            }
            70% {
                transform: scale(1.1);
                opacity: 0;
            }
            100% {
                transform: scale(0.95);
                opacity: 0;
            }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .fitness-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            transform: translateY(50px);
            opacity: 0;
        }
        
        .fitness-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
        
        .cta-section {
            background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.95)), 
                        url('/api/placeholder/1920/1080') center/cover no-repeat;
            position: relative;
            overflow: hidden;
        }
        
        .cta-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.4), rgba(236, 72, 153, 0.2));
            filter: blur(80px);
            z-index: 0;
        }
        .parallax {
            background-image: url('assetes/images/');
            min-height: 100vh;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="fixed top-0 left-0 w-full py-4 px-6 md:px-12 flex justify-between items-center z-50 bg-opacity-10 bg-black backdrop-filter backdrop-blur-lg border-b border-gray-800">
        <div class="flex items-center">
            <span class="text-2xl logo">FIT</span>
        </div>
        <div>
    <a href="login.php">
        <button class="login-btn px-6 py-2 rounded-full text-white font-medium">Login</button>
    </a>
</div>
    </nav>

    <!-- Hero Section with Animation -->
    <section class="hero flex items-center justify-center px-4 md:px-12">
        <!-- Animated Background -->
        <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover">
        <source src="assetes/images/g2.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
        
        <div class="container mx-auto hero-content">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 mb-12 md:mb-0" id="hero-text">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 opacity-0">
                        <span class="gradient-text">Transform Your Body,</span><br>
                        <span class="gradient-text">Transform Your Life</span>
                    </h1>
                    <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-lg opacity-0">
                        Join our community of fitness enthusiasts and achieve your health goals with personalized training plans and expert guidance.
                    </p>
                    <div class="flex items-center space-x-6 opacity-0">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                            Get Started
                        </button>
                        <div class="flex items-center cursor-pointer group">
                            <div class="play-btn bg-white text-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-300 group-hover:text-white transition-colors">Watch how it works</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 md:px-12 relative overflow-hidden " id="features">
        <div class="container mx-auto">
            <div class="text-center mb-16 opacity-0" id="features-heading">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Why Choose <span class="gradient-text">FITHUB</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto">We combine cutting-edge technology with expert coaching to help you achieve your fitness goals faster.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="fitness-card rounded-xl p-6">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Personal Training</h3>
                    <p class="text-gray-400">Get customized workout plans designed specifically for your body type and fitness goals.</p>
                </div>
                
                <!-- Feature Card 2 -->
                <div class="fitness-card rounded-xl p-6">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Nutrition Planning</h3>
                    <p class="text-gray-400">Expert nutritional guidance to complement your fitness journey and maximize results.</p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="fitness-card rounded-xl p-6">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Progress Tracking</h3>
                    <p class="text-gray-400">Advanced tools to monitor your improvement and stay motivated throughout your journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-24 px-4 md:px-12">
        <div class="cta-circle left-0 top-0"></div>
        <div class="cta-circle right-0 bottom-0"></div>
        
        <div class="container mx-auto relative z-10">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6" id="cta-heading">Ready to Start Your <span class="gradient-text">Fitness Journey</span>?</h2>
                <p class="text-gray-300 mb-8">Join thousands who have already transformed their lives with FITHUB's personalized approach to fitness.</p>
                <div>
                <a href="log.php">
                <button class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-indigo-600 hover:to-purple-600 text-white font-bold px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    Join FITHUB Today
                </button>
                </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 px-4 md:px-12">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <span class="text-2xl logo">FIT</span>
                    <p class="text-gray-400 mt-2">Transform Your Body, Transform Your Life</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 FITHUB. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize GSAP
            gsap.registerPlugin(ScrollTrigger);
            
            // Hero Section Animations
            const heroTimeline = gsap.timeline();
            heroTimeline.to("#hero-text h1", { opacity: 1, y: 0, duration: 1, ease: "power3.out" })
                .to("#hero-text p", { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, "-=0.5")
                .to("#hero-text div", { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, "-=0.5")
                .to("#hero-image", { opacity: 1, duration: 1, ease: "power3.out" }, "-=0.5");
            
            // Features Section Animations
            gsap.to("#features-heading", {
                scrollTrigger: {
                    trigger: "#features",
                    start: "top 80%",
                    toggleActions: "play none none none"
                },
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            });
            
            // Feature Cards Animation
            gsap.utils.toArray(".fitness-card").forEach((card, i) => {
                gsap.to(card, {
                    scrollTrigger: {
                        trigger: card,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    },
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: i * 0.2,
                    ease: "power3.out"
                });
            });
            
            // CTA Section Animation
            gsap.from("#cta-heading", {
                scrollTrigger: {
                    trigger: ".cta-section",
                    start: "top 70%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 50,
                duration: 1,
                ease: "power3.out"
            });
            
            // Moving Background Circles
            gsap.to(".circle:nth-child(1)", {
                x: "20%",
                y: "10%",
                duration: 15,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
            
            gsap.to(".circle:nth-child(2)", {
                x: "-15%",
                y: "-10%",
                duration: 12,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
            
            gsap.to(".circle:nth-child(3)", {
                x: "10%",
                y: "15%",
                duration: 18,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
            
            // Parallax Effect on Scroll
            gsap.to(".hero-content", {
                y: (i, target) => -ScrollTrigger.maxScroll(window) * 0.15,
                ease: "none",
                scrollTrigger: {
                    start: 0,
                    end: "max",
                    invalidateOnRefresh: true,
                    scrub: 0.6
                }
            });
        });
    </script>
