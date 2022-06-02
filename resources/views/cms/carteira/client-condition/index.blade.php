
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Client Condition') }}
                        </span>

                        <div class="float-right">
                            <a href="{{ route('client_condition_sp.create',array('id' => $client->id)) }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                {{ __('Create New') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>


                                    <th>Condition Id</th>
                                    <th>Client Id</th>
                                    <th>Brute</th>
                                    <th>Tax</th>
                                    <th>Guarantee</th>
                                    <th>Start Cond</th>
                                    <th>End Cond</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientConditions as $clientCondition)
                                <tr>


                                    <td>{{ $clientCondition->condition_id }}</td>
                                    <td>{{ $clientCondition->client_id }}</td>
                                    <td>{{ $clientCondition->brute }}</td>
                                    <td>{{ $clientCondition->tax }}</td>
                                    <td>{{ $clientCondition->guarantee }}</td>
                                    <td>{{ $clientCondition->start_cond }}</td>
                                    <td>{{ $clientCondition->end_cond }}</td>

                                    <td>
                                        <form action="{{ route('client_condition.destroy',$clientCondition->id) }}" method="POST">
                                            <a class="btn btn-sm btn-primary " href="{{ route('client_condition.show',$clientCondition->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                            <a class="btn btn-sm btn-success" href="{{ route('client_condition.edit',$clientCondition->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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

        </div>
    </div>
</div>
