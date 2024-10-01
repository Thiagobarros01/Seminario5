function validateForm() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;

    var nameError = document.getElementById("name-error");
    var emailError = document.getElementById("email-error");
    var passwordError = document.getElementById("password-error");
    var confirmPasswordError = document.getElementById("confirm-password-error");

    nameError.textContent = "";
    emailError.textContent = "";
    passwordError.textContent = "";
    confirmPasswordError.textContent = "";

    var isValid = true;

    if (name.trim() === "") {
        nameError.textContent = "Por favor, insira o nome da empresa.";
        isValid = false;
    }

    if (email.trim() === "") {
        emailError.textContent = "Por favor, insira seu e-mail.";
        isValid = false;
    } else if (!isValidEmail(email)) {
        emailError.textContent = "Por favor, insira um e-mail válido.";
        isValid = false;
    }

    if (password.trim() === "") {
        passwordError.textContent = "Por favor, insira sua senha.";
        isValid = false;
    } else if (password.length < 6) {
        passwordError.textContent = "A senha deve ter pelo menos 6 caracteres.";
        isValid = false;
    }

    if (confirmPassword.trim() === "") {
        confirmPasswordError.textContent = "Por favor, confirme sua senha.";
        isValid = false;
    } else if (password!== confirmPassword) {
        confirmPasswordError.textContent = "As senhas não coincidem.";
        isValid = false;
    }

    if (isValid) {
        // Redireciona para a página principal após o cadastro bem-sucedido
        window.location.href = "index.html"; // Substitua "index.html" pela URL da página principal do seu site
    }
}

function isValidEmail(email) {
    // Regex simples para validar e-mail
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function submitForm() {
    // Coleta os dados do formulário
    var form = document.getElementById('register-form');
    var formData = new FormData(form);

    // Cria a solicitação AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'registro.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Exibe a notificação
            var notification = document.getElementById('notification');
            notification.style.display = 'block';

            // Verifica a resposta do servidor
            if (xhr.responseText.trim() === 'success') {
                notification.innerHTML = 'Usuário cadastrado com sucesso!';
                notification.classList.remove('error');
                setTimeout(function () {
                    notification.style.display = 'none';
                }, 3000);
            } else {
                notification.innerHTML = xhr.responseText;
                notification.classList.add('error');
                setTimeout(function () {
                    notification.style.display = 'none';
                }, 3000);
            }
        } else {
            alert('Erro ao enviar solicitação.');
        }
    };

    xhr.send(formData);

    // Retorna false para evitar o envio do formulário padrão
    return false;
}