# 📝 Documentação do Projeto Laravel

## 🎯 Objetivo do Sistema

Quero criar esse sistema para organizar tarefas do time ou projeto, com campos para responsáveis, prazos e status.  
Ele deve permitir:

- ✅ Alterar a prioridade das tarefas manualmente, como arrastando linhas (drag-and-drop) em uma lista.
- ⏰ Me avisar automaticamente quando as tarefas estiverem próximas de se atrasar.
- 📆 Gerar um cronograma, onde posso atribuir um responsável a cada tarefa.
- 📲 Enviar notificações por e-mail ou WhatsApp ao responsável quando a tarefa for criada ou alterada.
- 🔔 Atuar como um sistema de lembretes configurável, que envia notificações repetidas até que a tarefa seja concluída.

### 📌 O que é uma "Demanda"

No meu caso, uma “demanda” é uma tarefa atribuída a um desenvolvedor, que pode vir de:

- Um chamado do CRM feito pelo cliente
- Um job do GitLab que está sendo executado

O objetivo é que o sistema centralize a visão dessas tarefas em andamento, com:

- 👨‍💻 Quem está executando
- ⏱️ Data de entrada, previsão, entrega
- 🔁 Possibilidade do dev estar atuando em mais de uma tarefa
- 🧩 Tipo (melhoria, bug, novo recurso)
- 📊 Priorização visual (ex: Verde, Amarelo, Laranja, Vermelho)
- 📚 Histórico de troca de prioridades

Ou seja, uma demanda é uma tarefa de desenvolvimento rastreável, que conecta os dois sistemas atuais e melhora o controle interno.

## ⚙️ Versão do Laravel
`12.21.0`

## 📁 Commands
- `app/Console/Commands/MakeEntity.php`
- `app/Console/Commands/Migrations/MakeDomainMigration.php`
- `app/Console/Commands/Migrations/MigrateDomains.php`
- `app/Console/Commands/MakeRepository.php`
- `app/Console/Commands/MakeDTO.php`
- `app/Console/Commands/MakeServiceInterface.php`
- `app/Console/Commands/MakeControllerApi.php`
- `app/Console/Commands/MakeRepositoryInterface.php`
- `app/Console/Commands/MakeService.php`
- `app/Console/Commands/Summary/GenerateSummary.php`
- `app/Console/Commands/Summary/BuildSummary.php`
- `app/Console/Commands/MakeUseCase.php`
- `app/Console/Commands/MakeControllerWeb.php`

## 📁 Controllers
- `app/Http/Controllers/Controller.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/Web/AuthController.php`
- `app/Http/Controllers/Web/DashboardController.php`
- `app/Http/Controllers/Web/DemandaLogController.php`
- `app/Http/Controllers/Web/UserController.php`
- `app/Http/Controllers/Web/DemandaController.php`
- `app/Http/Controllers/Api/DashboardController.php`
- `app/Http/Controllers/Api/RoleController.php`
- `app/Http/Controllers/Api/UserController.php`
- `app/Http/Controllers/Api/PermissionController.php`
- `app/Http/Controllers/Api/DemandaController.php`
- `app/Http/Controllers/Api/DemandaImportController.php`
- `app/Http/Controllers/Auth/EmailVerificationPromptController.php`
- `app/Http/Controllers/Auth/ConfirmablePasswordController.php`
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/PasswordController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`

## 📁 DTOs
- `app/Domain/Role/DTOs/RoleDTO.php`
- `app/Domain/Role/DTOs/UpdateRoleDTO.php`
- `app/Domain/Role/DTOs/CreateRoleDTO.php`
- `app/Domain/Usuario/DTOs/CreateUserDTO.php`
- `app/Domain/Usuario/DTOs/UserDTO.php`
- `app/Domain/Usuario/DTOs/UpdateUserDTO.php`
- `app/Domain/DemandaLog/DTOs/DemandaLogDTO.php`
- `app/Domain/DemandaLog/DTOs/CreateDemandaLogDTO.php`
- `app/Domain/Demanda/DTOs/CreateDemandaDTO.php`
- `app/Domain/Demanda/DTOs/DemandaDTO.php`
- `app/Domain/Demanda/DTOs/UpdateDemandaDTO.php`
- `app/Domain/Permission/DTOs/CreatePermissionDTO.php`
- `app/Domain/Permission/DTOs/PermissionDTO.php`
- `app/Domain/Auth/DTOs/SendPasswordResetLinkDTO.php`
- `app/Domain/Auth/DTOs/RegisterUserDTO.php`
- `app/Domain/Auth/DTOs/ResetPasswordDTO.php`
- `app/Domain/Auth/DTOs/UpdatePasswordDTO.php`
- `app/Domain/Auth/DTOs/PasswordResetLinkDTO.php`
- `app/Domain/Auth/DTOs/LoginDTO.php`

## 📁 Entities
- `app/Domain/Role/Entities/Role.php`
- `app/Domain/Usuario/Entities/User.php`
- `app/Domain/Demanda/Entities/Demanda.php`
- `app/Domain/Permission/Entities/Permission.php`
- `app/Domain/Permission/Entities/DeniedPermission.php`

## 📁 Infrastructure
- `app/Infrastructure/Persistence/Role/RoleRepository.php`
- `app/Infrastructure/Persistence/Usuario/UserRepository.php`
- `app/Infrastructure/Persistence/DemandaLog/DemandaLogRepository.php`
- `app/Infrastructure/Persistence/Demanda/DemandaRepository.php`
- `app/Infrastructure/Persistence/Permission/PermissionRepository.php`
- `app/Infrastructure/Persistence/Permission/DeniedPermissionRepository.php`

## 📁 Models
- `app/Models/Users/User.php`
- `app/Models/Permission.php`
- `app/Models/Role.php`
- `app/Models/Demanda/Demanda.php`
- `app/Models/RolePermission.php`
- `app/Models/UserRole.php`
- `app/Models/UserPermission.php`
- `app/Models/DemandaLog.php`

## 📁 Outros
- `app/View/Components/GuestLayout.php`
- `app/View/Components/AppLayout.php`
- `app/Listeners/UpdateLastLoginAt.php`

## 📁 Policies
- `app/Policies/Demanda/DemandaPolicy.php`

## 📁 Providers
- `app/Providers/RepositoryServiceProvider.php`
- `app/Providers/UserServiceProvider.php`
- `app/Providers/DemandaServiceProvider.php`
- `app/Providers/AppServiceProvider.php`
- `app/Providers/AuthServiceProvider.php`
- `app/Providers/EventServiceProvider.php`

## 📁 Repositories
- `app/Domain/Role/Repositories/RoleRepositoryInterface.php`
- `app/Domain/Usuario/Repositories/UserRepositoryInterface.php`
- `app/Domain/DemandaLog/Repositories/DemandaLogRepositoryInterface.php`
- `app/Domain/Demanda/Repositories/DemandaRepositoryInterface.php`
- `app/Domain/Permission/Repositories/PermissionRepositoryInterface.php`
- `app/Domain/Permission/Repositories/DeniedPermissionRepositoryInterface.php`

## 📁 Requests
- `app/Http/Requests/ProfileUpdateRequest.php`
- `app/Http/Requests/Usuario/CreateUserRequest.php`
- `app/Http/Requests/Usuario/UpdateUserRequest.php`
- `app/Http/Requests/Demanda/UpdateDemandaRequest.php`
- `app/Http/Requests/Demanda/StoreDemandaRequest.php`
- `app/Http/Requests/Auth/LoginRequest.php`

## 📁 Services
- `app/Domain/Auth/Services/AuthService.php`
- `app/Domain/Auth/Services/AuthServiceInterface.php`
- `app/Infrastructure/Services/UserService.php`
- `app/Infrastructure/Services/NotificationService.php`
- `app/Infrastructure/Services/AuthService.php`
- `app/Infrastructure/Services/PermissionService.php`

## 📁 UseCases
- `app/Domain/Role/UseCases/ListRolesUseCase.php`
- `app/Domain/Role/UseCases/CreateRoleUseCase.php`
- `app/Domain/Role/UseCases/FindRoleByNameUseCase.php`
- `app/Domain/Role/UseCases/FindRoleByIdUseCase.php`
- `app/Domain/Role/UseCases/UpdateRoleUseCase.php`
- `app/Domain/Role/UseCases/DeleteRoleUseCase.php`
- `app/Domain/Usuario/UseCases/CreateUserUseCase.php`
- `app/Domain/Usuario/UseCases/ShowUserUseCase.php`
- `app/Domain/Usuario/UseCases/BulkUserActionUseCase.php`
- `app/Domain/Usuario/UseCases/ListUserUseCase.php`
- `app/Domain/Usuario/UseCases/DeleteUserUseCase.php`
- `app/Domain/Usuario/UseCases/UpdateUserUseCase.php`
- `app/Domain/DemandaLog/UseCases/CreateLogUseCase.php`
- `app/Domain/DemandaLog/UseCases/GetDemandaHistoryUseCase.php`
- `app/Domain/Demanda/UseCases/CreateDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/GetDemandaHistoryUseCase.php`
- `app/Domain/Demanda/UseCases/GetAllDemandasUseCase.php`
- `app/Domain/Demanda/UseCases/DeleteDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/GetDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/ChangeStatusDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/BulkUpdateDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/AddCommentUseCase.php`
- `app/Domain/Demanda/UseCases/ImportDemandasUseCase.php`
- `app/Domain/Demanda/UseCases/UpdateDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/UpdatePriorityUseCase.php`
- `app/Domain/Demanda/UseCases/ListDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/ApproveDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/ExportDemandaUseCase.php`
- `app/Domain/Demanda/UseCases/ShowDemandaUseCase.php`
- `app/Domain/Permission/UseCases/RevokeDeniedPermissionUseCase.php`
- `app/Domain/Permission/UseCases/UpdatePermissionUseCase.php`
- `app/Domain/Permission/UseCases/AssignDeniedPermissionUseCase.php`
- `app/Domain/Permission/UseCases/CreatePermissionUseCase.php`
- `app/Domain/Permission/UseCases/GetPermissionByIdUseCase.php`
- `app/Domain/Permission/UseCases/CheckPermissionUseCase.php`
- `app/Domain/Permission/UseCases/DeletePermissionUseCase.php`
- `app/Domain/Permission/UseCases/ListPermissionsUseCase.php`
- `app/Domain/Auth/UseCases/ResetPasswordUseCase.php`
- `app/Domain/Auth/UseCases/SendEmailVerificationUseCase.php`
- `app/Domain/Auth/UseCases/RegisterUserUseCase.php`
- `app/Domain/Auth/UseCases/UpdatePasswordUseCase.php`
- `app/Domain/Auth/UseCases/VerifyEmailUseCase.php`
- `app/Domain/Auth/UseCases/SendPasswordResetLinkUseCase.php`
- `app/Domain/Auth/UseCases/LoginUseCase.php`

## 📁 Migrations
- `database/migrations/0001_01_01_000001_create_cache_table.php`
- `database/migrations/0001_01_01_000002_create_jobs_table.php`
- `database/migrations/2025_08_26_014047_create_denied_permissions_table.php`

## 📁 Arquivos de Rotas Customizadas
_Nenhum encontrado_

## 📦 Dependências do Composer:
- `php`: `^8.2`
- `laravel/framework`: `^12.0`
- `laravel/tinker`: `^2.10.1`
- `maatwebsite/excel`: `^1.1`

## 🛣️ Lista de Rotas Registradas
```
GET      up                                                 Closure                       
GET      /                                                  Closure                       
GET      register                                           App\Http\Controllers\Auth\RegisteredUserController@create
POST     register                                           App\Http\Controllers\Auth\RegisteredUserController@store
GET      login                                              App\Http\Controllers\Auth\AuthenticatedSessionController@create
POST     login                                              App\Http\Controllers\Auth\AuthenticatedSessionController@store
GET      reset-password/{token}                             App\Http\Controllers\Auth\NewPasswordController@create
POST     reset-password                                     App\Http\Controllers\Auth\NewPasswordController@store
GET      forgot-password                                    App\Http\Controllers\Auth\PasswordResetLinkController@create
POST     forgot-password                                    App\Http\Controllers\Auth\PasswordResetLinkController@store
GET      verify-email                                       App\Http\Controllers\Auth\EmailVerificationPromptController
GET      verify-email/{id}/{hash}                           App\Http\Controllers\Auth\VerifyEmailController
POST     email/verification-notification                    App\Http\Controllers\Auth\EmailVerificationNotificationController@store
GET      confirm-password                                   App\Http\Controllers\Auth\ConfirmablePasswordController@show
POST     confirm-password                                   App\Http\Controllers\Auth\ConfirmablePasswordController@store
PUT      password                                           App\Http\Controllers\Auth\PasswordController@update
POST     logout                                             App\Http\Controllers\Auth\AuthenticatedSessionController@destroy
GET      profile                                            App\Http\Controllers\ProfileController@edit
PUT      profile                                            App\Http\Controllers\Web\AuthController@updateProfile
PATCH    profile                                            App\Http\Controllers\ProfileController@update
DELETE   profile                                            App\Http\Controllers\ProfileController@destroy
GET      dashboard                                          App\Http\Controllers\Web\DashboardController@index
GET      demandas                                           App\Http\Controllers\Web\DemandaController@index
GET      demandas/create                                    App\Http\Controllers\Web\DemandaController@create
POST     demandas                                           App\Http\Controllers\Web\DemandaController@store
GET      demandas/{}                                        App\Http\Controllers\Web\DemandaController@show
GET      demandas/{}/edit                                   App\Http\Controllers\Web\DemandaController@edit
PUT      demandas/{}                                        App\Http\Controllers\Web\DemandaController@update
DELETE   demandas/{}                                        App\Http\Controllers\Web\DemandaController@destroy
GET      demandas/pending                                   App\Http\Controllers\Web\DemandaController@pending
GET      demandas/my-tasks                                  App\Http\Controllers\Web\DemandaController@myTasks
GET      demandas/overdue                                   App\Http\Controllers\Web\DemandaController@overdue
GET      demandas/filter/{status}                           App\Http\Controllers\Web\DemandaController@filterByStatus
GET      demandas/search                                    App\Http\Controllers\Web\DemandaController@search
GET      demandas/export                                    App\Http\Controllers\Web\DemandaController@export
GET      demandas/api/{demanda}/history                     App\Http\Controllers\Web\DemandaController@getHistory
POST     demandas/api/{demanda}/comment                     App\Http\Controllers\Web\DemandaController@addComment
POST     demandas/api/update-priority                       App\Http\Controllers\Web\DemandaController@updatePriority
GET      demandas/importar                                  App\Http\Controllers\Web\DemandaController@importForm
POST     demandas/importar                                  App\Http\Controllers\Web\DemandaController@importProcess
GET      demandas/demandas/{id}/logs                        App\Http\Controllers\Web\DemandaLogController@show
GET      demandas/kanban                                    App\Http\Controllers\Web\DemandaController@kanban
GET      users                                              App\Http\Controllers\Web\UserController@index
GET      users/create                                       App\Http\Controllers\Web\UserController@create
POST     users                                              App\Http\Controllers\Web\UserController@store
GET      users/{id}                                         App\Http\Controllers\Web\UserController@show
GET      users/{id}/edit                                    App\Http\Controllers\Web\UserController@edit
PUT      users/{id}                                         App\Http\Controllers\Web\UserController@update
DELETE   users/{id}                                         App\Http\Controllers\Web\UserController@destroy
GET      users/{id}/permissions                             App\Http\Controllers\Web\UserController@permissions
POST     users/{id}/permissions                             App\Http\Controllers\Web\UserController@updatePermissions
POST     users/bulk-action                                  App\Http\Controllers\Web\UserController@bulkAction
GET      api/demandas                                       App\Http\Controllers\Api\DemandaController@index
GET      api/demandas/{id}                                  App\Http\Controllers\Api\DemandaController@show
POST     api/demandas                                       App\Http\Controllers\Api\DemandaController@store
PUT      api/demandas/{id}                                  App\Http\Controllers\Api\DemandaController@update
DELETE   api/demandas/{id}                                  App\Http\Controllers\Api\DemandaController@destroy
POST     api/demandas/{id}/approve                          App\Http\Controllers\Api\DemandaController@approve
POST     api/demandas/{id}/change-status                    App\Http\Controllers\Api\DemandaController@changeStatus
GET      api/demandas/stats                                 App\Http\Controllers\Api\DemandaController@stats
GET      api/demandas/overview                              App\Http\Controllers\Api\DemandaController@overview
GET      api/demandas/{id}/history                          App\Http\Controllers\Api\DemandaController@history
GET      api/demandas/{id}/timeline                         App\Http\Controllers\Api\DemandaController@timeline
GET      api/demandas/{id}/historico                        App\Http\Controllers\Api\DemandaController@historico
POST     api/demandas/bulk-update                           App\Http\Controllers\Api\DemandaController@bulkUpdate
POST     api/demandas/bulk-assign                           App\Http\Controllers\Api\DemandaController@bulkAssign
POST     api/demandas/bulk-change-status                    App\Http\Controllers\Api\DemandaController@bulkChangeStatus
GET      api/demandas/pending                               App\Http\Controllers\Api\DemandaController@pending
GET      api/demandas/overdue                               App\Http\Controllers\Api\DemandaController@overdue
GET      api/demandas/by-user/{userId}                      App\Http\Controllers\Api\DemandaController@byUser
GET      api/demandas/by-status/{status}                    App\Http\Controllers\Api\DemandaController@byStatus
GET      api/demandas/by-priority/{priority}                App\Http\Controllers\Api\DemandaController@byPriority
POST     api/demandas/{id}/comments                         App\Http\Controllers\Api\DemandaController@addComment
GET      api/demandas/{id}/comments                         App\Http\Controllers\Api\DemandaController@getComments
POST     api/demandas/{id}/attachments                      App\Http\Controllers\Api\DemandaController@addAttachment
GET      api/demandas/{id}/attachments                      App\Http\Controllers\Api\DemandaController@getAttachments
POST     api/demandas/update-priority                       App\Http\Controllers\Api\DemandaController@updatePriority
POST     api/demandas/reorder                               App\Http\Controllers\Api\DemandaController@reorder
POST     api/demandas/{id}/reminder                         App\Http\Controllers\Api\DemandaController@setReminder
DELETE   api/demandas/{id}/reminder                         App\Http\Controllers\Api\DemandaController@removeReminder
GET      api/demandas/export                                App\Http\Controllers\Api\DemandaController@export
POST     api/demandas/export-filtered                       App\Http\Controllers\Api\DemandaController@exportFiltered
POST     api/demandas/validate-assignment                   App\Http\Controllers\Api\DemandaController@validateAssignment
POST     api/demandas/validate-status-change                App\Http\Controllers\Api\DemandaController@validateStatusChange
POST     api/demandas/import                                App\Http\Controllers\Api\DemandaImportController
POST     api/login                                          App\Http\Controllers\Auth\AuthenticatedSessionController@store
POST     api/logout                                         App\Http\Controllers\Auth\AuthenticatedSessionController@destroy
GET      api/users                                          App\Http\Controllers\Api\UserController@index
GET      api/users/{id}                                     App\Http\Controllers\Api\UserController@show
POST     api/users                                          App\Http\Controllers\Api\UserController@store
PUT      api/users/{id}                                     App\Http\Controllers\Api\UserController@update
DELETE   api/users/{id}                                     App\Http\Controllers\Api\UserController@destroy
GET      api/users/{id}/permissions                         App\Http\Controllers\Api\UserController@permissions
GET      api/users/{id}/roles                               App\Http\Controllers\Api\UserController@roles
POST     api/users/{id}/assign-role                         App\Http\Controllers\Api\UserController@assignRole
DELETE   api/users/{id}/remove-role/{roleId}                App\Http\Controllers\Api\UserController@removeRole
POST     api/users/{id}/assign-permission                   App\Http\Controllers\Api\UserController@assignPermission
DELETE   api/users/{id}/remove-permission/{permissionId}    App\Http\Controllers\Api\UserController@removePermission
GET      api/user/profile                                   App\Http\Controllers\Api\UserController@profile
PUT      api/user/profile                                   App\Http\Controllers\Api\UserController@updateProfile
GET      storage/{path}                                     Closure                       
```

## 📌 Pendências / Backlog
- [ ] Adicionar funcionalidades pendentes aqui

---
Gerado automaticamente por `php artisan generate:summary` em 10/09/2025 13:03:14
