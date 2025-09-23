document.addEventListener('DOMContentLoaded', function () {
    // -------------------------
    // Sidebar toggle
    // -------------------------
    const toggleButton = document.getElementById('toggleSidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleText = document.getElementById('toggleText');
    const logoText = document.getElementById('logoText');
    const userText = document.getElementById('userText');
    const logoutText = document.getElementById('logoutText');
    const mainContent = document.getElementById('mainContent');

    const dashboardText = document.getElementById('dashboardText');
    const registerText = document.getElementById('registerText');
    const paymentText = document.getElementById('paymentText');
    const requestsText = document.getElementById('requestsText');
    const archivesText = document.getElementById('archivesText');
    const adminText = document.getElementById('adminText');

    const toggleSidebarFunction = () => {
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = sidebar.classList.contains('w-16');

        sidebar.classList.toggle('w-64', isCollapsed);
        sidebar.classList.toggle('w-16', !isCollapsed);

        [logoText, userText, logoutText, dashboardText, registerText, paymentText, requestsText, archivesText, adminText]
            .forEach(el => el?.classList.toggle('hidden', !isCollapsed));

        toggleIcon.classList.toggle('fa-chevron-left', isCollapsed);
        toggleIcon.classList.toggle('fa-chevron-right', !isCollapsed);
        toggleText.textContent = isCollapsed ? 'Réduire' : 'Agrandir';

        mainContent.classList.toggle('md:ml-64', isCollapsed);
        mainContent.classList.toggle('md:ml-16', !isCollapsed);
    };

    toggleButton?.addEventListener('click', toggleSidebarFunction);

    // -------------------------
    // Multi-step form (2 steps)
    // -------------------------
    let currentStep = 1;
    const totalSteps = 2;

    const stepContents = document.querySelectorAll('.step-content');
    const stepIndicators = document.querySelectorAll('.step-indicator');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    const showStep = (step) => {
        stepContents.forEach(content => content.classList.add('hidden'));
        stepIndicators.forEach((indicator, i) => {
            const indicatorDiv = indicator.querySelector('div');
            indicator.classList.remove('step-active', 'step-completed');
            indicatorDiv.className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-gray-200';

            if (i + 1 < step) {
                indicator.classList.add('step-completed');
                indicatorDiv.className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-green-500 text-white';
                indicatorDiv.innerHTML = '<i class="fas fa-check"></i>';
            } else if (i + 1 === step) {
                indicator.classList.add('step-active');
                indicatorDiv.className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-indigo-500 text-white';
                indicatorDiv.innerHTML = `<span>${step}</span>`;
            }
        });

        document.getElementById(`step${step}`)?.classList.remove('hidden');

        prevBtn.classList.toggle('hidden', step === 1);
        nextBtn.classList.toggle('hidden', step === totalSteps);
        submitBtn.classList.toggle('hidden', step !== totalSteps);

        currentStep = step;
    };

    nextBtn?.addEventListener('click', () => {
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

        showStep(currentStep + 1);
    });

    prevBtn?.addEventListener('click', () => {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // -------------------------
    // Form submission
    // -------------------------
    document.getElementById('registrationForm')?.addEventListener('submit', function (e) {
        // Le formulaire se soumet normalement sans alert
        // Si tu veux AJAX, tu peux ajouter la requête ici
        this.submit();
    });

    // -------------------------
    // Toggle password visibility
    // -------------------------
    window.togglePassword = function (fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field?.nextElementSibling?.querySelector('i');
        if (field?.type === 'password') {
            field.type = 'text';
            icon?.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon?.classList.replace('fa-eye-slash', 'fa-eye');
        }
    };

    // -------------------------
    // Password strength checker
    // -------------------------
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMessage = document.getElementById('passwordMessage');

    passwordInput?.addEventListener('input', function () {
        const password = this.value;
        let strength = 0;

        if (password.length >= 8) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 25;
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;

        passwordStrength.style.width = `${strength}%`;

        let color = 'bg-gray-400';
        let message = 'Votre mot de passe doit contenir au moins 8 caractères';

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

        passwordStrength.className = `h-2 rounded-full ${color}`;
        passwordMessage.textContent = message;
    });

    // -------------------------
    // Confirm password match
    // -------------------------
    const confirmInput = document.getElementById('confirmPassword');
    const matchMessage = document.getElementById('passwordMatch');

    confirmInput?.addEventListener('input', function () {
        const password = passwordInput?.value;
        const confirmPassword = this.value;

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

    // -------------------------
    // Initialize
    // -------------------------
    showStep(1);
});
