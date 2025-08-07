{{-- CTA Section Component --}}
<section class="cta" id="cta">
    <div class="container">
        <div class="cta-content fade-in">
            <h2 class="cta-title lg:text-4xl">
                {{ $title ?? 'Prêt à révolutionner vos livraisons ?' }}
            </h2>
            <p class="cta-description">
                {{ $description ?? 'Rejoignez des milliers d\'utilisateurs qui font confiance à CarryWay pour leurs besoins de livraison.' }}
            </p>
            <div class="cta-actions sm:flex-row">
                <a href="{{ $primaryActionUrl ?? route('register') }}" class="btn btn-primary">
                    <i data-lucide="{{ $primaryActionIcon ?? 'rocket' }}" class="icon-sm mr-2"></i>
                    {{ $primaryActionText ?? 'Commencer maintenant' }}
                </a>
                <a href="{{ $secondaryActionUrl ?? '#' }}" class="btn btn-outline">
                    {{ $secondaryActionText ?? 'Planifier une démo' }}
                </a>
            </div>
        </div>
    </div>
</section>
