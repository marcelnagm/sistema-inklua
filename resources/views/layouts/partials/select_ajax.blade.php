 <select name="{{$name}}" class="form-control form-control-lg">
                @foreach ($list as $key)
                <option value="{{ $key->id }}"
                        @if ($key->id == old('myselect', $param))
                    selected="selected"
                    @endif
                    >{{ $key }}</option>
                @endforeach
            </select>