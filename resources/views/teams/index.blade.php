@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h2 class="mb-0">Equipos</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamFormModal">
                        <i class="fas fa-plus"></i> Nuevo Equipo
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
                                    <th scope="col">País</th>
                                    <th scope="col">Director</th>
                                    <th scope="col">Pilotos</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teams as $team)
                                    <tr>
                                        <td>{{ $team->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($team->logo)
                                                    <img src="{{ asset('storage/' . $team->logo) }}" 
                                                         alt="{{ $team->name }}" 
                                                         class="me-2"
                                                         style="width: 30px; height: 30px; object-fit: contain;">
                                                @endif
                                                {{ $team->name }}
                                            </div>
                                        </td>
                                        <td>{{ $team->country }}</td>
                                        <td>{{ $team->director }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $team->pilots_count ?? $team->pilots->count() }} pilotos
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-warning edit-team" 
                                                    data-team="{{ $team->toJson() }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#teamFormModal">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <form action="{{ route('teams.destroy', $team->id) }}" 
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
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                No hay equipos registrados
                                            </div>
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
<div class="modal fade" id="teamFormModal" tabindex="-1" aria-labelledby="teamFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamFormModalLabel">Nuevo Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="teamForm" action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Equipo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">País</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                               id="country" name="country" required>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="director" class="form-label">Director del Equipo</label>
                        <input type="text" class="form-control @error('director') is-invalid @enderror" 
                               id="director" name="director" required>
                        @error('director')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="year_founded" class="form-label">Año de Fundación</label>
                        <input type="number" class="form-control @error('year_founded') is-invalid @enderror" 
                               id="year_founded" name="year_founded" required>
                        @error('year_founded')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="team_principal" class="form-label">Principal del Equipo</label>
                        <input type="text" class="form-control @error('team_principal') is-invalid @enderror" 
                               id="team_principal" name="team_principal" required>
                        @error('team_principal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo del Equipo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                               id="logo" name="logo" accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            if (confirm('¿Estás seguro de que deseas eliminar este equipo? Esta acción también eliminará todos los pilotos asociados.')) {
                this.submit();
            }
        });
    });

    // Manejo del formulario de edición
    document.querySelectorAll('.edit-team').forEach(button => {
        button.addEventListener('click', function() {
            const team = JSON.parse(this.dataset.team);
            const form = document.getElementById('teamForm');
            const modalTitle = document.getElementById('teamFormModalLabel');

            modalTitle.textContent = 'Editar Equipo';
            form.action = `/teams/${team.id}`;
            form.querySelector('input[name="_method"]').value = 'PUT';
            
            // Llenar el formulario
            form.querySelector('#name').value = team.name;
            form.querySelector('#country').value = team.country;
            form.querySelector('#director').value = team.director;
            form.querySelector('#year_founded').value = team.year_founded;
            form.querySelector('#team_principal').value = team.team_principal;
        });
    });

    // Resetear el formulario al abrir el modal para nuevo equipo
    const teamFormModal = document.getElementById('teamFormModal');
    teamFormModal.addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('teamForm');
        form.reset();
        form.action = "{{ route('teams.store') }}";
        form.querySelector('input[name="_method"]').value = 'POST';
        document.getElementById('teamFormModalLabel').textContent = 'Nuevo Equipo';
    });
});
</script>
@endpush
@endsection