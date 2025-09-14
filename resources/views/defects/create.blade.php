@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Registrar defecto</h1>

  @if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('defects.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
      <div class="col-md-4">
        <label>Código de barras</label>
        <input name="barcode" class="form-control" required value="{{ old('barcode') }}">
      </div>
      <div class="col-md-4">
        <label>SKU (opcional)</label>
        <input name="sku" class="form-control" value="{{ old('sku') }}">
      </div>
      <div class="col-md-4">
        <label>Nombre (si no existe)</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-4">
        <label>Tipo de defecto</label>
        <select name="defect_type_id" class="form-control" required>
          @foreach($types as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label>Ubicación</label>
        <select name="location_id" class="form-control">
          <option value="">-- Selecciona --</option>
          @foreach($locations as $loc)
            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
          @endforeach
        </select>
        <small class="text-muted">o usa texto:</small>
        <input name="location_text" class="form-control mt-1" value="{{ old('location_text') }}" placeholder="Ej. Góndola 7">
      </div>
      <div class="col-md-4">
        <label>Severidad (1-5)</label>
        <input type="number" name="severity" class="form-control" min="1" max="5" value="{{ old('severity',1) }}">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-4">
        <label>Lote</label>
        <input name="lot" class="form-control" value="{{ old('lot') }}">
      </div>
      <div class="col-md-8">
        <label>Notas</label>
        <input name="notes" class="form-control" value="{{ old('notes') }}">
      </div>
    </div>

    <div class="mt-2">
      <label>Foto (opcional)</label>
      <input type="file" name="photo" class="form-control" accept="image/*">
    </div>

    <div class="form-check mt-2">
      <input class="form-check-input" type="checkbox" value="1" id="force" name="force">
      <label class="form-check-label" for="force">Registrar aunque se detecte duplicado (10 min)</label>
    </div>

    <button class="btn btn-primary mt-3">Guardar</button>
  </form>
</div>
@endsection
