// document.addEventListener('DOMContentLoaded', () => {
const api = axios.create({
  baseURL: "http://localhost/deliveryrs/public/",
  headers: {
    //'Authorization': 'Bearer seu-token-aqui',
    "Content-Type": "application/json",
  },
});

function ToggleSidebar() {
  document.addEventListener("DOMContentLoaded", () => {
    document
      .getElementById("toggleSidebar")
      .addEventListener("click", function () {
        console.log("clickado");
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("collapsed");
        document.body.style.gridTemplateColumns = sidebar.classList.contains(
          "collapsed"
        )
          ? "80px 1fr"
          : "250px 1fr";
      });
  });
}

async function sortable() {
  const sortableList = document.getElementById("sortable-list");

  new Sortable(sortableList, {
    animation: 150,
    onEnd: function (evt) {
      let order = [];
      document.querySelectorAll("#sortable-list li").forEach((item, index) => {
        order.push({
          id: item.getAttribute("data-id"),
          position: index + 1,
        });
      });
      try {
        const res = api.post("/atualizar-ordem", {
          order: order,
        });

        if (res)
          return Toastify({
            text: "Categorias atualizadas com sucesso!",
            duration: 3000,
            gravity: "top",
            position: "center",
            backgroundColor: "linear-gradient(to right, #000, #111)",
          }).showToast();
      } catch (e) {
        alert(e);
      }
    },
  });
}

ToggleSidebar();
sortable();

function openEditModal(categoryId, categoryName, isListed) {
  const editModal = document.getElementById("editModal");
  editModal.style.display = "flex";

  document.getElementById("editCategoryName").value = categoryName;
  document.getElementById("editIsListed").checked = isListed == 1;
  document.getElementById("editCategoryId").value = categoryId;

  const cancelEditButton = document.getElementById("cancelEdit");
  cancelEditButton.onclick = function () {
    editModal.style.display = "none";
  };
  const closeEditModal = document.getElementById("closeEditModal");
  closeEditModal.onclick = function () {
    editModal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == editModal) {
      editModal.style.display = "none";
    }
  };
  const editForm = document.getElementById("editCategoryForm");
  editForm.onsubmit = function (event) {
    event.preventDefault();
    updateCategory(categoryId);
    editModal.style.display = "none";
  };
}function updateCategory(categoryId) {
  const categoryName = document.getElementById("editCategoryName").value;
  const isListed = document.getElementById("editIsListed").checked ? 1 : 0;

  // Debugging
  console.log('categoryName:', categoryName);
  console.log('isListed:', isListed);

  const data = {
    id: categoryId,
    name: categoryName,
    isListed: isListed
  };

  console.log('Dados que serão enviados:', data);  // Verifique os dados que estão sendo enviados para o backend
  
  api.post("/atualizar-categoria", data, {
    headers: {
      "Content-Type": "application/json",
    }
  })
  .then(response => {
    if (response.data.success) {
      Toastify({
        text: "Categoria atualizada!",
        duration: 3000,
        gravity: "top",
        position: "center",
        backgroundColor: "linear-gradient(to right, #000, #111)"
      }).showToast();
    } 
    setTimeout(() => {
      location.reload(); 
    }, 20);
  })
  .catch(error => {
    console.error("Erro na requisição:", error);
    Toastify({
      text: "Erro ao enviar os dados. Tente novamente.",
      duration: 3000,
      gravity: "top",
      position: "center",
      backgroundColor: "linear-gradient(to right, #000, #111)"
    }).showToast();
    setTimeout(() => {
      location.reload();
    }, 20);
  });
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

//   function openModal(categoryId) {

//     const modal = document.getElementById('modal');
//     modal.style.display = 'flex';

//     const confirmButton = document.getElementById('confirmDelete');
//     confirmButton.onclick = function() {
//         removeCategory(categoryId);
//         modal.style.display = 'none';
//     };

//     const cancelButton = document.getElementById('cancelDelete');
//     cancelButton.onclick = function() {
//         modal.style.display = 'none';
//     };

//     const closeModal = document.getElementById('closeModal');
//     closeModal.onclick = function() {
//         modal.style.display = 'none';
//     };

//     window.onclick = function(event) {
//         if (event.target == modal) {
//             modal.style.display = 'none';
//         }
//     };
// }

// function removeCategory(categoryId) {

// }

// function setupDeleteButtons() {
//     const deleteButtons = document.querySelectorAll('button[onclick^="removeCategory"]');
//     deleteButtons.forEach(button => {
//         const categoryId = button.getAttribute('onclick').match(/\d+/)[0];
//         button.setAttribute('onclick', `openModal(${categoryId})`);
//     });
// }

// document.addEventListener('DOMContentLoaded', setupDeleteButtons);
