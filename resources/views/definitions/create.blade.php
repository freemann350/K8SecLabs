@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add new Definition</h4>
      <p class="card-description">Here you can add a new definition</p>
      <form method="POST" action="{{ route('Definitions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Name *</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Definition name" required>
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
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

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
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Privacy *</label>
          <div class="col-sm-12">
            <select class="form-select" name="private">
              <option value="0">Public</option>
              <option value="1">Private</option>
            </select>
            @error('private')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Description</label>
          <div class="col-sm-12">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description') }}</textarea>
            <small class="form-text text-muted">Note: HTML elements are allowed</small>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Tags</label>
          <div class="col-sm-12">
            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags') }}" placeholder="Tags">
            <small class="form-text text-muted">Must be separated by commas (i.e.: tag1,tag 2,tag_3)</small>
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
@endsection
