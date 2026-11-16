@include('layouts.haut')

<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-book me-2"></i>
                Mes modules
            </h2>
            <p class="text-muted mb-0">
                Liste des modules qui vous sont affectés.
            </p>
            <br>
        </div>
    </div>
    {{-- Tableau des modules --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                Modules affectés
                <span class="badge bg-primary ms-2">
                    {{ $modules->count() }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            @if($modules->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <h5 class="mt-3">Aucun module affecté</h5>
                    <p class="text-muted mb-0">
                        Vous n'avez pas encore de module affecté.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Intitulé du module</th>
                                <th>Volume horaire</th>
                                <th>Semestre</th>
                                <th>Filière</th>
                                <th>Année universitaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $index => $module)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $module->code_mdl }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $module->intitule }}
                                    </td>
                                    <td>
                                        {{ $module->volume_horaire }} h
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $module->semestre }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $module->filiere }}
                                    </td>
                                    <td>
                                        {{ $module->annee }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>
    </div>

</div>

@include('layouts.bas')

