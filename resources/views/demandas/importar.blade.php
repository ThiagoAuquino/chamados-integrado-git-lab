@extends('layouts.app')

@section('title', 'Importar Demandas')
@section('page-title', 'Importar Demandas')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('demandas.pending') }}">Demandas</a></li>
    <li class="breadcrumb-item active">Importar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-upload mr-2"></i>Importar Planilha de Demandas</h3>
    </div>
    <form action="{{ route('demandas.import.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="file">Arquivo Excel (.xlsx)</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if (session('import_result'))
                <hr>
                <h5>Resumo da Importação:</h5>
                <ul class="list-group mt-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span><strong>Importadas com Sucesso:</strong></span>
                        <span class="badge bg-success">{{ session('import_result')['importadas'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><strong>Com Erros:</strong></span>
                        <span class="badge bg-danger">{{ count(session('import_result')['erros']) }}</span>
                    </li>
                </ul>

                @if (count(session('import_result')['erros']) > 0)
                    <div class="mt-4">
                        <h6>Detalhes dos Erros:</h6>
                        <ul class="list-group">
                            @foreach (session('import_result')['erros'] as $erro)
                                <li class="list-group-item text-danger">{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload mr-1"></i> Importar
            </button>
        </div>
    </form>
</div>
@endsection
