<?php $render('headerAdmin') ?>

<div class="container-login">

        <img src="<?= $base ?>/assets/images/logo/Group-21.png" />


        <?php if (!empty($flash)): ?>
                <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
                <script>
                        Toastify({
                                text: <?= json_encode($flash); ?>,
                                duration: 3000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "linear-gradient(to right, #000, #111)"
                        }).showToast();
                </script>
        <?php endif; ?>


        <form method="POST" action="<?= $base ?>/admin" id="login-form">

                <input placeholder="Digite seu email" type="text" name="email" /> <br>

                <input placeholder="Digite sua senha" type="password" name="password" /> <br>

                <button type="submit">
                        Acessar conta
                </button>

        </form>
</div>


<?php $render('footerAdmin') ?>