@extends('template.layout')
@section('main-content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Running environments</h4>
      <p class="card-description">
          List of currently running Environments that you joined
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt">
          <thead>
            <tr>
              <th>Name</th>
              <th>Creation date</th>
              <th>Definition</th>
              <th>Created by</th>
              <th>Last access</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environmentAccesses as $environmentAccess)
            @if ($environmentAccess->environment->end_date == null)
              <tr>
                <td>{{ $environmentAccess->environment->name }}</td>
                <td class="text-center">{{ $environmentAccess->created_at }}</td>
                <td>{{ $environmentAccess->environment->userDefinition->definition->name }}</td>
                <td>{{ $environmentAccess->environment->userDefinition->user->name }} &nbsp;</td>
                <td class="text-center">{{ isset($environmentAccess->last_access) ? $environmentAccess->last_access : 'N/A' }}</td>
                <td>
                    <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="#" onclick="window.open('{{route('EnvironmentAccesses.show',$environmentAccess->id)}}','Show Environment','width=1024,height=720')"><i class="mdi mdi-information-outline"></i></a>
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
      <h4 class="card-title">Past joined environments</h4>
      <p class="card-description">
          List of your previously joined Environments that have ended
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt1">
          <thead>
            <tr>
              <th class="text-center">Name</th>
              <th class="text-center">Runtime</th>
              <th class="text-center">Definition</th>
              <th class="text-center">Created By</th>
              <th class="text-center">Last access</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environmentAccesses as $environmentAccess)
            @if ($environmentAccess->environment->end_date != null)
            <tr>
                <td>{{ $environmentAccess->environment->name }}</td>
                <td>From: {{ $environmentAccess->created_at }} <br> To: {{ $environmentAccess->environment->end_date }}</td>
                <td>{{ $environmentAccess->environment->userDefinition->definition->name }}</td>
                <td>{{ $environmentAccess->environment->userDefinition->user->name }} &nbsp;</td>
                <td class="text-center">{{ isset($environmentAccess->last_access) ? $environmentAccess->last_access : 'N/A' }}</td>
                <td>
                    <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="#" onclick="window.open('{{route('EnvironmentAccesses.show',$environmentAccess->id)}}','Show Environment','width=1024,height=720')"><i class="mdi mdi-information-outline"></i></a>
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
