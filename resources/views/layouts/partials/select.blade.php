<select name="{{$name}}" class="form-control" 
        @isset($onchange) onchange="{{$onchange}}" @endisset
        @isset($id) id="{{$id}}" @endisset
        
        >
     <option value="">Selecione uma Opção</option>
                @foreach ($list as $key)
                <option value="{{ $key->id }}"
                        @if ($key->id == old('myselect', $param))
                    selected="selected"
                    @endif
                    >{{ $key }}</option>
                @endforeach
            </select>