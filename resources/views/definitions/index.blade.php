@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Definitions</h4>
      <p class="card-description">
        <a href="{{ route('Definitions.create') }}" class="btn btn-primary">Create Definition</a>
      </p>
      <table class="table table-striped" id="dt">
        <thead>
          <tr>
            <th>Name</th>
            <th>Path</th>
            <th>Category</th>
            <th>User</th>
            <th>Private</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($definitions as $definition)
          <tr>
            <td>{{ $definition->name }}</td>
            <td>{{ $definition->path }}</td>
            <td>{{ $definition->category->name }}</td>
            <td>{{ $definition->user->name }}</td>
            <td>{{ $definition->private ? 'Yes' : 'No' }}</td>
            <td>
              <a href="{{ route('definitions.edit', $definition->definition_id) }}" class="btn btn-warning btn-sm">Edit</a>
              <form action="{{ route('definitions.destroy', $definition->definition_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
