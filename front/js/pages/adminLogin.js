// document.addEventListener('DOMContentLoaded', function() {
//     document.getElementById('loginForm').addEventListener('submit', function(e) {
//         e.preventDefault();
//
//         const username = document.getElementById('username');
//         const password = document.getElementById('password');
//         let isValid = true;
//
//         // Проверка логина
//         if (username.value.trim() === '') {
//             username.classList.add('is-invalid');
//             isValid = false;
//         } else {
//             username.classList.remove('is-invalid');
//         }
//
//         // Проверка пароля
//         if (password.value.trim() === '') {
//             password.classList.add('is-invalid');
//             isValid = false;
//         } else {
//             password.classList.remove('is-invalid');
//         }
//
//         if (isValid) {
//             // Здесь можно добавить отправку формы или другие действия
//             alert('Форма заполнена корректно! Можно отправлять данные.');
//             // this.submit(); // Раскомментируйте для реальной отправки формы
//         }
//     });
// });

class AdminLogin {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.usernameInput = this.form.querySelector('#username');
        this.passwordInput = this.form.querySelector('#password');
        this.redirectInput = this.form.querySelector('input[name="redirect-from"]');

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    validateInputs() {
        let isValid = true;

        if (this.usernameInput.value.trim() === '') {
            this.usernameInput.classList.add('is-invalid');
            isValid = false;
        } else {
            this.usernameInput.classList.remove('is-invalid');
        }

        if (this.passwordInput.value.trim() === '') {
            this.passwordInput.classList.add('is-invalid');
            isValid = false;
        } else {
            this.passwordInput.classList.remove('is-invalid');
        }

        return isValid;
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.validateInputs()) {
            return;
        }

        const payload = {
            name: this.usernameInput.value.trim(),
            pass: this.passwordInput.value.trim(),
        };

        try {
            const response = await fetch('/api/admin/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });


            console.log('Статус ответа:', response.status); // 🟡 Статус
            const result = await response.json();
            console.log('Ответ от сервера:', result); // 🟢 Смотри что пришло

            if (result && result.data.idUser > 0) {
                window.location.href = this.redirectInput?.value?.trim() || '/';
            } else {
                alert('Неверный логин или пароль');
            }
        } catch (error) {
            console.error('Ошибка при входе:', error);
            alert('Произошла ошибка при входе. Попробуйте позже.');
        }
    }
}

// Создание экземпляра после загрузки DOM
document.addEventListener('DOMContentLoaded', function () {
    new AdminLogin('loginForm');
});

