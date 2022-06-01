<?php $i = 0; ?>

<div class="col-xl-12">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Experiência profissional do Candidato - Hunting
        </h6>
    </div>                



    <div class="card-body">
    <div class="float-right">
        <a href="{{ route('work.hunt.create',array('id' =>  $candidateHunting->id)) }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
            Adicionar Novo
        </a>
    </div>
    </div>



    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead">
                    <tr>
                        <th >Função</th>
                        <th>Empresa</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidateExperienceHuntings as $candidateExperienceHunting)
                    <tr>

                        <td>{{ $candidateExperienceHunting->role }}</td>
                        <td>{{ $candidateExperienceHunting->company }}</td>
                        <td>{{ $candidateExperienceHunting->start_at->format('d/m/Y')  }}</td>
                        <td>{{ $candidateExperienceHunting->end_at <> NULL ? $candidateExperienceHunting->end_at->format('d/m/Y') : 'Ativo' }}</td>

                        <td>
                            <form action="{{ route('work.destroy',$candidateExperienceHunting->id) }}" method="POST">                                
                                <a class="btn btn-sm btn-success" href="{{ route('work.edit',$candidateExperienceHunting->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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

</div>
