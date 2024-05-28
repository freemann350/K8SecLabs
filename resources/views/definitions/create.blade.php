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
          <label class="col-sm-3 col-form-label">Name</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Definition name" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Path</label>
          <div class="col-sm-12">
            <input type="text" name="path" class="form-control @error('path') is-invalid @enderror" value="{{ old('path') }}" placeholder="Definition path" required>
            @error('path')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Category</label>
          <div class="col-sm-12">
            <select class="form-select" name="category_id">
              @foreach($categories as $category)
                <option value="{{ $category->category_id }}">{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">User</label>
          <div class="col-sm-12">
            <select class="form-select" name="user_id">
              @foreach($users as $user)
                <option value="{{ $user->user_id }}">{{ $user->name }}</option>
              @endforeach
            </select>
            @error('user_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Private</label>
          <div class="col-sm-12">
            <select class="form-select" name="private">
              <option value="1">Yes</option>
              <option value="0">No</option>
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
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Checksum</label>
          <div class="col-sm-12">
            <input type="text" name="checksum" class="form-control @error('checksum') is-invalid @enderror" value="{{ old('checksum') }}" placeholder="Checksum">
            @error('checksum')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Tags</label>
          <div class="col-sm-12">
            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags') }}" placeholder="Tags">
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
