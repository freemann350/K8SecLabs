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
        <table class="table table-striped" style="text-align:center" id="dt">
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
            <tr>
              <td>{{ $environment->name }}</td>
              <td>{{ $environment->created_at }}</td>
              <td>{{ $environment->userDefinition->definition->name }}</td>
              <td>{{ $environment->quantity }} &nbsp;</td>
              <td>
                  <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Environments.show',$environment->id)}}"><i class="mdi mdi-view-list"></i></a>
                  <a class="btn btn-outline-dark btn-fw btn-rounded btn-sm" href="{{ route('Environments.edit', $environment->id) }}"><i class="mdi mdi-pencil"></i></a>
                  <a class="btn btn-outline-danger btn-fw btn-rounded btn-sm" href="#" onclick="_delete('Are you sure you want to delete the environment &quot;{{ $environment->name }}&quot; ({{ $environment->id }})','{{ route("Environments.destroy", $environment->id) }}')"><i class="mdi mdi-trash-can-outline"></i></a>
                  <a class="btn btn-outline-warning btn-fw btn-rounded btn-sm" href="#" onclick="access_code('{{$environment->access_code}}')"><i class="mdi mdi-key"></i></a>
              </td>
            </tr>
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
        <table class="table table-striped" style="text-align:center" id="dt1">
          <thead>
            <tr>
              <th>Name</th>
              <th>Runtime</th>
              <th>Definition</th>
              <th>Quantity of scenarios</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environments as $environment)
            <tr>
              <td>{{ $environment->name }}</td>
              <td>From: {{ $environment->created_at }} <br> To:{{ isset($environment->end_date) ? $environment->end_date : "N/A" }}</td>
              <td>{{ $environment->userDefinition->definition->category->name }}</td>
              <td>{{ $environment->quantity }} &nbsp;</td>
              <td>
                  <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="{{route('Environments.show',$environment->id)}}"><i class="mdi mdi-view-list"></i></a>
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
