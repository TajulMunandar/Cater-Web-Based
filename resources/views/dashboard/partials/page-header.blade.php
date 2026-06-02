<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 animate-fade-in-up">
    <div>
        <h1 class="fw-bold mb-1" style="font-size:1.5rem;letter-spacing:-0.04em;color:var(--color-text);">{{ $title }}</h1>
        <p class="mb-0 d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--color-text-muted);">
            <i class="ti ti-{{ $icon ?? 'file-text' }}" style="font-size:0.9rem;"></i>
            {{ $subtitle }}
        </p>
    </div>
</div>
