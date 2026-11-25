<?php $flash = pullFlash(); ?>
<?php if ($flash): ?>
    <div class="notice <?= ($flash['type'] ?? '') === 'error' ? 'notice-error' : '' ?>">
        <?= e($flash['message']) ?>
    </div>
<?php endif; ?>
