@extends('template.layout')
@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Environments</h4>
      <p class="card-description">
          List of all past Environments
      </p>
      <table class="table table-striped" style="text-align:center" id="dt">
        <thead>
          <tr>
            <th>Name</th>
            <th>Definition</th>
            <th>Category</th>
            <th>Quantity of scenarios</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($environments as $environment)
          <tr>
            <td>{{ $environment->name }}</td>
            <td>{{ $environment->userDefinition->definition->name }}</td>
            <td>{{ $environment->userDefinition->definition->category->name }}</td>
            <td>{{ $environment->qty }} &nbsp;</td>
            <td>
                <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Environments.show',$environment->id)}}"><i class="mdi mdi-information-outline"></i></a>
                <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm" href="{{ route('Environments.edit', $environment->id) }}"><i class="mdi mdi-pencil"></i></a>
                <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to delete the environment &quot;{{ $environment->name }}&quot; ({{ $environment->id }})','{{ route("Environments.destroy", $environment->id) }}')"><i class="mdi mdi-trash-can-outline"></i></a>
                <a class="btn btn-outline-warning btn-fw btn-rounded btn-sm" href="#"><i class="mdi mdi-key"></i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="d-grid gap-2">
  <a class="btn btn-success btn-lg btn-block" href="{{ route('Environments.create') }}"><i class="mdi mdi-plus-circle"></i> Create new Environment</a>
</div>
@endsection
