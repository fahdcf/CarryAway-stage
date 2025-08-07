


<div id="warningModal" class="modal-overlay" style="display:none; opacity:0;">
    <div class="modal-content">
        <div class="modal-icon-top">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" fill="#f3f4f6"/>
                <path d="M12 8v4" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="16" r="1.5" fill="#fbbf24"/>
            </svg>
        </div>
        <div class="modal-header" style="flex-direction:column; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
            <h2 class="modal-title" style="color:#fbbf24;">Petite confirmation</h2>
            <div class="modal-subtitle" style="color:#b45309;">Voulez-vous vraiment désactiver cet utilisateur ?</div>
        </div>
        <div class="modal-body">
            <p class="modal-message">La désactivation empêchera l'utilisateur d'accéder à son compte. Vous pouvez réactiver le compte à tout moment.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeWarningModal()">Annuler</button>
            <button class="btn-confirm" id="modalConfirmBtn">Désactiver</button>
        </div>
    </div>
</div>
<style>
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: opacity 0.35s cubic-bezier(.4,0,.2,1);
}
.modal-overlay.show {
    opacity: 1 !important;
}
.modal-content {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 32px rgba(71,85,105,0.10);
    padding: 2rem 2rem 1.5rem 2rem;
    min-width: 320px;
    max-width: 95vw;
    text-align: center;
    animation: modalFadeIn 0.45s cubic-bezier(.4,0,.2,1);
    position: relative;
}
.modal-icon-top {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
}
.modal-title {
    color: #fbbf24;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0.15rem;
}
.modal-subtitle {
    color: #b45309;
    font-size: 0.95rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}
.modal-message {
    color: #374151;
    font-size: 0.98rem;
    margin-bottom: 1.25rem;
}
.modal-footer {
    display: flex;
    gap: 1.1rem;
    justify-content: center;
}
.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: none;
    border-radius: 8px;
    padding: 0.45rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(71,85,105,0.06);
}
.btn-cancel:hover {
    background: #e5e7eb;
    box-shadow: 0 4px 16px rgba(71,85,105,0.10);
}
.btn-confirm {
    background: linear-gradient(90deg,#fef9c3 0%,#fde68a 100%);
    color: #b45309;
    border: none;
    border-radius: 8px;
    padding: 0.45rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(251,191,36,0.08);
}
.btn-confirm:hover {
    background: linear-gradient(90deg,#fde68a 0%,#fef9c3 100%);
    box-shadow: 0 4px 16px rgba(251,191,36,0.12);
}
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
<script>
function openWarningModal(onConfirm) {
    const overlay = document.getElementById('warningModal');
    overlay.style.display = 'flex';
    setTimeout(() => overlay.classList.add('show'), 10);
    const confirmBtn = document.getElementById('modalConfirmBtn');
    confirmBtn.onclick = function() {
        closeWarningModal();
        if (typeof onConfirm === 'function') onConfirm();
    };
}
function closeWarningModal() {
    const overlay = document.getElementById('warningModal');
    overlay.classList.remove('show');
    setTimeout(() => overlay.style.display = 'none', 350);
}
</script>
