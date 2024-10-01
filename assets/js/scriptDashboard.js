document.getElementById('loginForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const users = JSON.parse(localStorage.getItem('users')) || [];
    const user = users.find(u => u.username === username && u.password === password);

    if (user) {
        window.location.href = 'dashboard.html';
    } else {
        alert('Usuário ou senha inválidos');
    }
});

document.getElementById('registerForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const newUsername = document.getElementById('newUsername').value;
    const newPassword = document.getElementById('newPassword').value;

    const users = JSON.parse(localStorage.getItem('users')) || [];
    const userExists = users.some(u => u.username === newUsername);

    if (userExists) {
        alert('Usuário já cadastrado');
    } else {
        users.push({ username: newUsername, password: newPassword });
        localStorage.setItem('users', JSON.stringify(users));
        alert('Usuário cadastrado com sucesso');
        window.location.href = 'index.html';
    }
});

window.onload = function() {
    if (document.getElementById('agentGroupChart')) {
        const ctx1 = document.getElementById('agentGroupChart').getContext('2d');
        const ctx2 = document.getElementById('priorityChart').getContext('2d');
        const ctx3 = document.getElementById('escalatedSLAChart').getContext('2d');

        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Group A', 'Group B', 'Group C'],
                datasets: [{
                    label: 'SLA Violations by Agent Group',
                    data: [247, 123, 56],
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
                }]
            }
        });

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Low', 'Medium', 'High', 'Urgent'],
                datasets: [{
                    label: 'SLA Violations by Priority',
                    data: [123, 56, 247, 89],
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
                }]
            }
        });

        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Escalated', 'Non-Escalated'],
                datasets: [{
                    label: 'Escalated SLA Violations',
                    data: [50, 200],
                    backgroundColor: ['#ff6384', '#36a2eb']
                }]
            }
        });
    }

    if (document.getElementById('ticketList')) {
        loadTickets();
    }

    if (document.getElementById('ticketForm')) {
        document.getElementById('ticketForm').addEventListener('submit', function(event) {
            event.preventDefault();
            addTicket();
        });
    }
}

function loadTickets() {
    const ticketList = document.getElementById('ticketList');
    const tickets = JSON.parse(localStorage.getItem('tickets')) || [];

    ticketList.innerHTML = '';
    tickets.forEach(ticket => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${ticket.id}</td>
            <td>${ticket.subject}</td>
            <td>${ticket.priority}</td>
            <td>${ticket.status}</td>
        `;
        ticketList.appendChild(row);
    });
}

function addTicket() {
    const subject = document.getElementById('subject').value;
    const priority = document.getElementById('priority').value;
    const status = document.getElementById('status').value;

    const tickets = JSON.parse(localStorage.getItem('tickets')) || [];
    const newTicket = {
        id: tickets.length + 1,
        subject,
        priority,
        status
    };

    tickets.push(newTicket);
    localStorage.setItem('tickets', JSON.stringify(tickets));
    window.location.href = 'tickets.html';
}
