@extends('layouts.app')

@section('content')
<!-- Lista de Pilotos -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h2 class="mb-0">Pilotos</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pilotFormModal">
                        <i class="fas fa-plus"></i> Nuevo Piloto
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Equipo</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col">País</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pilots as $pilot)
                                    <tr>
                                        <td>{{ $pilot->id }}</td>
                                        <td>{{ $pilot->name }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $pilot->team->name }}
                                            </span>
                                        </td>
                                        <td>{{ $pilot->age }}</td>
                                        <td>{{ $pilot->country }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-warning edit-pilot" 
                                                    data-pilot="{{ $pilot->toJson() }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#pilotFormModal">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <form action="{{ route('pilots.destroy', $pilot->id) }}" 
                                                  method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            No hay pilotos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Formulario -->
<div class="modal fade" id="pilotFormModal" tabindex="-1" aria-labelledby="pilotFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pilotFormModalLabel">Nuevo Piloto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pilotForm" action="{{ route('pilots.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_id" class="form-label">Equipo</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" 
                                    id="team_id" name="team_id" required>
                                <option value="">Seleccionar equipo</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Edad</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                   id="age" name="age" min="16" max="100" required>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">País</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación de eliminación
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar este piloto?')) {
                this.submit();
            }
        });
    });

    // Manejo del formulario de edición
    document.querySelectorAll('.edit-pilot').forEach(button => {
        button.addEventListener('click', function() {
            const pilot = JSON.parse(this.dataset.pilot);
            const form = document.getElementById('pilotForm');
            const modalTitle = document.getElementById('pilotFormModalLabel');

            modalTitle.textContent = 'Editar Piloto';
            form.action = `/pilots/${pilot.id}`;
            form.querySelector('input[name="_method"]').value = 'PUT';
            
            // Llenar el formulario
            form.querySelector('#name').value = pilot.name;
            form.querySelector('#team_id').value = pilot.team_id;
            form.querySelector('#age').value = pilot.age;
            form.querySelector('#country').value = pilot.country;
        });
    });

    // Resetear el formulario al abrir el modal para nuevo piloto
    const pilotFormModal = document.getElementById('pilotFormModal');
    pilotFormModal.addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('pilotForm');
        form.reset();
        form.action = "{{ route('pilots.store') }}";
        form.querySelector('input[name="_method"]').value = 'POST';
        document.getElementById('pilotFormModalLabel').textContent = 'Nuevo Piloto';
    });
});
</script>
@endpush
@endsection