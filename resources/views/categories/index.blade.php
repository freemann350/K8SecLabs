@extends('template.layout')

@section('main-content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
      <div class="card-body">
          <h4 class="card-title">Categories</h4>
          <p class="card-description">
          List of all categories
          </p>
          <div class="table-responsive">
          <table class="table table-hover table-striped" style="text-align:center" id="dt">
              <thead>
              <tr>
                  <th>Name</th>
                  <th>Training Type</th>
                  <th>Actions</th>
              </tr>
              </thead>
              <tbody>
              @foreach($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->training_type == 'R')
                        Red
                    @elseif($category->training_type == 'B')
                        Blue
                    @elseif($category->training_type == 'U')
                        Undefined
                    @endif
                </td>
                <td>
                    <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm" href="{{ route('Categories.edit', $category->id) }}"><i class="mdi mdi-pencil"></i></a>
                    <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to delete the category &quot;{{ $category->name }}&quot; ({{ $category->id }})','{{ route("Categories.destroy", $category->id) }}')"><i class="mdi mdi-trash-can-outline"></i></a>
                </td>
              </tr>
              @endforeach
              </tbody>
          </table>
          </div>
          <br>
          <button onclick="location.reload();" type="button" class="btn btn-info btn-lg btn-block"><i class="mdi mdi-refresh"></i>Refresh info</button>
      </div>
  </div>
</div>
<div class="d-grid gap-2">
  <a class="btn btn-success btn-lg btn-block" href="{{ route('Categories.create') }}"><i class="mdi mdi-plus-circle"></i> Add new category</a>
</div>
@endsection
