

    const user = document.getElementById('user');
    const pass = document.getElementById('pass');
    const form = document.querySelector('form');
    const errorGeneral = document.getElementById('error-general');

    form.addEventListener('submit', (e) => {
        let messages = [];

        if (!/^[a-zA-Z0-9]{4,16}$/.test(user.value.trim())) {
            messages.push('El usuario debe tener entre 4 y 16 caracteres y solo puede contener letras y números.');
        }
        if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(pass.value)) {
            messages.push('La contraseña debe tener al menos 6 caracteres, contener al menos una letra y un número.');
        }

        if (messages.length > 0) {
            e.preventDefault();
            errorGeneral.innerText = messages[0];
            errorGeneral.style.display = 'block';

            setTimeout(() => {
                errorGeneral.style.display = 'none';
            }, 3000);
        } else {
            errorGeneral.style.display = 'none';
        }
    });