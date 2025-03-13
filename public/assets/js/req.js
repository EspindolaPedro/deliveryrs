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
