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
            <ul id="sortable-list" class="sortable-list">

                <?php foreach ($categories as $category): ?>
                    <li onclick="" class="max-w-[600px] rounded-md bg-white text-[#252525] flex justify-between items-center cursor-grab"
                        data-id="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?><span class="flex gap-4">

                            <button  class="w-10"
                            onclick="updateCategory(<?= $category['id'] ?>)" 

                            class="cursor-pointer"
                            data-is-listed="<?= $category['is_listed'] ?>"
                            >
                            <img src="<?= $base ?>/assets/images/edit.svg" 
                            class="w-10" alt="editar"></button>

                         <!--   <button onclick="removeCategory(category['id'])" class="cursor-pointer"><img src="<?= $base ?>/assets/images/trash.svg " class="w-8" alt="editar"></span></button> -->


                    </li>
                <?php endforeach; ?>
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