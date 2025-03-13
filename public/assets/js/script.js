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




  function openEditModalProducts() {  
    document.querySelector('.modalProduct').classList.add('show');
}

function closeModal() {
    document.querySelector('.modalProduct').classList.remove('show');
}

  

  document.addEventListener("DOMContentLoaded", function () {
    const phoneMask = document.getElementById("telefone");

    if (phoneMask) {
      const maskOp = {
        mask: "(00) 9 0000-0000" // Corrigido o formato da máscara
      };
      
      IMask(phoneMask, maskOp);
    }

    const precoInput = document.getElementById('price');
    const precoFromInput = document.getElementById('price_from');
       IMask(precoInput, {
            mask: Number, 
            scale: 2,
            thousandsSeparator: '.', 
            padFractionalZeros: true, 
            normalizeZeros: true, 
            radix: ',',
        });
        IMask(precoFromInput, {
          mask: Number, 
          scale: 2,
          thousandsSeparator: '.', 
          padFractionalZeros: true, 
          normalizeZeros: true, 
          radix: ',',
      });
  });
