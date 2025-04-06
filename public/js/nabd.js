
// تحديد زر القائمة
const hamBurger = document.querySelector(".toggle-btn");

// إضافة حدث للنقر على زر القائمة
hamBurger.addEventListener("click", function() {
    // تحديد الـ sidebar
    const sidebar = document.querySelector("#sidebar");

    // التبديل بين الوضعين: إظهار أو إخفاء الـ sidebar
    sidebar.classList.toggle("collapsed");
});



document.addEventListener("DOMContentLoaded", function () {
    // Récupère tous les liens
    const links = document.querySelectorAll('.sidebar-link');

    // Supprime la classe active de tous les liens
    function removeActiveClass() {
        links.forEach(link => link.classList.remove('active'));
    }

    // Ajoute la classe active au lien correspondant
    links.forEach(link => {
        const linkUrl = link.getAttribute('href'); // URL du lien
        if (window.location.pathname.endsWith(linkUrl)) {
            link.classList.add('active');
        }

        // Ajoute un événement de clic pour mettre à jour la classe active
        link.addEventListener('click', () => {
            removeActiveClass();
            link.classList.add('active');
        });
    });
});

