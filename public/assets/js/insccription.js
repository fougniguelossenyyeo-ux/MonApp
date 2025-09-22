  // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const toggleButton = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const logoText = document.getElementById('logoText');
        const userText = document.getElementById('userText');
        const logoutText = document.getElementById('logoutText');
        const mainContent = document.getElementById('mainContent');
        
        // Navigation text elements
        const dashboardText = document.getElementById('dashboardText');
        const registerText = document.getElementById('registerText');
        const paymentText = document.getElementById('paymentText');
        const requestsText = document.getElementById('requestsText');
        const archivesText = document.getElementById('archivesText');
        const adminText = document.getElementById('adminText');
        
        let isCollapsed = false;

        // Toggle sidebar function
        function toggleSidebarFunction() {
            isCollapsed = !isCollapsed;
            
            if (isCollapsed) {
                // Collapse sidebar
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                
                // Hide text elements
                logoText.classList.add('hidden');
                userText.classList.add('hidden');
                logoutText.classList.add('hidden');
                
                // Hide navigation text
                dashboardText.classList.add('hidden');
                registerText.classList.add('hidden');
                paymentText.classList.add('hidden');
                requestsText.classList.add('hidden');
                archivesText.classList.add('hidden');
                adminText.classList.add('hidden');
                
                // Change toggle button
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                toggleText.textContent = 'Agrandir';
                
                // Adjust main content
                mainContent.classList.remove('md:ml-64');
                mainContent.classList.add('md:ml-16');
            } else {
                // Expand sidebar
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                
                // Show text elements
                logoText.classList.remove('hidden');
                userText.classList.remove('hidden');
                logoutText.classList.remove('hidden');
                
                // Show navigation text
                dashboardText.classList.remove('hidden');
                registerText.classList.remove('hidden');
                paymentText.classList.remove('hidden');
                requestsText.classList.remove('hidden');
                archivesText.classList.remove('hidden');
                adminText.classList.remove('hidden');
                
                // Change toggle button
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                toggleText.textContent = 'Réduire';
                
                // Adjust main content
                mainContent.classList.remove('md:ml-16');
                mainContent.classList.add('md:ml-64');
            }
        }

        // Toggle sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebarOverlay.classList.remove('hidden');
            }
        }

        // Close sidebar
        function closeSidebar() {
            sidebar.classList.add('collapsed');
            sidebarOverlay.classList.add('hidden');
        }

        // Event listeners
        toggleButton.addEventListener('click', toggleSidebarFunction);
        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Multi-step form logic
        let currentStep = 1;
        const totalSteps = 3;
        
        const stepContents = document.querySelectorAll('.step-content');
        const stepIndicators = document.querySelectorAll('.step-indicator');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthMessage = document.getElementById('passwordMessage');
            
            let strength = 0;
            let message = '';
            let color = '';
            
            if (password.length > 0) {
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            }
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 50) {
                color = 'bg-red-500';
                message = 'Faible - Utilisez au moins 8 caractères avec majuscules, chiffres et symboles';
            } else if (strength < 75) {
                color = 'bg-yellow-500';
                message = 'Moyen - Ajoutez des caractères spéciaux pour renforcer';
            } else {
                color = 'bg-green-500';
                message = 'Fort - Bon mot de passe';
            }
            
            strengthBar.className = `h-2 rounded-full ${color}`;
            strengthMessage.textContent = message;
        });
        
        // Password match checker
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchMessage = document.getElementById('passwordMatch');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchMessage.textContent = 'Les mots de passe correspondent';
                    matchMessage.className = 'text-xs text-green-600 mt-1';
                    matchMessage.classList.remove('hidden');
                } else {
                    matchMessage.textContent = 'Les mots de passe ne correspondent pas';
                    matchMessage.className = 'text-xs text-red-600 mt-1';
                    matchMessage.classList.remove('hidden');
                }
            } else {
                matchMessage.classList.add('hidden');
            }
        });
        
        // Navigation functions
        function showStep(step) {
            // Hide all steps
            stepContents.forEach(content => content.classList.add('hidden'));
            stepIndicators.forEach(indicator => {
                indicator.classList.remove('step-active', 'step-completed');
                indicator.querySelector('div').className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-gray-200';
            });
            
            // Show current step
            document.getElementById(`step${step}`).classList.remove('hidden');
            
            // Update indicators
            for (let i = 1; i <= step; i++) {
                const indicator = stepIndicators[i-1];
                if (i < step) {
                    indicator.classList.add('step-completed');
                    indicator.querySelector('div').className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-green-500 text-white';
                    indicator.querySelector('div').innerHTML = '<i class="fas fa-check"></i>';
                } else if (i === step) {
                    indicator.classList.add('step-active');
                    indicator.querySelector('div').className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-indigo-500 text-white';
                    indicator.querySelector('div').innerHTML = '<span>' + i + '</span>';
                }
            }
            
            // Update button visibility
            if (step === 1) {
                prevBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            } else if (step === totalSteps) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
            
            currentStep = step;
        }
        
        // Next button click
        nextBtn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                // Basic validation for step 1
                if (currentStep === 1) {
                    const inputs = document.querySelectorAll('#step1 input[required]');
                    let valid = true;
                    
                    inputs.forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('border-red-500');
                            valid = false;
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });
                    
                    if (!valid) return;
                }
                
                // Password validation for step 2
                if (currentStep === 2) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;
                    const terms = document.getElementById('terms').checked;
                    
                    if (password.length < 8) {
                        alert('Le mot de passe doit contenir au moins 8 caractères');
                        return;
                    }
                    
                    if (password !== confirmPassword) {
                        alert('Les mots de passe ne correspondent pas');
                        return;
                    }
                    
                    if (!terms) {
                        alert('Vous devez accepter les conditions d\'utilisation');
                        return;
                    }
                }
                
                showStep(currentStep + 1);
            }
        });
        
        // Previous button click
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
        
        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Compte créé avec succès ! Vous allez recevoir un email de confirmation.');
            // In a real application, you would submit the form data to your server here
        });
        
        // Initialize
        showStep(1);