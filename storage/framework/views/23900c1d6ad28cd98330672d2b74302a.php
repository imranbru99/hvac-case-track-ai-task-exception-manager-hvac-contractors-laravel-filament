<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'applyAction',
    'form',
    'headingTag' => 'h3',
    'resetAction' => null,
    'resetActionPosition' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'applyAction',
    'form',
    'headingTag' => 'h3',
    'resetAction' => null,
    'resetActionPosition' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Filament\Tables\Enums\FiltersResetActionPosition;

    $resetActionPosition ??= FiltersResetActionPosition::Header;
?>

<div <?php echo e($attributes->class(['fi-ta-filters'])); ?>>
    <div class="fi-ta-filters-header">
        <<?php echo e($headingTag); ?> class="fi-ta-filters-heading">
            <?php echo e(__('filament-tables::table.filters.heading')); ?>

        </<?php echo e($headingTag); ?>>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($resetActionPosition === FiltersResetActionPosition::Header) && $resetAction?->isVisible()): ?>
            <div>
                <?php echo e($resetAction->defaultView($resetAction::LINK_VIEW)); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo e($form); ?>


    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($applyAction->isVisible() || (($resetActionPosition === FiltersResetActionPosition::Footer) && $resetAction?->isVisible())): ?>
        <div class="fi-ta-filters-actions-ctn">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($applyAction->isVisible()): ?>
                <?php echo e($applyAction); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($resetActionPosition === FiltersResetActionPosition::Footer) && $resetAction?->isVisible()): ?>
                <?php echo e($resetAction); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /var/www/html/vendor/filament/tables/resources/views/components/filters.blade.php ENDPATH**/ ?>