// document.addEventListener('DOMContentLoaded', () => {
const api = axios.create({
  baseURL: "http://localhost/deliveryrs/public/",
  headers: {
    //'Authorization': 'Bearer seu-token-aqui',
    "Content-Type": "application/json",
  },
});



function menuLinks() {
  
  const links = document.querySelectorAll('.sidebar-ul li a');
  const currentPath = window.location.pathname.replace(/\/$/, ""); // Remove barra final

  links.forEach(link => {
      const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/$/, "");
      
      if (linkPath === currentPath) {
          link.classList.add("active");
      } else {
          link.classList.remove("active");
      }
  });

}



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

  menuLinks()
  ToggleSidebar();

  

  document.addEventListener("DOMContentLoaded", function () {
    const phoneMask = document.getElementById("telefone");

    if (phoneMask) {
      const maskOp = {
        mask: "(00) 9 0000-0000" // Corrigido o formato da máscara
      };
      
      IMask(phoneMask, maskOp);
    }
  });

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
