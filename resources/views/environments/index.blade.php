@extends('template.layout')
@section('main-content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Your Running Environments</h4>
      <p class="card-description">
          List of your currently running Environments
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt">
          <thead>
            <tr>
              <th>Name</th>
              <th>Creation date</th>
              <th>Definition</th>
              <th>Quantity of scenarios</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environments as $environment)
              @if ($environment->end_date == null)              
              <tr>
                <td>{{ $environment->name }}</td>
                <td class="text-center">{{ $environment->created_at }}</td>
                <td>{{ $environment->userDefinition->definition->name }}</td>
                <td>{{ $environment->quantity }} &nbsp;</td>
                <td>
                    <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Environments.show',$environment->id)}}"><i class="mdi mdi-view-list"></i></a>
                    <a class="btn btn-outline-warning btn-fw btn-rounded btn-sm" href="#" onclick="access_code('{{$environment->access_code}}')"><i class="mdi mdi-key"></i></a>
                    <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to stop the environment &quot;{{ $environment->name }}&quot; ({{ $environment->id }})','{{ route("Environments.destroy", $environment->id) }}')"><i class="mdi mdi-stop-circle-outline"></i></a>
                </td>
              </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Your Past Environments</h4>
      <p class="card-description">
          List of all your past Environments
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt1">
          <thead>
            <tr>
              <th class="text-center">Name</th>
              <th class="text-center">Runtime</th>
              <th class="text-center">Definition</th>
              <th class="text-center">Quantity of scenarios</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environments as $environment)
            @if ($environment->end_date != null)
            <tr>
              <td>{{ $environment->name }}</td>
              <td>From: {{ $environment->created_at }} <br> To:{{ $environment->end_date }}</td>
              <td>{{ $environment->userDefinition->definition->category->name }}</td>
              <td>{{ $environment->quantity }} &nbsp;</td>
              <td>
                  <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Environments.show',$environment->id)}}"><i class="mdi mdi-view-list"></i></a>
                  <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to delete the environment &quot;{{ $environment->name }}&quot; ({{ $environment->id }})','{{ route("Environments.destroy", $environment->id) }}')"><i class="mdi mdi-trash-can-outline"></i></a>
              </td>
            </tr>
            @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
