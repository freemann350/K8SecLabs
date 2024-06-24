@extends('template.layout')
@section('main-content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Environments</h4>
      <p class="card-description">
          List of accessible environments on this Environment
      </p>
      <div class="table-responsive">
        <table class="table table-striped text-center" id="dt">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Assigned User</th>
              <th class="text-center">Last Access</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($environmentAccesses as $environmentAccess)
              <tr>
                <td class="text-center">{{ $environmentAccess->id }}</td>
                <td class="text-center">{{ isset($environmentAccess->user->name) ? $environmentAccess->user->name : 'N/A'}} {{ isset($environmentAccess->user->type) ? '('.$environmentAccess->user->type.')' : ''}}</td>
                <td class="text-center">{{ isset($environmentAccess->last_access) ? $environmentAccess->last_access : 'N/A' }}</td>
              <td class="text-center">
                    <a class="btn btn-outline-info btn-fw btn-rounded btn-sm"  href="#" onclick="window.open('{{route('EnvironmentAccesses.show',$environmentAccess->id)}}','Show Environment','width=1024,height=720')"><i class="mdi mdi-information-outline"></i></a>
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