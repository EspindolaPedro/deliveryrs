
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
            return showToastify("Categorias atualizadas com sucesso!")
        } catch (e) {
          alert(e);
        }
      },
    });
  }
  sortable();