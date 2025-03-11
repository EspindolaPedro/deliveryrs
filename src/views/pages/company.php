<?php
$empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '1';
?>
<?php $render('headerAdmin',) ?>

<?php $render('sidebar') ?>

<main class="container-orders text-white  ">

    <div class="nav flex px-5 gap-4">
        <a href="?empresa=1" class="<?php echo $empresa == 1 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Dados da empresa</a>
        <a href="?empresa=2" class="<?php echo $empresa == 2 ? 'text-black bg-[#FFEF00]' : 'bg-[#424242] text-[#C6C6C6]'; ?>">Horários</a>
    </div>


    <?php if ($empresa == '1'): ?>

        <form  class="form" method="POST" action="<?= $base?>/dados-empresa">
            <!-- Campo de Upload de Imagem -->
             <div>
                 <label class="CompanyAvatar" for="avatar">
                     <span>
                         <img src=" <?=$base?>/assets/images/upload.svg" alt="Ícone de Upload" width="30" height="30">
                     </span>
                     <input type="file" id="avatar" name="image_url" accept="image/png, image/jpeg" onchange="handleFile(event)">
                     <div class="preview-photo">
                        <img src="<?= $_SESSION['user']['image_url'] ?? ''?>" alt="">
                     </div>
                     
                 </label>

             </div>

            <!-- Campo de Nome do Produto -->
            <div class="flex gap-2">
                <input
                    type="text"
                    placeholder="Digite o nome da empresa"
                    value="<?= $_SESSION['user']['name'] ?? ''?>"
                    class="input"
                    name="name"
                    />

                    <input
                    type="text"
                    id="telefone"
                    name="phone"
                    placeholder="(67) 9 9999 9999"
                    value="<?= $_SESSION['user']['phone']?>"
                    class="input"

                />

            </div>
            <input
                type="text"
                placeholder="contato@gmail.com"
                class="input"
                value="<?= $_SESSION['user']['email']?>"
                name="email"
            />

            <input
                type="text"
                placeholder="Rua, número, bairro e cidade"
                class="input"
                value="<?= $_SESSION['user']['address']?>"
                name="address"
            />

            <!-- Campo de Preço do Produto -->

            <!-- Campo de Descrição do Produto -->
            <textarea
                placeholder="Sobre a empresa"
                class="input"
                name="about"
            >
            <?= htmlspecialchars($_SESSION['user']['about'])?>
        </textarea>
            
            <!-- Botão de Cadastro -->
            <button type="submit" class="buttonAdd">
                Alterar dados
            </button>
        </form>

    <?php else: ?>


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