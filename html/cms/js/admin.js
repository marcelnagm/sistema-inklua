

$(document).ready(function () {  

    $('.delete-action').on('click', function(){
        console.log('aciona delete')
        const btn = $(this);
        $('.item-delete-title').html(btn.attr('data-title'))
        $('#delete-form').attr('action', btn.attr('data-url-delete'))
    })
    

    if( $('#form-article')[0] || $('#form-ad')[0] || $('#form-position')[0] ){
        
        /* Função para habilitar ou desabilitar upload de arquivo */
        const select_to_disable = document.querySelectorAll('.select-remove');
        [].forEach.call(select_to_disable, function( el ) {
            el.addEventListener('change', (event) => {
                el_file = document.getElementById( el.getAttribute('data-file-input') );
                el_file.disabled = el.checked;
            } );
        });


        /* Função para exibir legenda quando arquivo é selecionado */
        /* const input_files_with_label = document.querySelectorAll('.file-with-label');
        [].forEach.call(input_files_with_label, function( file_input ) {
            file_input.addEventListener('change', (event) => {
                el_label = document.getElementById( file_input.getAttribute('data-file-caption-id') );
                el_label.hidden = file_input.files.length == 0 ;
            } );
        }); */

         /* Preview de imagem no upload */
        const imageEl = document.getElementById('image');
        let output = document.getElementById("image-output");

        let loadFile = imageEl.addEventListener('change', (event) =>{
            if( !output ){
                $('#img-preview-wrapper').html('<div class="form-row mb-3"><div class="col-lg-12"><img id="image-output" alt="" class="show-image" src="'+ URL.createObjectURL(event.target.files[0]) +'"></div></div>');
                output = document.getElementById("image-output");
            }else{
                output.src = URL.createObjectURL(event.target.files[0]);
            }
        });
    } 
    
    $('.group-multiple').select2();

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });
});

const masks = {
    date(value) {
        console.log(value);
        return value
            .replace(/\D/g, '')
            .replace(/(\d{2})(\d{2})/, '$1/$2')
            .replace(/(\d{2})(\d{2})/, '$1/$2')
            .replace(/(\/\d{4})\d+?$/, '$1')
    }
};

document.querySelectorAll('.input-time').forEach(($inputTime) => {
    const field = $inputTime.dataset.js;

    $inputTime.addEventListener('input', (e) => {
        e.target.value = masks[field](e.target.value)
    }, false);

});