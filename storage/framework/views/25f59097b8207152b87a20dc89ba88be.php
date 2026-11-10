<?php
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\VerticalAlignment;
?>

<div
    x-data="notificationsComponent()"
    data-filament-notifications="<?php echo e($hasPulledNotificationsOnMount ? 'mounted' : 'pending'); ?>"
>
    <div
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fi-no',
            'fi-align-' . static::$alignment->value,
            'fi-vertical-align-' . static::$verticalAlignment->value,
        ]); ?>"
        role="status"
        aria-atomic="false"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($notification); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($broadcastChannel = $this->getBroadcastChannel()): ?>
            <?php
        $__scriptKey = '642100889-0';
        ob_start();
    ?>
            <script>
                setUpFilamentBroadcastNotifications({
                    $wire,
                    broadcastChannel: <?php echo \Illuminate\Support\Js::from($broadcastChannel)->toHtml() ?>,
                    event: '.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
                    onNotification: (notification) =>
                        $wire.handleBroadcastNotification(notification),
                })
            </script>
            <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /var/www/html/vendor/filament/notifications/resources/views/notifications.blade.php ENDPATH**/ ?>