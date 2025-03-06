

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
    const res = api.post("/atualizar-categoria", data, {
      headers: {
        "Content-Type": "application/json",
      }
    })
   if (res) {
    Toastify({
      text: "Categoria atualizada!",
      duration: 3000,
      gravity: "top",
      position: "center",
      backgroundColor: "linear-gradient(to right, #000, #111)"
    }).showToast();
    setTimeout(() => {
      location.reload(); 
    }, 20);
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