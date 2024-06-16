@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit definition {{ $definition->name}} main data</h4>
      <p class="card-description">Here you can edit the main data of the Definition "{{$definition->name}}"</p>
      <form method="POST" action="{{ route('Definitions.update', $definition->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Name *</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $definition['name'] }}" placeholder="Definition name" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Category *</label>
          <div class="col-sm-12">
            <select class="form-select" name="category">
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{$category->id == $definition['category_id'] ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Privacy *</label>
          <div class="col-sm-12">
            <select class="form-select" name="private">
              <option value="0" {{ $definition['private'] == 0 ? 'selected' : '' }}>Public</option>
              <option value="1" {{ $definition['private'] == 1 ? 'selected' : '' }}>Private</option>
            </select>
            @error('private')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Description</label>
          <div class="col-sm-12">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ isset($definition['description']) ? $definition['description']  : '' }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Tags</label>
          <div class="col-sm-12">
            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ isset($definition['tags']) ? $definition['tags']  : '' }}" placeholder="Tags">
            <small class="form-text text-muted">Must be separated by commas (i.e.: owasp,sql injection)</small>
            @error('tags')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <button type="button" class="btn btn-light" onclick="window.location='{{ route('Definitions.index') }}'">Cancel</button>
      </form>
    </div>
  </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Definition {{ $definition->name}}'s' config file</h4>
      <p class="card-description">Here you can edit the config file content of the Definition "{{$definition->name}}"</p>
      <form method="POST" action="{{ route('Definitions.updateDefinition', $definition->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Definition (JSON) *</label>
          <div class="col-sm-12">
            <div class="mb-3">
              <input class="form-control form-control-sm file-upload-info" type="file" name="definition">
            </div>
            @error('definition')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <button type="button" class="btn btn-light" onclick="window.location='{{ route('Definitions.index') }}'">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
