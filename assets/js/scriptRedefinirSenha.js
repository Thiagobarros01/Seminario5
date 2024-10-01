document.getElementById('formRedefinirSenha').addEventListener('submit', function(event) {
    event.preventDefault(); // Previne o comportamento padrão do formulário

    var email = document.getElementById('email').value;
    var senhaNova = document.getElementById('senhaNova').value;
    var senhaConfirmacao = document.getElementById('senhaConfirmacao').value;

    // Validação básica
    if (!email ||!senhaNova ||!senhaConfirmacao) {
        alert('Todos os campos são obrigatórios.');
        return;
    }

    if (senhaNova!== senhaConfirmacao) {
        alert('As senhas não coincidem.');
        return;
    }

    // Aqui você pode adicionar o código para enviar a nova senha para o servidor
    console.log('Nova senha enviada com sucesso:', {email, senhaNova});
});
