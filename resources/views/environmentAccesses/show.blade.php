@extends('template.simpleLayout')
@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{$environmentAccess->environment->name}}</h4>
            <div class="row">
                <div class="col-md-5">
                    <address>
                        <h4 class="card-title">Main Info</h4>
                        <p class="mb-2"><b><u>Created by:</u></b> {{ $environmentAccess->environment->userDefinition->user->name }}</p>
                        <p class="mb-2"><b><u>Category:</u></b> {{ $environmentAccess->environment->userDefinition->definition->category->name }}</p>
                        <p class="mb-2"><b><u>Created at:</u></b> {{ $environmentAccess->created_at }}</p>
                    </address>
                </div>
                <div class="col-md-2">
                    <address>
                        <h4 class="card-title">Status</h4>
                        @if ($environmentAccess->environment->end_date == null)
                        <label class="badge badge-success">Running</label>
                        @else
                        <label class="badge badge-dark">Terminated</label>
                        @endif
                    </address>
                </div>
                <div class="col-md-5">
                    <address>
                        <h4 class="card-title">Tags</h4>
                        @if (isset($environmentAccess->environment->userDefinition->definition->tags))
                        @php
                            $tags = explode(',', $environmentAccess->environment->userDefinition->definition->tags);
                        @endphp
                        @foreach($tags as $tag)
                            <label class="badge badge-info" style="margin:5px 0 5px 0">#{{$tag}}</label> &nbsp;
                        @endforeach
                        @else
                            <p class="mb-2">There are no tags on this environment</p>
                        @endif
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <h4 class="card-title">Description</h4>
                {!! $environmentAccess->description !!}
            </div>
        </div>
    </div>
</div>
@endsection