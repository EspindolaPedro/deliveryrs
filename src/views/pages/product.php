<?php
$produtos = isset($_GET['produtos']) ? $_GET['produtos'] : '1';
?>
<?php $render('headerAdmin', []) ?>

<?php $render('sidebar') ?>

<main class="container-orders text-white  ">

    <div class="nav flex px-5 gap-4" style="margin-bottom:40px;">
        <a href="?produtos=1" class="<?php echo $produtos == 1 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Adicionar produtos</a>
        <a href="?produtos=2" class="<?php echo $produtos == 2 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Produtos cadastrados</a>
    </div>

    <?php if ($produtos == '1'): ?>
        
        <main class="container">
            <h1 class="text-3xl font-medium">Novo produto</h1>

        <form id="productForm" class="form">
            <!-- Campo de Upload de Imagem -->
            <label class="labelAvatar" for="avatar">
                <span>
                    <img src="<?=$base?>/assets/images/upload.svg" alt="Ícone de Upload" width="30" height="30">
                </span>
                <input type="file" id="avatar" class="product" accept="image/png, image/jpeg" onchange="handleFile(event)">
                <div class="preview-photo"></div>
                
            </label>

            <!-- Seleção de Categoria -->
            <select name="category" id="category">
                <option value="">Selecione uma categoria</option>
                <!-- As opções serão preenchidas dinamicamente via JavaScript -->
            </select>

            <!-- Campo de Nome do Produto -->
            <input
                type="text"
                id="name"
                placeholder="Digite o nome do produto"
                class="input"
            />

            <!-- Campo de Preço do Produto -->
            <div class="flex gap-2">
                <input
                    type="text"
                    id="price"
                    placeholder="Valor R$"
                    class="input"
                />
                <input
                    type="text"
                    id="price_from"
                    placeholder="Valor R$ (valor para promoção)"
                    class="input"
                />

            </div>

            <!-- Campo de Descrição do Produto -->
            <textarea
                id="description"
                placeholder="Descreva seu produto"
                class="input"
            ></textarea>

            
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
    <script>
        showToastify(<?= json_encode($flash); ?>);
    </script>
<?php endif; ?>








<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModal">&times;</span>
        <h2 class="text-bold text-2xl">Editar Categoria</h2>

        <form id="editCategoryForm">
            <input class="text-bold text-xl border-b-2 border-gray-400 " style="padding: 4px;" type="text" id="editCategoryName" name="name" placeholder="Nome da Categoria" required>
            <br>
            <label for="editIsListed mb-2" class="text-xl">Aparecer no menu ?</label> <br>
            <input type="checkbox" id="editIsListed" name="is_listed" value="1">
            <br>
            <input type="hidden" id="editCategoryId" name="id"> <br>
            <div class="modal-buttons">
                <button type="submit" class="confirm-button">Salvar</button>
                <button type="button" id="cancelEdit" class="cancel-button">Cancelar</button>
            </div>
        </form>

    </div>
</div>


<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Tem certeza que deseja excluir?</h2>
        <p>Esta ação não pode ser desfeita.</p>
        <div class="modal-buttons">
            <button id="confirmDelete" class="confirm-button">Sim, excluir</button>
            <button id="cancelDelete" class="cancel-button">Cancelar</button>
        </div>
    </div>
</div>

<?php $render('footerAdmin') ?>