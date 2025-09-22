     // Éléments du DOM
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const logoutBtn = document.getElementById('logoutBtn');
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const toggleButton = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const logoText = document.getElementById('logoText');
        const userText = document.getElementById('userText');
        const logoutText = document.getElementById('logoutText');
        
        // Éléments de texte de navigation
        const dashboardText = document.getElementById('dashboardText');
        const registerText = document.getElementById('registerText');
        const paymentText = document.getElementById('paymentText');
        const requestsText = document.getElementById('requestsText');
        const archivesText = document.getElementById('archivesText');
        const adminText = document.getElementById('adminText');
        
        let isCollapsed = false;

        // Fonction de bascule de la barre latérale
        function toggleSidebarFunction() {
            isCollapsed = !isCollapsed;
            
            if (isCollapsed) {
                // Réduire la barre latérale
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                
                // Masquer les éléments de texte
                logoText.classList.add('hidden');
                userText.classList.add('hidden');
                logoutText.classList.add('hidden');
                
                // Masquer le texte de navigation
                dashboardText.classList.add('hidden');
                registerText.classList.add('hidden');
                paymentText.classList.add('hidden');
                requestsText.classList.add('hidden');
                archivesText.classList.add('hidden');
                adminText.classList.add('hidden');
                
                // Changer le bouton de bascule
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                toggleText.textContent = '';
                
                // Ajuster le contenu principal
                document.getElementById('mainContent').classList.remove('md:ml-64');
                document.getElementById('mainContent').classList.add('md:ml-16');
            } else {
                // Étendre la barre latérale
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                
                // Afficher les éléments de texte
                logoText.classList.remove('hidden');
                userText.classList.remove('hidden');
                logoutText.classList.remove('hidden');
                
                // Afficher le texte de navigation
                dashboardText.classList.remove('hidden');
                registerText.classList.remove('hidden');
                paymentText.classList.remove('hidden');
                requestsText.classList.remove('hidden');
                archivesText.classList.remove('hidden');
                adminText.classList.remove('hidden');
                
                // Changer le bouton de bascule
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                toggleText.textContent = 'Réduire';
                
                // Ajuster le contenu principal
                document.getElementById('mainContent').classList.remove('md:ml-16');
                document.getElementById('mainContent').classList.add('md:ml-64');
            }
        }

        // Bascule de la barre latérale
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebarOverlay.classList.remove('hidden');
            }
        }

        // Fermer la barre latérale
        function closeSidebar() {
            sidebar.classList.add('collapsed');
            sidebarOverlay.classList.add('hidden');
        }

        // Fonction de déconnexion
        function logout() {   }

        // Changer d'onglet
        function switchTab(tabName) {
            // Masquer tous les contenus d'onglets
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Supprimer la classe active de tous les boutons d'onglets
            tabButtons.forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            
            // Afficher le contenu de l'onglet sélectionné
            document.getElementById(`${tabName}-tab`).classList.add('active');
            
            // Ajouter la classe active au bouton d'onglet sélectionné
            const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
            activeButton.classList.remove('text-gray-500', 'hover:text-gray-700');
            activeButton.classList.add('border-indigo-500', 'text-indigo-600');
        }

        // Écouteurs d'événements
        toggleButton.addEventListener('click', toggleSidebarFunction);
        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        logoutBtn.addEventListener('click', logout);

        // Changement d'onglet
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');
                switchTab(tabName);
            });
        });

        // Événement de changement du sélecteur de rôle
        document.getElementById('roleSelector').addEventListener('change', function() {
            const role = this.value;
            console.log('Rôle sélectionné:', role);
            // Dans une vraie application, vous rechargeriez les données en fonction du rôle sélectionné
        });

        // Initialisation
        switchTab('users');