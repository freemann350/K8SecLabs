@extends('template.layout')
@section('main-content')
@foreach ($environments as $key => $environment)
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><strong>Environment #{{$key+1}}</strong></h4>
                <div class="row">
                    <div class="col-md-6">
                        <address>
                            <p class="fw-bold">
                                Assigned User
                            </p>
                            <p class="mb-2">
                                {{$environment->user_id == null ? "N/A" : $environment->user->name}}
                            </p>
                        </address>
                    </div>
                    <div class="col-md-6">
                        <address>
                            <p class="fw-bold">
                                Status
                            </p>
                            <label class="badge badge-success"><b>Ready</b></label>
                        </address>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-info btn-lg btn-block" onclick="toggleDescription({{$key}})">
                        Show/Hide Description
                    </button>
                    <address id="description-{{$key}}" style="display: none;">
                        <p class="fw-bold">
                            Description
                        </p>
                        <p class="mb-2 description">
                            {!! $environment->description !!}
                        </p>
                    </address>
                </div>
            </div>
        </div>
    </div>
@endforeach
<script>
    function toggleDescription(key) {
        var description = document.getElementById('description-' + key);
        if (description.style.display === "none") {
            description.style.display = "block";
        } else {
            description.style.display = "none";
        }
    }
</script>
@endsection