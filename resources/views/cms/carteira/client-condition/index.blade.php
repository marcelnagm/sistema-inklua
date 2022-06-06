
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <h6 class="m-0 font-weight-bold text-primary">Condições Contratuais
                        </h6>

                        <div class="float-right">
                            <a href="{{ route('client_condition_sp.create',array('id' => $client->id)) }}" class="btn btn-primary btn-sm float-right ml-1"  data-placement="left">
                                Nova Condição
                            </a>                               
                            <a  href="{{  route('conditions_name.index') }}" class="btn btn-primary btn-sm float-right "  data-placement="left">
                                Tipos de condicões
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>


                                    <th>Tipo de condição</th>                                 
                                    <th>Tipo de Taxa</th>
                                    <th>Taxa</th>
                                    <th>Garantia</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientConditions as $clientCondition)
                                <tr>

                                    <?php $cond = $clientCondition->condition()->first() ?>
                                    <td>

                                        {{ $cond->name }}
                                        @if($cond->intervals)
                                        @if($cond->money) R$ @endif{{ $clientCondition->start_cond }} - @if($cond->money) R$ @endif{{ $clientCondition->end_cond }}
                                        @endif

                                    </td>

                                    <td>{{ $clientCondition->brute ?  'Líquida' : 'Bruta' }}</td>
                                    <td>{{ $clientCondition->tax }}%</td>
                                    <td>{{ $clientCondition->guarantee }} dias</td>
                                    <td>
                                        <form action="{{ route('client_condition.destroy',$clientCondition->id) }}" method="POST" class="float-right">
                                            <!--<a class="btn btn-sm btn-primary " href="{{ route('client_condition.show',$clientCondition->id) }}"><i class="fa fa-fw fa-eye"></i> Exibir</a>-->
                                            <a class="btn btn-sm btn-success" href="{{ route('client_condition.edit',$clientCondition->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Desativar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
