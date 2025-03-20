
<div class="modalUpdate relative">
            <h1 class="text-3xl font-medium">Atualizar produto</h1>
            <button class="closeUpdateModal absolute z-2 top-2 -left-0 text-red-400 text-xl text-semibold bg-[#fff] p-4 rounded-full pointer" style="padding:4px 10px;cursor:pointer;">X</button>
            
        <form id="productEditForm" class="form" method="POST" enctype="multipart/form-data" >
            
            <label class="labelAvatar" for="avatar">
                <span>
                    <img src="<?=$base?>/assets/images/upload.svg" alt="Ícone de Upload" width="30" height="30">
                </span>
                <input type="file" name="image_url" id="avatar"  class="product product-image up-img" accept="image/png, image/jpeg" onchange="handleFile(event)">
                <div class="preview-photo"></div>
                
            </label>

            <select name="UpdateCategory" id="category_selected">
                <option value="">Escolha uma categoria</option>                             
             
            </select>

            <!-- Campo de Nome do Produto -->
            <input
                type="text"
                id="updateNameProduct"
                placeholder="Digite o nome do produto"
                class="input"
                name="name"
            />

            <!-- Campo de Preço do Produto -->
            <div class="flex gap-2">
                <input
                    type="text"
                    id="Updateprice"
                    placeholder="Valor R$"
                    class="input"
                    name="price"
                />
                <input
                    type="text"
                    id="UpdatePricefrom"
                    placeholder="Valor R$ (valor para promoção)"
                    class="input"
                    name="price_from"
                />

            </div>

            <!-- Campo de Descrição do Produto -->
            <textarea
                id="UpdateDescription"
                placeholder="Descreva seu produto"
                class="input"
                name="description"
            ></textarea>

            
            <label for="visivel" class="text-xl" style="margin-bottom: 22px;">Aparecer no menu? <br>
                <input type="checkbox" name="UpdateIsListed" value="1" checked id=""> Sim <br>
                </label>
            <!-- Botão de Cadastro -->
            <button type="submit" class="buttonAdd">
                Cadastrar
            </button>
        </form>

        </div>

