<?php
$categoria = isset($_GET['categorias']) ? $_GET['categorias'] : '1';
?>
<?php $render('headerAdmin', ['categories' => $categories]) ?>

<?php $render('sidebar') ?>

<main class="container-orders text-white  ">

    <div class="nav flex px-5 gap-4">
        <a href="?categoria=1" class="<?php echo $categoria == 1 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Nova Categoria</a>
        <a href="?categorias=2" class="<?php echo $categoria == 2 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Categorias cadastradas</a>
    </div>


    <?php if ($categoria == '1'): ?>

        <div class="new-category mt-46">
            <h1 class="text-3xl ">Nova categoria</h1>

            <form method="POST" action="<?= $base ?>/nova-categoria">
                <input type="text" placeholder="Nome" name="nameCategory">
                <br>

                <label for="visivel">Aparecer no menu?</label> <br>
                <input type="checkbox" name="is_listed" value="1" checked id=""> Sim <br>

                <button type="submit" class="button">
                    Criar nova categoria
                </button>
            </form>


        </div>


    <?php else: ?>
        <div class="cont">
            <!-- Filtro de seleção -->
            <select id="categoryFilter" class="mb-4 p-2 border rounded max-w-[200px]">
                <option value="all">Todas</option>
                <option value="listed">Listadas</option>
                <option value="not-listed">Não Listadas</option>
            </select>

            <ul id="sortable-list" class="sortable-list">
                <?php foreach ($categories as $category): ?>
                    <li class="category-item max-w-[600px] rounded-md bg-white  text-[#252525] flex justify-between items-center cursor-grab"
                        data-id="<?= $category['id'] ?>" data-listed="<?= $category['is_listed'] ?>">

                        <div class="flex flex-col ">
                            <div class="category-name ">
                                <span class="font-semibold">Categoria:</span> <span class="text-xl"><?= htmlspecialchars($category['name']) ?></span>
                            </div>
                            <div class="category-status ">
                                <span class="font-semibold text-xl">Listado?</span> <?= $category['is_listed'] ? '<span class="text-green-700">Sim</span>' : '<span class="text-red-600">Não</span>' ?>
                            </div>
                        </div>

                        <span class="flex gap-4">
                            <button class="w-10 cursor-pointer"
                                onclick="openEditModal(<?= json_encode($category['id']) ?>,  '<?= addslashes($category['name']) ?>', <?= $category['is_listed'] ?>)">
                                <img src="<?= $base ?>/assets/images/edit.svg" class="w-10" alt="editar">
                            </button>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
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


<script>
    // Função para filtrar as categorias
    document.getElementById('categoryFilter').addEventListener('change', function() {
        var filterValue = this.value;
        var categories = document.querySelectorAll('.category-item');

        categories.forEach(function(category) {
            var isListed = category.getAttribute('data-listed');

            // Verifica qual opção foi escolhida e exibe/oculta as categorias
            if (filterValue === 'all') {
                category.style.display = 'flex'; // Exibe todas
            } else if (filterValue === 'listed' && isListed === '1') {
                category.style.display = 'flex'; // Exibe apenas as listadas
            } else if (filterValue === 'not-listed' && isListed === '0') {
                category.style.display = 'flex'; // Exibe apenas as não listadas
            } else {
                category.style.display = 'none'; // Oculta as categorias que não atendem ao filtro
            }
        });
    });
</script>



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