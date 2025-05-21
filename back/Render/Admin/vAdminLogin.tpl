<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Вход в систему</h2>
                    <form id="loginForm">
                        <input type="hidden" name="redirect-from" value="{REDIRECT_FROM}">
                        <div class="mb-3">
                            <label for="username" class="form-label">Логин</label>
                            <input type="text" class="form-control" id="username" placeholder="Введите логин" required>
                            <div class="invalid-feedback">Пожалуйста, введите логин</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="password" placeholder="Введите пароль" required>
                            <div class="invalid-feedback">Пожалуйста, введите пароль</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
