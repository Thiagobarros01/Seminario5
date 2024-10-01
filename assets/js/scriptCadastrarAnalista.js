document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('senha');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Mostrar' : 'Esconder';
});
    