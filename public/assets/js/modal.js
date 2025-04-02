const api = axios.create({
  baseURL: "http://localhost/deliveryrs/public/",
  headers: {
    //'Authorization': 'Bearer seu-token-aqui',
    "Content-Type": "application/json",
  },
});

function showToastify(message) {
  Toastify({
    text: message,
    duration: 3000,
    gravity: "top",
    position: "center",
    backgroundColor: "linear-gradient(to right, #000, #111)",
  }).showToast();
}
function openEditModal(categoryId, categoryName, isListed) {
  const editModal = document.getElementById("editModal");
  editModal.style.display = "flex";

  // Preenche os campos do modal com as informações da categoria
  document.getElementById("editCategoryName").value = categoryName;
  document.getElementById("editIsListed").checked = isListed == 1;
  document.getElementById("editCategoryId").value = categoryId;

  // Funcionalidade para fechar o modal ao clicar no botão "Cancelar"
  const cancelEditButton = document.getElementById("cancelEdit");
  cancelEditButton.onclick = function () {
    editModal.style.display = "none";
  };

  // Funcionalidade para fechar o modal ao clicar no "X"
  const closeEditModal = document.getElementById("closeEditModal");
  closeEditModal.onclick = function () {
    editModal.style.display = "none";
  };

  // Fecha o modal caso o usuário clique fora dele
  window.onclick = function (event) {
    if (event.target == editModal) {
      editModal.style.display = "none";
    }
  };

  // Função para submeter o formulário de edição
  const editForm = document.getElementById("editCategoryForm");
  editForm.onsubmit = function (event) {
    event.preventDefault();
    updateCategory(categoryId);  // Chama a função para atualizar a categoria
    editModal.style.display = "none";  // Fecha o modal após a edição
  };
}
async function updateCategory(categoryId) {
  const categoryName = document.getElementById("editCategoryName").value;
  const isListed = document.getElementById("editIsListed").checked ? 1 : 0;

  const data = {
    id: categoryId,
    name: categoryName,
    isListed: isListed,
  };
  try {
    // Aguarde a resposta da API
    const res = await api.post("/atualizar-categoria", data, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    const message = res.data.message || "Categoria atualizada com sucesso!";

    Toastify({
      text: message,
      duration: 3000,
      gravity: "top",
      position: "center",
      backgroundColor: "linear-gradient(to right, #000, #111)"
    }).showToast();

    setTimeout(() => {
      location.reload(); // Recarrega a página após a atualização
    }, 20);
  } catch (error) {
    console.error("Erro na requisição:", error);
    Toastify({
      text: "Erro ao atualizar a categoria!",
      duration: 3000,
      gravity: "top",
      position: "center",
      backgroundColor: "linear-gradient(to right, #000, #111)"
    }).showToast();
  }
}


function setupEditButtons() {
  const editButtons = document.querySelectorAll(
    'button[onclick^="updateCategory"]'
  );
  editButtons.forEach((button) => {
    const categoryId = button.getAttribute("onclick").match(/\d+/)[0];
    const categoryElement = button.closest("li");
    const categoryName = categoryElement.textContent.trim();

    const isListed = button.getAttribute("data-is-listed") || 0;

    button.setAttribute(
      "onclick",
      `openEditModal(${categoryId}, '${categoryName}', ${isListed})`
    );
  });
}
document.addEventListener("DOMContentLoaded", setupEditButtons);

async function GetProducts() {
  const loading = document.getElementById("loading");
  loading.style.display = "block";
  try {
    
    const res = await api.get("/produtos");

    const produtos = Array.isArray(res.data) ? res.data : res.data.produtos;
    console.log("Dados da API:", produtos);

    renderProducts(produtos);
  } catch (error) {
    console.error("Erro ao buscar produtos:", error);
  } finally {
    const loading = document.getElementById("loading");
    loading.style.display = "none";
  }
}

function renderProducts(produtos) {
  const ul = document.getElementById("product-list");

  if (!ul) {
    console.error("Elemento UL não encontrado!");
    return;
  }

  ul.innerHTML = "";

  if (produtos == undefined) {
    const li = document.createElement("li");
    li.classList.add("text-red-500", "text-2xl", "font-bold");

    li.innerHTML = `
       Nenhum produto cadastrado!
      `;
    ul.appendChild(li);
    return;
  }

  console.log(produtos.length);
  produtos.forEach((produto) => {
    const li = document.createElement("li");
    li.classList.add(
      "max-w-[600px]",
      "rounded-md",
      "bg-white",
      "text-[#252525]",
      "flex",
      "justify-between",
      "items-center",
      "product-item"
    );
    li.setAttribute("data-id", produto.id);
    li.setAttribute("data-listed", produto.is_listed);
    li.style.padding = "8px";
    li.style.marginBottom = "12px";
    
    const priceFrom = parseFloat(produto.price_from); 
    li.innerHTML = `
            <div class="flex gap-4">
              <img 
                src="${produto.image_url}" 
                alt="${produto.name}"
                style="max-width:100px; max-height:80px;object-fit:cover;">
              <div>
                <p class="text-2xl text-semibold product-name">${produto.name}</p> 
                <span class="font-semibold text-base ">Categoria:</span> ${
                  produto.category_name
                } <br>
                <span class="font-semibold text-base product-price">R$</span> ${formatPrice(produto.price)} 
                <span class="font-semibold text-base" style="margin-left: 10px;" >Listado?</span> ${
                  produto.is_listed == 1 ? "<span class='text-green-600'>Sim</span>" : "<span class='text-red-600'>Não</span>"
                }
              </div>
            </div>
            <span class="flex gap-4">
              <button class="cursor-pointer openUpdateModal ">
                <img src="http://localhost/deliveryrs/public/assets/images/edit.svg" data-id="${
                  produto.id
                }" class="w-8" alt="editar">
              </button>
              <button class="cursor-pointer">
                <img src="http://localhost/deliveryrs/public/assets/images/trash.svg" class="w-8" alt="editar">
              </button>
            </span>
          `;

    ul.appendChild(li);
  });
}

document.getElementById('searchInput').addEventListener('input', function() {
  const searchValue = this.value.toLowerCase();
  
  const products = document.querySelectorAll('.product-item');
  
  products.forEach(function(product) {
      const productName = product.querySelector('.product-name').textContent.toLowerCase();
      const productPrice = product.querySelector('.product-price').textContent.toLowerCase();
      
      if (productName.includes(searchValue) || productPrice.includes(searchValue)) {
        product.style.display = 'flex'; 
      } else {
        product.style.display = 'none';
      }
  });
});


async function carregarCategorias() {
  try {
    const response = await api.get("/categorias");
    const categorias = response.data;

    const select = document.getElementById("category_selected");

    select.innerHTML = '<option value="">Escolha uma categoria</option>';

    categorias.forEach((categoria) => {
      const option = document.createElement("option");
      option.value = categoria.id;
      option.textContent = categoria.name;
      select.appendChild(option);
    });
  } catch (error) {
    console.error("Erro ao carregar categorias:", error);
  }
}

function modalEditP() {
  const modal = document.querySelector(".modalUpdate");

  document
    .getElementById("product-list")
    .addEventListener("click", async function (e) {
      if (e.target && e.target.closest("button.openUpdateModal")) {
        const li = e.target.closest("li");
        const productId = li.getAttribute("data-id");
        const modalForm = document.getElementById("productEditForm");
        modalForm.setAttribute("data-id", productId);
        try {
          const res = await api.get(`/produto/${productId}`);
          const produto = res.data[0];

          document.getElementById("category_selected").value =
            produto.category_id;
          document.getElementById("updateNameProduct").value =
            produto.name ?? "";

   
          document.getElementById("Updateprice").value =
          formatPrice(produto.price) ?? null;

            
            
          document.getElementById("UpdatePricefrom").value =
          formatPrice(produto.price_from) ?? null;


          document.getElementById("UpdateDescription").value =
            produto.description ?? "";
          document.querySelector('input[name="UpdateIsListed"]').checked =
            produto.is_listed ? 1 : 0;
        } catch (e) {}
        modal.style.display = "block";
      }
      // Feche o modal clicando fora dele
      if (e.target === modal) {
        modal.style.display = "none"; // Esconde o modal
      }
    });
}

async function updateProduct() {
  const modalForm = document.getElementById("productEditForm");
  modalForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const productId = modalForm.getAttribute("data-id");
    if (!productId) return console.error("Product not found");

    const formData = new FormData(modalForm);
    console.log("FormData contents:");
    for (let [key, value] of formData.entries()) {
      console.log(key, value);
    }
    try {
      const res = await api.post(`/atualizar-produto/${productId}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      if (res.data.message) {
        showToastify(res.data.message);
      }
    } catch (error) {
      console.error("Error:", error);
      showToastify(error.response?.data?.message || "Error updating product");
    }

   await GetProducts()
  });
}

document.addEventListener("DOMContentLoaded", () => {
  modalEditP();
  GetProducts();
  carregarCategorias();
  updateProduct();

  let closeModalEdit = document.querySelector(".closeUpdateModal");
  closeModalEdit.addEventListener("click", () => {
    const modal = document.querySelector(".modalUpdate");
    if (modal.style.display == "block") {
      modal.style.display = "none";
    }
  });
});


const formatPrice = (value) => {
  const num = parseFloat(value || 0);
  return num.toLocaleString("pt-BR", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
};

