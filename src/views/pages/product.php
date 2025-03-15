<?php
$produtos = isset($_GET['produtos']) ? $_GET['produtos'] : '1';
?>
<?php $render('headerAdmin', ['products' => $products, 'categories' => $categories, 'flash' => $flash]) ?>

<?php $render('sidebar') ?>

<main class="container-orders text-white  ">

    <div class="nav flex px-5 gap-4" style="margin-bottom:40px;">
        <a href="?produtos=1" class="<?php echo $produtos == 1 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Adicionar produtos</a>
        <a href="?produtos=2" class="<?php echo $produtos == 2 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Produtos cadastrados</a>
    </div>

    <?php if ($produtos == '1'): ?>

        <main class="container">
            <h1 class="text-3xl font-medium">Novo produto</h1>

            <form id="productForm" class="form" method="POST" enctype="multipart/form-data" action="<?= $base ?>/novo_produto">

                <label class="labelAvatar" for="avatar">
                    <span>
                        <img src="<?= $base ?>/assets/images/upload.svg" alt="Ícone de Upload" width="30" height="30">
                    </span>
                    <input type="file" name="image_url" id="avatar" class="product" accept="image/png, image/jpeg" onchange="handleFile(event)">
                    <div class="preview-photo"></div>

                </label>

                <select name="category_id" id="category">
                    <option value="">Escolha uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach ?>
                </select>

                <!-- Campo de Nome do Produto -->
                <input
                    type="text"
                    id="name"
                    placeholder="Digite o nome do produto"
                    class="input"
                    name="name" />

                <!-- Campo de Preço do Produto -->
                <div class="flex gap-2">
                    <input
                        type="text"
                        id="price"
                        placeholder="Valor R$"
                        class="input"
                        name="price" />
                    <input
                        type="text"
                        id="price_from"
                        placeholder="Valor R$ (valor para promoção)"
                        class="input"
                        name="price_from" />

                </div>

                <!-- Campo de Descrição do Produto -->
                <textarea
                    id="description"
                    placeholder="Descreva seu produto"
                    class="input"
                    name="description"></textarea>


                <label for="visivel" class="text-xl" style="margin-bottom: 22px;">Aparecer no menu? <br>
                    <input type="checkbox" name="is_listed" value="1" checked id=""> Sim <br>
                </label>
                <!-- Botão de Cadastro -->
                <button type="submit" class="buttonAdd">
                    Cadastrar
                </button>
            </form>

        </main>


    <?php else: ?>

        <div>
            <ul id="sortable-list" class="sortable-list">


                <li class="max-w-[600px] rounded-md bg-white text-[#252525] flex justify-between items-center cursor-grab"
                    data-id="">
                    Cervejinha
                    <span class="flex gap-4">

                        <button
                            onclick=""
                            class="cursor-pointer"
                            data-is-listed="">

                            <img src="<?= $base ?>/assets/images/edit.svg"
                                class="w-8" alt="editar"></button>

                        <button onclick="  " class="cursor-pointer"><img src="<?= $base ?>/assets/images/trash.svg " class="w-8" alt="editar"></span></button>

                </li>

            </ul>
        </div>



    <?php endif; ?>

</main>

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



<?php $render('footerAdmin') ?>