@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Your Definitions</h4>
      <p class="card-description">
          List of all your Definitions
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt">
          <thead>
            <tr>
              <th>Name</th>
              <th>Category</th>
              <th>Privacy</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($definitions as $definition)
            <tr>
              <td>{{ $definition->name }}</td>
              <td>{{ $definition->category->name }}</td>
              <td>{{ $definition->private ? 'Private' : 'Public' }}</td>
              <td>
                  <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Definitions.show',$definition->id)}}"><i class="mdi mdi-information-outline"></i></a>
                  <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm"  href="{{route('Definitions.download',$definition->id)}}"><i class="mdi mdi-file-download"></i></a>
                  <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm" href="{{ route('Definitions.edit', $definition->id) }}"><i class="mdi mdi-pencil"></i></a>
                  <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to delete the definition &quot;{{ $definition->name }}&quot; ({{ $definition->id }})','{{ route("Definitions.destroy", $definition->id) }}')"><i class="mdi mdi-trash-can-outline"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Saved Definitions</h4>
      <p class="card-description">
          List of all your saved Definitions
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt1">
          <thead>
            <tr>
              <th class="text-center">Name</th>
              <th class="text-center">Category</th>
              <th class="text-center">Privacy</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($userDefinitions as $userDefinition)
            <tr>
              <td>{{ $userDefinition->definition->name }}</td>
              <td>{{ $userDefinition->definition->category->name }}</td>
              <td>{{ $userDefinition->definition->private ? 'Private' : 'Public' }}</td>
              <td>
                  <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Definitions.show',$userDefinition->definition_id)}}"><i class="mdi mdi-information-outline"></i></a>
                  <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm"  href="{{route('Definitions.download',$userDefinition->definition_id)}}"><i class="mdi mdi-file-download"></i></a>
                  <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm"  href="#" onclick="_delete('Are you sure you want to unassociate the definition &quot;{{ $userDefinition->definition->name }}&quot; ({{ $userDefinition->id }})','{{ route("Definitions.removeDefinition", $userDefinition->id) }}')"><i class="mdi mdi-playlist-minus"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
