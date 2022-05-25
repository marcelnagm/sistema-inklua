<select class="browser-default custom-select" name="{{$name}}" id="{{$name}}">
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
                $('#{{$name}}').empty();
                console.log(data);
                $.each(data, function (index, subcategory) {
                    console.log(data[index].id);
                    console.log(data[index].name);
                    $('#{{$name}}').append('<option value="' + data[index].id + '">' + data[index].name + '</option>');
                })
            }
        });
    }
</script>