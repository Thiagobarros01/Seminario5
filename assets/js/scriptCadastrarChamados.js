        function closeForm() {
            // Seleciona o contêiner do formulário
            var formContainer = document.querySelector('.container');
            
            // Oculta o contêiner do formulário
            formContainer.style.display = 'none';
            
            // Exibe uma mensagem de alerta
            alert("Formulário fechado.");
        }


    function closeForm() {
        // Exibe uma mensagem de alerta
        alert("Formulário fechado.");
        
        // Tenta fechar a janela atual
        if (window.self!== window.top) {
            window.top.close();
        } else {
            // Se não for possível fechar a janela, oculta o formulário
            var formContainer = document.querySelector('.container');
            formContainer.style.display = 'none';
        }
    }
    function goBack() {
        window.history.back();
    }

    function submitForm() {
        // Coleta os dados do formulário
        var form = document.getElementById('ticket-form');
        var formData = new FormData(form);

        // Cria a solicitação AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'submit_chamado.php', true);
        xhr.onload = function () {
            var notification = document.getElementById('notification');
            if (xhr.status === 200) {
                // Verifica a resposta do servidor
                if (xhr.responseText.trim() === 'success') {
                    notification.innerHTML = 'Chamado cadastrado com sucesso!';
                    notification.classList.remove('error');
                } else {
                    notification.innerHTML = xhr.responseText;
                    notification.classList.add('error');
                }
            } else {
                notification.innerHTML = 'Erro ao enviar solicitação.';
                notification.classList.add('error');
            }

            // Exibe a notificação
            notification.style.display = 'block';

            // Define um temporizador para esconder a notificação após 3 segundos
            setTimeout(function () {
                notification.style.display = 'none';
            }, 3000);
        };

        xhr.send(formData);

        // Retorna false para evitar o envio do formulário
        return false;
    }

    function closeForm() {
        document.getElementById('ticket-form').reset();
    }