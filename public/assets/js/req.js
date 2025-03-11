// Função para manipular o upload de arquivo
   
function handleFile(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.querySelector(".preview-photo"); 
            let preview = document.getElementById('preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview'; 
                previewContainer.appendChild(preview);
            }

            preview.src = e.target.result;
            preview.style.display = 'flex'; 
        };
        reader.readAsDataURL(file);
    }
}

function loadCategories() {
    const categories = [
        { id: 1, name: 'Eletrônicos' },
        { id: 2, name: 'Roupas' },
        { id: 3, name: 'Alimentos' },
    ];

    const select = document.getElementById('category');
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

// Função para lidar com o envio do formulário
function handleSubmit(event) {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value;
    const category = document.getElementById('category').value;
    const avatar = document.getElementsByClassName('product').files[0];

    if (!name || !price || !description || !category || !avatar) {
        showToastify('Por favor, preencha todos os campos.')
        return;
    }

    // Aqui você pode enviar os dados para o servidor
    const formData = new FormData();
    formData.append('name', name);
    formData.append('price', price);
    formData.append('description', description);
    formData.append('category', category);
    formData.append('avatar', avatar);

    console.log('Dados do formulário:', Object.fromEntries(formData.entries()));
    showToastify('Produto cadastrado com sucesso!');
}

// Carrega as categorias ao carregar a página
document.addEventListener('DOMContentLoaded', loadCategories);

// Adiciona o listener para o envio do formulário
document.getElementById('productForm').addEventListener('submit', handleSubmit);







function handleSubmit(event) {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value;
    const category = document.getElementById('avatarCompany').value;
    const avatar = document.getElementById('avatar').files[0];

    if (!name || !price || !description || !category || !avatar) {
        showToastify('Por favor, preencha todos os campos.')
        return;
    }

    // Aqui você pode enviar os dados para o servidor
    const formData = new FormData();
    formData.append('name', name);
    formData.append('price', price);
    formData.append('description', description);
    formData.append('avatarCompany', category);
    formData.append('avatar', avatar);

    console.log('Dados do formulário:', Object.fromEntries(formData.entries()));
    showToastify('Produto cadastrado com sucesso!');
}

// Carrega as categorias ao carregar a página
document.addEventListener('DOMContentLoaded', loadCategories);

// Adiciona o listener para o envio do formulário
document.getElementById('productForm').addEventListener('submit', handleSubmit);