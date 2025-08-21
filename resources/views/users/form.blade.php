@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuário' : 'Novo Usuário')
@section('page-title', isset($user) ? 'Editar Usuário' : 'Novo Usuário')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">{{ isset($user) ? 'Editar' : 'Novo' }}</li>
@endsection

@section('content')
    <form method="POST" 
          action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" 
          enctype="multipart/form-data" 
          id="user-form">
        @csrf
        @isset($user)
            @method('PUT')
        @endisset

        <div class="row">
            <div class="col-md-8">
                @include('users.partials.basic-info')
                @include('users.partials.roles-permissions')
            </div>

            <div class="col-md-4">
                @include('users.partials.settings')
                @include('users.partials.avatar')
                @isset($user)
                    @include('users.partials.system-info')
                @endisset
                @include('users.partials.actions')
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        .avatar-preview img {
            transition: all 0.3s ease;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .avatar-preview:hover img {
            transform: scale(1.05);
        }

        .custom-file-label::after {
            content: "Procurar";
        }

        .card-outline {
            border-width: 1px;
        }

        .icheck-primary label {
            font-weight: normal;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.mask@1.14.16/dist/jquery.mask.min.js"></script>
    @include('users.scripts.users-scripts')
@endpush
