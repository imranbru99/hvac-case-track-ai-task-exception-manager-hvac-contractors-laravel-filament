<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filament()->hasUnsavedChangesAlerts()): ?>
        <?php
        $__scriptKey = '617597185-0';
        ob_start();
    ?>
        <script>
            setUpFilamentUnsavedActionChangesAlert({
                resolveLivewireComponentUsing: () => window.Livewire.find('<?php echo e($_instance->getId()); ?>'),
                $wire,
            })
        </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /var/www/html/vendor/filament/filament/resources/views/components/unsaved-action-changes-alert.blade.php ENDPATH**/ ?>