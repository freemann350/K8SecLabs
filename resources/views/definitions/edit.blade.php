@extends('template.layout')

@section('main-content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Definition</h4>
      <p class="card-description">Here you can edit the definition</p>
      <form method="POST" action="{{ route('Definitions.update', $definition->definition_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Name</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $definition->name }}" placeholder="Definition name" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Path</label>
          <div class="col-sm-12">
            <input type="text" name="path" class="form-control @error('path') is-invalid @enderror" value="{{ $definition->path }}" placeholder="Definition path" required>
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
                <option value="{{ $category->category_id }}" @if($category->category_id == $definition->category_id) selected @endif>{{ $category->name }}</option>
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
                <option value="{{ $user->user_id }}" @if($user->user_id == $definition->user_id) selected @endif>{{ $user->name }}</option>
              @endforeach
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Private</label>
          <div class="col-sm-12">
            <select class="form-select" name="private">
              <option value="1" @if($definition->private) selected @endif>Yes</option>
              <option value="0" @if(!$definition->private) selected @endif>No</option>
            </select>
            @error('private')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Description</label>
          <div class="col-sm-12">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ $definition->description }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Checksum</label>
          <div class="col-sm-12">
            <input type="text" name="checksum" class="form-control @error('checksum') is-invalid @enderror" value="{{ $definition->checksum }}" placeholder="Checksum">
            @error('checksum')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 col-form-label">Tags</label>
          <div class="col-sm-12">
            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ $definition->tags }}" placeholder="Tags">
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
