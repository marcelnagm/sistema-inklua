<select class="browser-default custom-select" 
        @isset($name) name="{{$name}}"  @endisset        
        @isset($id)  id="{{$id}}" @endisset
        @isset($onchange) onchange='{{$onchange}}' @endisset>
    <option value="">Selecione uma Opção</option>
</select>
<script type="text/javascript">

    function depend(id) {

        var select = document.getElementById(id);
        var cat_id = select.options[select.selectedIndex].value;
        console.log('#' + id);
        console.log(cat_id);
        $.ajax({
            url: "{{ $route }}",
            type: "POST",
            data: {
                id: cat_id
            },
            success: function (data) {
                $('#{{$id}}').empty();
                console.log(data);
                $.each(data, function (index, subcategory) {                    
                    $('#{{$id}}').append('<option value="' + data[index].id + '">' + data[index].name + '</option>');
                })
            }
        });
    }
</script>