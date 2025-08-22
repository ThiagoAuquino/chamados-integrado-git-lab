@extends('layouts.app')

@section('title', 'Importar Demandas')
@section('page-title', 'Importação de Demandas')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('demandas.index') }}">Demandas</a></li>
    <li class="breadcrumb-item active">Importar</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-import mr-1"></i> Importar Demandas</h3>
        </div>
        <div class="card-body">
            @if(session('feedback'))
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Resultado da Importação</h5>
                    <p>{{ session('feedback')['importadas'] }} demandas importadas com sucesso.</p>
                    @if(count(session('feedback')['erros']) > 0)
                        <hr>
                        <strong>Erros:</strong>
                        <ul>
                            @foreach(session('feedback')['erros'] as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <form action="{{ route('demandas.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="arquivo">Arquivo Excel (.xlsx)</label>
                    <input type="file" name="arquivo" id="arquivo" class="form-control @error('arquivo') is-invalid @enderror" required>
                    @error('arquivo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload mr-1"></i> Importar
                </button>
            </form>
        </div>
    </div>
@endsection
