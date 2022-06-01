<?php $i = 1; ?>

<!-- Card Header - Dropdown -->
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Formação do Candidato - Hunting
    </h6>
</div>                



    <div class="card-body">
        <div class="float-right">
            <a href="{{ route('education.hunt.create',array('id' =>  $candidateHunting->id)) }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                Adicionar Novo
            </a>
        </div>
    </div>


<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead">
                <tr>
                    <th>Nível de Instrução</th>
                    <th>Instituição</th>
                    <th>Curso</th>
                    <th>Início</th>
                    <th>Fim</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($candidateEducationHuntings as $candidateEducationHunting)
                <tr>
                    

                    <td>{{ $candidateEducationHunting->level_education() }}</td>
                    <td>{{ $candidateEducationHunting->institute }}</td>
                    <td>{{ $candidateEducationHunting->course }}</td>
                    <td>{{ $candidateEducationHunting->start_at->format('d/m/Y')  }}</td>
                    <td>{{  $candidateEducationHunting->end_at <> NULL ?$candidateEducationHunting->end_at->format('d/m/Y') : 'Ativo'  }}</td>

                    <td>
                        <form action="{{ route('education.destroy',$candidateEducationHunting->id) }}" method="POST" class="float-right">                            
                            <a class="btn btn-sm btn-success" href="{{ route('education.edit',$candidateEducationHunting->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

