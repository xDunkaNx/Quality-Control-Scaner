@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Defectos</h1>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <table class="table table-sm table-striped">
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Ubicación</th>
        <th>Lote</th>
        <th>Sev</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
    @foreach($defects as $d)
      <tr>
        <td>{{ $d->found_at?->format('Y-m-d H:i') }}</td>
        <td>{{ $d->product->name }} <small class="text-muted">({{ $d->product->barcode }})</small></td>
        <td>{{ $d->type->name }}</td>
        <td>{{ $d->location->name ?? $d->location_text ?? '—' }}</td>
        <td>{{ $d->lot ?? '—' }}</td>
        <td>{{ $d->severity }}</td>
        <td>{{ $d->status }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $defects->links() }}
</div>
@endsection
