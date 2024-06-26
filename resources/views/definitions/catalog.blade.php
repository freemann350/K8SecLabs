@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Definitions</h4>
      <p class="card-description">
          List of all your Definitions
      </p>
      <table class="table table-striped text-center" id="dt">
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($definitions as $definition)
          @if (($definition->private == 1 && $definition->user_id == Auth::user()->id) || $definition->private == 0)
          <tr>
            <td>{{ $definition->name }}</td>
            <td>{{ $definition->category->name }}</td>
            <td>{{ $definition->user->name }}</td>
            <td>
                <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Definitions.show',$definition->id)}}"><i class="mdi mdi-information-outline"></i></a>
                <a class="btn btn-outline-success btn-fw btn-rounded btn-sm"  href="{{ route('Definitions.addDefinition', $definition->id) }}"><i class="mdi mdi-playlist-plus"></i></a>
                <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm"  href="{{route('Definitions.download',$definition->id)}}"><i class="mdi mdi-file-download"></i></a>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
