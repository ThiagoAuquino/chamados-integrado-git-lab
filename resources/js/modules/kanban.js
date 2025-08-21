// Funcionalidades do Kanban Drag-and-Drop
document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.kanban-column .kanban-cards');
    
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            
            onEnd: function(evt) {
                const itemId = evt.item.dataset.id;
                const newStatus = evt.to.dataset.status;
                
                // Chamar API para atualizar status
                updateDemandaStatus(itemId, newStatus);
            }
        });
    });
});

function updateDemandaStatus(demandaId, newStatus) {
    fetch(`/api/demandas/${demandaId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Status atualizado com sucesso!');
        } else {
            toastr.error('Erro ao atualizar status');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        toastr.error('Erro de conexão');
    });
}


class KanbanSystem {
    constructor() {
        this.columns = [];
        this.sortableInstances = [];
        this.apiEndpoint = '/api/demandas';
        this.init();
    }

    init() {
        this.setupColumns();
        this.setupEventListeners();
        this.setupAutoRefresh();
        this.setupFilters();
        this.setupKeyboardShortcuts();
    }

    setupColumns() {
        const columnElements = document.querySelectorAll('.kanban-cards');
        
        columnElements.forEach(column => {
            const sortableInstance = new Sortable(column, {
                group: 'kanban-board',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                forceFallback: true,
                fallbackClass: 'sortable-fallback',
                
                // Configurações de arrastar
                onStart: (evt) => {
                    this.onDragStart(evt);
                },
                
                onMove: (evt) => {
                    return this.onDragMove(evt);
                },
                
                onEnd: (evt) => {
                    this.onDragEnd(evt);
                }
            });
            
            this.sortableInstances.push(sortableInstance);
            this.columns.push({
                element: column,
                status: column.dataset.status,
                sortable: sortableInstance
            });
        });
    }

    onDragStart(evt) {
        // Adicionar classe visual ao card sendo arrastado
        evt.item.classList.add('dragging');
        
        // Destacar zonas de drop válidas
        this.highlightDropZones(evt.item);
        
        // Armazenar dados iniciais
        evt.item.setAttribute('data-original-index', evt.oldIndex);
        evt.item.setAttribute('data-original-status', evt.from.dataset.status);
        
        // Feedback visual
        this.showDragFeedback(true);
    }

    onDragMove(evt) {
        const card = evt.dragged;
        const targetColumn = evt.to;
        
        // Verificar permissões
        if (!this.canMoveToStatus(card, targetColumn.dataset.status)) {
            return false;
        }
        
        // Verificar regras de negócio
        return this.validateMove(card, targetColumn.dataset.status);
    }

    onDragEnd(evt) {
        const card = evt.item;
        const newStatus = evt.to.dataset.status;
        const oldStatus = card.getAttribute('data-original-status');
        const demandaId = card.dataset.id;
        
        // Remover classes visuais
        card.classList.remove('dragging');
        this.showDragFeedback(false);
        this.clearDropZoneHighlights();
        
        // Se não houve mudança de status, não fazer nada
        if (newStatus === oldStatus) {
            return;
        }
        
        // Mostrar loader no card
        this.showCardLoader(card, true);
        
        // Atualizar via API
        this.updateDemandaStatus(demandaId, newStatus, oldStatus)
            .then(response => {
                if (response.success) {
                    this.onMoveSuccess(card, newStatus, response.data);
                } else {
                    this.onMoveError(card, oldStatus, response.message);
                }
            })
            .catch(error => {
                this.onMoveError(card, oldStatus, error.message);
            })
            .finally(() => {
                this.showCardLoader(card, false);
            });
    }

    async updateDemandaStatus(demandaId, newStatus, oldStatus) {
        try {
            const response = await fetch(`${this.apiEndpoint}/${demandaId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    status: newStatus,
                    previous_status: oldStatus,
                    moved_at: new Date().toISOString()
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Erro ao atualizar status');
            }
            
            return data;
        } catch (error) {
            console.error('Erro na API:', error);
            throw error;
        }
    }

    onMoveSuccess(card, newStatus, data) {
        // Atualizar contadores
        this.updateColumnCounters();
        
        // Atualizar dados do card se necessário
        if (data.demanda) {
            this.updateCardData(card, data.demanda);
        }
        
        // Notificação de sucesso
        toastr.success(`Demanda movida para ${this.getStatusLabel(newStatus)}!`);
        
        // Registrar atividade
        this.logActivity('move', card.dataset.id, newStatus);
        
        // Atualizar estatísticas do dashboard
        this.refreshDashboardStats();
    }

    onMoveError(card, originalStatus, errorMessage) {
        // Reverter para posição original
        const originalColumn = document.querySelector(`[data-status="${originalStatus}"]`);
        const originalIndex = parseInt(card.getAttribute('data-original-index'));
        
        if (originalColumn) {
            const cards = Array.from(originalColumn.children);
            if (originalIndex < cards.length) {
                originalColumn.insertBefore(card, cards[originalIndex]);
            } else {
                originalColumn.appendChild(card);
            }
        }
        
        // Notificação de erro
        toastr.error(errorMessage || 'Erro ao mover demanda');
        
        // Limpar atributos temporários
        card.removeAttribute('data-original-index');
        card.removeAttribute('data-original-status');
    }

    canMoveToStatus(card, targetStatus) {
        const userPermissions = window.userPermissions || [];
        const demandaData = this.getCardData(card);
        
        // Verificar permissões básicas
        if (!userPermissions.includes('move-demandas')) {
            return false;
        }
        
        // Verificar se pode mover para status específico
        const statusPermissions = {
            'backlog': ['create-demanda', 'edit-demanda'],
            'analise': ['analyze-demanda'],
            'desenvolvimento': ['develop-demanda'],
            'teste': ['test-demanda'],
            'concluido': ['complete-demanda']
        };
        
        const requiredPermissions = statusPermissions[targetStatus] || [];
        return requiredPermissions.some(permission => userPermissions.includes(permission));
    }

    validateMove(card, targetStatus) {
        const demandaData = this.getCardData(card);
        const currentStatus = card.closest('[data-status]').dataset.status;
        
        // Regras de fluxo
        const flowRules = {
            'backlog': ['analise'],
            'analise': ['backlog', 'desenvolvimento'],
            'desenvolvimento': ['analise', 'teste'],
            'teste': ['desenvolvimento', 'concluido'],
            'concluido': ['teste']
        };
        
        const allowedMoves = flowRules[currentStatus] || [];
        
        if (!allowedMoves.includes(targetStatus)) {
            toastr.warning('Movimento não permitido pelo fluxo do processo');
            return false;
        }
        
        // Validações específicas por status
        switch (targetStatus) {
            case 'desenvolvimento':
                if (!demandaData.responsavel_id) {
                    toastr.warning('Demanda deve ter um responsável para ir para desenvolvimento');
                    return false;
                }
                break;
                
            case 'teste':
                if (!demandaData.estimativa_horas) {
                    toastr.warning('Demanda deve ter estimativa de horas para ir para teste');
                    return false;
                }
                break;
                
            case 'concluido':
                if (demandaData.subtarefas_pendentes > 0) {
                    toastr.warning('Todas as subtarefas devem estar concluídas');
                    return false;
                }
                break;
        }
        
        return true;
    }

    highlightDropZones(draggedCard) {
        const currentStatus = draggedCard.closest('[data-status]').dataset.status;
        
        this.columns.forEach(column => {
            const canDrop = this.canMoveToStatus(draggedCard, column.status) && 
                           this.validateMove(draggedCard, column.status);
            
            if (canDrop) {
                column.element.parentElement.classList.add('drop-zone-valid');
            } else {
                column.element.parentElement.classList.add('drop-zone-invalid');
            }
        });
    }

    clearDropZoneHighlights() {
        document.querySelectorAll('.drop-zone-valid, .drop-zone-invalid').forEach(el => {
            el.classList.remove('drop-zone-valid', 'drop-zone-invalid');
        });
    }

    showDragFeedback(show) {
        const body = document.body;
        if (show) {
            body.classList.add('dragging-active');
        } else {
            body.classList.remove('dragging-active');
        }
    }

    showCardLoader(card, show) {
        if (show) {
            card.style.opacity = '0.7';
            card.style.pointerEvents = 'none';
            
            const loader = document.createElement('div');
            loader.className = 'card-loader';
            loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            card.appendChild(loader);
        } else {
            card.style.opacity = '';
            card.style.pointerEvents = '';
            
            const loader = card.querySelector('.card-loader');
            if (loader) {
                loader.remove();
            }
        }
    }

    updateColumnCounters() {
        this.columns.forEach(column => {
            const count = column.element.children.length;
            const counterElement = column.element.parentElement.querySelector('.badge');
            if (counterElement) {
                counterElement.textContent = count;
            }
        });
    }

    getCardData(card) {
        return {
            id: card.dataset.id,
            responsavel_id: card.dataset.responsavel,
            estimativa_horas: card.dataset.estimativa,
            subtarefas_pendentes: parseInt(card.dataset.subtarefasPendentes || 0)
        };
    }

    updateCardData(card, newData) {
        // Atualizar datasets
        Object.keys(newData).forEach(key => {
            if (card.dataset[key] !== undefined) {
                card.dataset[key] = newData[key];
            }
        });
        
        // Atualizar elementos visuais se necessário
        const responsavelImg = card.querySelector('.user-info img');
        if (responsavelImg && newData.responsavel) {
            responsavelImg.src = newData.responsavel.avatar || '/images/user-default.png';
            responsavelImg.title = newData.responsavel.name;
        }
    }

    getStatusLabel(status) {
        const labels = {
            'backlog': 'Backlog',
            'analise': 'Em Análise',
            'desenvolvimento': 'Desenvolvimento',
            'teste': 'Em Teste',
            'concluido': 'Concluído'
        };
        return labels[status] || status;
    }

    setupEventListeners() {
        // Botões de ação rápida nos cards
        document.addEventListener('click', (e) => {
            if (e.target.matches('.quick-edit-btn')) {
                const card = e.target.closest('.kanban-card');
                this.openQuickEdit(card.dataset.id);
            }
            
            if (e.target.matches('.quick-comment-btn')) {
                const card = e.target.closest('.kanban-card');
                this.openQuickComment(card.dataset.id);
            }
            
            if (e.target.matches('.card-expand-btn')) {
                const card = e.target.closest('.kanban-card');
                this.expandCard(card);
            }
        });
        
        // Filtro em tempo real
        document.getElementById('kanban-search')?.addEventListener('input', (e) => {
            this.filterCards(e.target.value);
        });
        
        // Ações em massa
        document.getElementById('bulk-move-btn')?.addEventListener('click', () => {
            this.showBulkMoveModal();
        });
    }

    setupAutoRefresh() {
        // Atualizar contadores a cada 30 segundos
        setInterval(() => {
            this.refreshCounters();
        }, 30000);
        
        // Verificar novas demandas a cada 60 segundos
        setInterval(() => {
            this.checkForNewDemandas();
        }, 60000);
    }

    setupFilters() {
        const filterButtons = document.querySelectorAll('.kanban-filter');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                const value = button.dataset.value;
                
                this.applyFilter(filter, value);
                
                // Atualizar UI dos filtros
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + F para buscar
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('kanban-search')?.focus();
            }
            
            // Esc para limpar filtros
            if (e.key === 'Escape') {
                this.clearFilters();
            }
            
            // Setas para navegar entre cards selecionados
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                this.navigateCards(e.key === 'ArrowRight' ? 'next' : 'prev');
            }
        });
    }

    filterCards(searchTerm) {
        const cards = document.querySelectorAll('.kanban-card');
        
        cards.forEach(card => {
            const title = card.querySelector('h6')?.textContent.toLowerCase() || '';
            const description = card.querySelector('p')?.textContent.toLowerCase() || '';
            const responsavel = card.querySelector('.user-info small')?.textContent.toLowerCase() || '';
            
            const matches = title.includes(searchTerm.toLowerCase()) ||
                          description.includes(searchTerm.toLowerCase()) ||
                          responsavel.includes(searchTerm.toLowerCase());
            
            card.style.display = matches ? 'block' : 'none';
        });
        
        this.updateColumnCounters();
    }

    applyFilter(filter, value) {
        const cards = document.querySelectorAll('.kanban-card');
        
        cards.forEach(card => {
            let show = true;
            
            switch (filter) {
                case 'priority':
                    show = card.classList.contains(`priority-${value}`) || value === 'all';
                    break;
                case 'responsavel':
                    show = card.dataset.responsavel === value || value === 'all';
                    break;
                case 'type':
                    show = card.dataset.type === value || value === 'all';
                    break;
            }
            
            card.style.display = show ? 'block' : 'none';
        });
        
        this.updateColumnCounters();
    }

    async refreshCounters() {
        try {
            const response = await fetch('/api/kanban/stats');
            const data = await response.json();
            
            if (data.success) {
                Object.keys(data.counters).forEach(status => {
                    const counter = document.querySelector(`[data-status="${status}"] + .kanban-header .badge`);
                    if (counter) {
                        counter.textContent = data.counters[status];
                    }
                });
            }
        } catch (error) {
            console.error('Erro ao atualizar contadores:', error);
        }
    }

    async refreshDashboardStats() {
        try {
            const response = await fetch('/api/dashboard/stats');
            const data = await response.json();
            
            if (data.success) {
                // Atualizar info-boxes do dashboard
                this.updateInfoBox('total_demandas', data.stats.total_demandas);
                this.updateInfoBox('em_andamento', data.stats.em_andamento);
                this.updateInfoBox('concluidas', data.stats.concluidas);
                this.updateInfoBox('alta_prioridade', data.stats.alta_prioridade);
            }
        } catch (error) {
            console.error('Erro ao atualizar estatísticas:', error);
        }
    }

    updateInfoBox(key, value) {
        const infoBox = document.querySelector(`.info-box:has([data-stat="${key}"])`);
        if (infoBox) {
            const numberElement = infoBox.querySelector('.info-box-number');
            if (numberElement) {
                // Animação de contagem
                this.animateCounter(numberElement, parseInt(numberElement.textContent), value);
            }
        }
    }

    animateCounter(element, start, end) {
        const duration = 1000;
        const increment = (end - start) / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    async checkForNewDemandas() {
        try {
            const response = await fetch('/api/demandas/recent');
            const data = await response.json();
            
            if (data.new_demandas && data.new_demandas.length > 0) {
                data.new_demandas.forEach(demanda => {
                    this.addNewDemandaCard(demanda);
                    this.showNewDemandaNotification(demanda);
                });
            }
        } catch (error) {
            console.error('Erro ao verificar novas demandas:', error);
        }
    }

    addNewDemandaCard(demanda) {
        const targetColumn = document.querySelector(`[data-status="${demanda.status}"]`);
        if (targetColumn) {
            const cardHtml = this.generateCardHtml(demanda);
            const cardElement = document.createElement('div');
            cardElement.innerHTML = cardHtml;
            
            // Adicionar com animação
            cardElement.style.opacity = '0';
            cardElement.style.transform = 'translateY(-20px)';
            targetColumn.insertBefore(cardElement.firstElementChild, targetColumn.firstElementChild);
            
            // Animar entrada
            setTimeout(() => {
                cardElement.firstElementChild.style.transition = 'all 0.3s ease';
                cardElement.firstElementChild.style.opacity = '1';
                cardElement.firstElementChild.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    generateCardHtml(demanda) {
        return `
            <div class="kanban-card priority-${demanda.prioridade.toLowerCase()}" data-id="${demanda.id}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge badge-secondary">#${demanda.id}</span>
                    <span class="badge badge-info">NOVO</span>
                </div>
                <h6 class="mb-2">
                    <a href="/demandas/${demanda.id}" class="text-dark text-decoration-none">
                        ${demanda.titulo}
                    </a>
                </h6>
                <div class="mb-2">
                    <span class="badge badge-${this.getPriorityColor(demanda.prioridade)}">
                        <i class="fas fa-flag mr-1"></i>
                        ${demanda.prioridade}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="user-info">
                        ${demanda.responsavel ? `
                            <img src="${demanda.responsavel.avatar || '/images/user-default.png'}" 
                                 class="img-circle elevation-2" width="24" height="24">
                            <small class="ml-1">${demanda.responsavel.name}</small>
                        ` : `
                            <small class="text-muted">
                                <i class="fas fa-user-slash mr-1"></i>
                                Não atribuída
                            </small>
                        `}
                    </div>
                    <small class="text-muted">
                        ${new Date(demanda.created_at).toLocaleDateString('pt-BR')}
                    </small>
                </div>
            </div>
        `;
    }

    getPriorityColor(priority) {
        const colors = {
            'alta': 'danger',
            'media': 'warning',
            'baixa': 'success'
        };
        return colors[priority.toLowerCase()] || 'secondary';
    }

    showNewDemandaNotification(demanda) {
        toastr.info(
            `Nova demanda: ${demanda.titulo}`,
            'Demanda Criada',
            {
                timeOut: 5000,
                onclick: function() {
                    window.location.href = `/demandas/${demanda.id}`;
                }
            }
        );
    }

    openQuickEdit(demandaId) {
        // Criar modal de edição rápida
        const modal = this.createQuickEditModal(demandaId);
        document.body.appendChild(modal);
        $(modal).modal('show');
        
        // Carregar dados da demanda
        this.loadDemandaForQuickEdit(demandaId);
    }

    createQuickEditModal(demandaId) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edição Rápida - Demanda #${demandaId}</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="quick-edit-content">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>Carregando...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="kanbanSystem.saveQuickEdit(${demandaId})">
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        `;
        return modal;
    }

    async loadDemandaForQuickEdit(demandaId) {
        try {
            const response = await fetch(`/api/demandas/${demandaId}/quick-edit`);
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('quick-edit-content').innerHTML = data.html;
                this.initializeQuickEditForm();
            }
        } catch (error) {
            document.getElementById('quick-edit-content').innerHTML = `
                <div class="alert alert-danger">
                    Erro ao carregar dados da demanda
                </div>
            `;
        }
    }

    initializeQuickEditForm() {
        // Inicializar Select2 e outros componentes
        $('.select2').select2({
            width: '100%'
        });
        
        // Inicializar editor de texto se necessário
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('descricao');
        }
    }

    async saveQuickEdit(demandaId) {
        const form = document.getElementById('quick-edit-form');
        const formData = new FormData(form);
        
        try {
            const response = await fetch(`/api/demandas/${demandaId}`, {
                method: 'PATCH',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                toastr.success('Demanda atualizada com sucesso!');
                $('.modal').modal('hide');
                
                // Atualizar card no kanban
                this.updateCardInBoard(demandaId, data.demanda);
            } else {
                toastr.error(data.message || 'Erro ao atualizar demanda');
            }
        } catch (error) {
            toastr.error('Erro de conexão');
        }
    }

    updateCardInBoard(demandaId, demandaData) {
        const card = document.querySelector(`[data-id="${demandaId}"]`);
        if (card) {
            // Atualizar título
            const titleElement = card.querySelector('h6 a');
            if (titleElement) {
                titleElement.textContent = demandaData.titulo;
            }
            
            // Atualizar prioridade
            card.className = card.className.replace(/priority-\w+/, `priority-${demandaData.prioridade.toLowerCase()}`);
            
            // Atualizar badge de prioridade
            const priorityBadge = card.querySelector('.badge');
            if (priorityBadge && priorityBadge.textContent.includes('flag')) {
                priorityBadge.className = `badge badge-${this.getPriorityColor(demandaData.prioridade)}`;
                priorityBadge.innerHTML = `<i class="fas fa-flag mr-1"></i>${demandaData.prioridade}`;
            }
            
            // Adicionar indicador de atualização
            card.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                card.style.animation = '';
            }, 500);
        }
    }

    openQuickComment(demandaId) {
        const comment = prompt('Digite seu comentário:');
        if (comment && comment.trim()) {
            this.addComment(demandaId, comment.trim());
        }
    }

    async addComment(demandaId, comment) {
        try {
            const response = await fetch(`/api/demandas/${demandaId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ comment: comment })
            });
            
            const data = await response.json();
            
            if (data.success) {
                toastr.success('Comentário adicionado!');
                
                // Atualizar contador de comentários no card
                const card = document.querySelector(`[data-id="${demandaId}"]`);
                const commentIcon = card?.querySelector('.fas.fa-comment');
                if (commentIcon) {
                    const countElement = commentIcon.nextElementSibling;
                    if (countElement) {
                        countElement.textContent = parseInt(countElement.textContent) + 1;
                    }
                }
            }
        } catch (error) {
            toastr.error('Erro ao adicionar comentário');
        }
    }

    expandCard(card) {
        if (card.classList.contains('expanded')) {
            this.collapseCard(card);
        } else {
            this.showExpandedCard(card);
        }
    }

    showExpandedCard(card) {
        card.classList.add('expanded');
        
        // Carregar informações adicionais via AJAX
        const demandaId = card.dataset.id;
        
        fetch(`/api/demandas/${demandaId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.renderExpandedContent(card, data.details);
                }
            });
    }

    renderExpandedContent(card, details) {
        const existingContent = card.querySelector('.expanded-content');
        if (existingContent) {
            existingContent.remove();
        }
        
        const expandedDiv = document.createElement('div');
        expandedDiv.className = 'expanded-content mt-3 pt-3 border-top';
        expandedDiv.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <h6>Descrição Completa:</h6>
                    <p class="text-muted small">${details.descricao || 'Sem descrição'}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <strong>Criada por:</strong><br>
                    <small>${details.criador?.name || 'N/A'}</small>
                </div>
                <div class="col-6">
                    <strong>Data limite:</strong><br>
                    <small>${details.data_vencimento || 'Não definida'}</small>
                </div>
            </div>
            ${details.tags?.length > 0 ? `
                <div class="mt-2">
                    <strong>Tags:</strong><br>
                    ${details.tags.map(tag => `<span class="badge badge-info mr-1">${tag}</span>`).join('')}
                </div>
            ` : ''}
        `;
        
        card.appendChild(expandedDiv);
    }

    collapseCard(card) {
        card.classList.remove('expanded');
        const expandedContent = card.querySelector('.expanded-content');
        if (expandedContent) {
            expandedContent.remove();
        }
    }

    clearFilters() {
        // Remover filtros ativos
        document.querySelectorAll('.kanban-filter.active').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Mostrar todos os cards
        document.querySelectorAll('.kanban-card').forEach(card => {
            card.style.display = 'block';
        });
        
        // Limpar busca
        const searchInput = document.getElementById('kanban-search');
        if (searchInput) {
            searchInput.value = '';
        }
        
        this.updateColumnCounters();
        toastr.info('Filtros limpos');
    }

    navigateCards(direction) {
        const selectedCard = document.querySelector('.kanban-card.selected');
        let targetCard = null;
        
        if (!selectedCard) {
            // Selecionar primeiro card
            targetCard = document.querySelector('.kanban-card');
        } else {
            if (direction === 'next') {
                targetCard = selectedCard.nextElementSibling || 
                           selectedCard.parentElement.parentElement.nextElementSibling?.querySelector('.kanban-card');
            } else {
                targetCard = selectedCard.previousElementSibling ||
                           selectedCard.parentElement.parentElement.previousElementSibling?.querySelector('.kanban-card:last-child');
            }
        }
        
        if (targetCard) {
            // Remover seleção anterior
            document.querySelectorAll('.kanban-card.selected').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Adicionar nova seleção
            targetCard.classList.add('selected');
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    logActivity(action, demandaId, details) {
        // Log para analytics/auditoria
        fetch('/api/logs/activity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                action: action,
                demanda_id: demandaId,
                details: details,
                timestamp: new Date().toISOString()
            })
        }).catch(error => {
            console.log('Erro ao registrar atividade:', error);
        });
    }

    destroy() {
        // Limpar event listeners e instâncias
        this.sortableInstances.forEach(instance => {
            instance.destroy();
        });
        
        // Limpar intervalos
        clearInterval(this.refreshTimer);
        clearInterval(this.newDemandasTimer);
    }
}

// Estilos CSS adicionais para o Kanban
const kanbanStyles = `
<style>
/* Estilos do Kanban */
.dragging-active {
    user-select: none;
}

.dragging-active .kanban-column {
    transition: all 0.3s ease;
}

.drop-zone-valid {
    background-color: rgba(40, 167, 69, 0.1);
    border: 2px dashed #28a745;
    border-radius: 8px;
}

.drop-zone-invalid {
    background-color: rgba(220, 53, 69, 0.1);
    border: 2px dashed #dc3545;
    border-radius: 8px;
}

.sortable-ghost {
    opacity: 0.4;
    background: linear-gradient(45deg, #e3f2fd, #bbdefb);
    transform: rotate(5deg);
}

.sortable-chosen {
    transform: scale(1.05);
    z-index: 999;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.sortable-drag {
    transform: rotate(5deg);
    opacity: 0.8;
}

.sortable-fallback {
    background: #fff;
    border: 2px solid #007bff;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.kanban-card.expanded {
    max-height: none;
    z-index: 10;
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.kanban-card.selected {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}

.card-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255,255,255,0.9);
    padding: 10px;
    border-radius: 50%;
    font-size: 1.2em;
    color: #007bff;
}

.expanded-content {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive */
@media (max-width: 768px) {
    .kanban-board .d-flex {
        flex-direction: column;
    }
    
    .kanban-column {
        margin: 10px 0;
        min-height: 300px;
    }
}
</style>
`;

// Adicionar estilos ao documento
document.head.insertAdjacentHTML('beforeend', kanbanStyles);

// Inicializar sistema quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.kanban-board')) {
        window.kanbanSystem = new KanbanSystem();
    }
});

// Exportar para uso global
window.KanbanSystem = KanbanSystem;
            