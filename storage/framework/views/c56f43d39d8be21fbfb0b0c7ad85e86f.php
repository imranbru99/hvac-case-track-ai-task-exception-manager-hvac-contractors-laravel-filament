<?php
    use Filament\Actions\Action;
    use Filament\Support\Facades\FilamentView;
    use Filament\Support\Icons\Heroicon;
    use Filament\View\PanelsIconAlias;
    use Filament\View\PanelsRenderHook;
    use Illuminate\Support\Arr;

    $debounce = filament()->getGlobalSearchDebounce();
    $keyBindings = filament()->getGlobalSearchKeyBindings();
    $suffix = filament()->getGlobalSearchFieldSuffix();
    $categories = $results?->getCategories()
        ->map(static fn ($groupedResults) => collect($groupedResults))
        ->filter(static fn ($groupedResults): bool => $groupedResults->isNotEmpty());
    $resultsCount = $categories?->sum(static fn ($groupedResults): int => count($groupedResults)) ?? 0;
    $resultsMessage = $resultsCount
        ? trans_choice('filament-panels::global-search.results_count', $resultsCount, ['count' => $resultsCount])
        : __('filament-panels::global-search.no_results_message');
    $id = 'global-search-' . $this->getId();
?>

<div class="fi-global-search-ctn">
    <?php echo e(FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_START)); ?>


    <div
        x-data="filamentGlobalSearch({
                    loadingMessage: <?php echo \Illuminate\Support\Js::from(__('filament-panels::global-search.loading_message'))->toHtml() ?>,
                    failureMessage: <?php echo \Illuminate\Support\Js::from(__('filament-panels::global-search.failure_message'))->toHtml() ?>,
                })"
        x-on:input="if ($event.target === $refs.input) inputChanged($event.target.value)"
        x-on:focusin="if ($event.target === $refs.input) reopen()"
        x-on:focusout="focusLeft($event)"
        x-on:keydown="keydown($event)"
        x-on:click.outside="dismiss()"
        x-on:open-modal.window.capture="
            if ($refs.results.contains(document.activeElement)) {
                dismiss()
                restoreFocus()
            }
        "
        x-on:livewire:navigate.window="navigationStarted($event)"
        x-on:livewire:navigated.window="navigationCompleted()"
        class="fi-global-search"
    >
        <div class="fi-global-search-field">
            <label for="<?php echo e($id); ?>-input" class="fi-sr-only">
                <?php echo e(__('filament-panels::global-search.field.label')); ?>

            </label>

            <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['prefixIcon' => Heroicon::MagnifyingGlass,'prefixIconAlias' => PanelsIconAlias::GLOBAL_SEARCH_FIELD,'inlinePrefix' => true,'suffix' => $suffix,'inlineSuffix' => true,'wire:target' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prefix-icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Heroicon::MagnifyingGlass),'prefix-icon-alias' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(PanelsIconAlias::GLOBAL_SEARCH_FIELD),'inline-prefix' => true,'suffix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($suffix),'inline-suffix' => true,'wire:target' => 'search']); ?>
                <input
                    autocomplete="off"
                    maxlength="1000"
                    placeholder="<?php echo e(__('filament-panels::global-search.field.placeholder')); ?>"
                    type="search"
                    wire:key="global-search.field.input"
                    id="<?php echo e($id); ?>-input"
                    aria-controls="<?php echo e($id); ?>-results"
                    aria-describedby="<?php echo e($id); ?>-instructions"
                    x-ref="input"
                    wire:model.live.debounce.<?php echo e($debounce); ?>="search"
                    x-mousetrap.global.<?php echo e(collect($keyBindings)->map(fn (string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.')); ?>="$refs.input?.focus()"
                    class="fi-input fi-input-has-inline-prefix"
                />
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal505efd9768415fdb4543e8c564dad437)): ?>
<?php $attributes = $__attributesOriginal505efd9768415fdb4543e8c564dad437; ?>
<?php unset($__attributesOriginal505efd9768415fdb4543e8c564dad437); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal505efd9768415fdb4543e8c564dad437)): ?>
<?php $component = $__componentOriginal505efd9768415fdb4543e8c564dad437; ?>
<?php unset($__componentOriginal505efd9768415fdb4543e8c564dad437); ?>
<?php endif; ?>
        </div>

        <p id="<?php echo e($id); ?>-instructions" class="fi-sr-only">
            <?php echo e(__('filament-panels::global-search.field.instructions')); ?>

        </p>

        <p
            wire:ignore
            role="status"
            aria-atomic="true"
            x-text="status"
            class="fi-sr-only"
        ></p>

        <div
            id="<?php echo e($id); ?>-results"
            role="region"
            aria-label="<?php echo e(__('filament-panels::global-search.results_label')); ?>"
            data-query="<?php echo e(trim($this->search ?? '')); ?>"
            data-has-results="<?php echo e($results !== null ? 'true' : 'false'); ?>"
            data-message="<?php echo e($resultsMessage); ?>"
            x-ref="results"
            x-show="isOpen"
            x-bind:inert="! isOpen"
            x-bind:aria-hidden="! isOpen"
            x-cloak
            x-on:mousedown.self.prevent
            x-on:click="linkActivated($event)"
            x-transition:enter-start="fi-transition-enter-start"
            x-transition:leave-end="fi-transition-leave-end"
            class="fi-global-search-results-ctn"
        >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($results !== null): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->isEmpty()): ?>
                    <p class="fi-global-search-no-results-message">
                        <?php echo e(__('filament-panels::global-search.no_results_message')); ?>

                    </p>
                <?php else: ?>
                    <ul class="fi-global-search-results">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupedResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $groupHeadingId = $id . '-group-' . $loop->index;
                            ?>

                            <li class="fi-global-search-result-group">
                                <h3
                                    id="<?php echo e($groupHeadingId); ?>"
                                    class="fi-global-search-result-group-header"
                                >
                                    <?php echo e($group); ?>

                                </h3>

                                <ul
                                    class="fi-global-search-result-group-results"
                                >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupedResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $resultVisibleActions = $result->getVisibleActions();
                                            $resultHeadingId = $groupHeadingId . '-result-' . $loop->index;
                                        ?>

                                        <li
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'fi-global-search-result',
                                                'fi-global-search-result-has-actions' => $resultVisibleActions,
                                            ]); ?>"
                                        >
                                            <a
                                                <?php echo e(\Filament\Support\generate_href_html($result->url)); ?>

                                                class="fi-global-search-result-link"
                                            >
                                                <h4
                                                    id="<?php echo e($resultHeadingId); ?>"
                                                    class="fi-global-search-result-heading"
                                                >
                                                    <?php echo e($result->title); ?>

                                                </h4>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result->details): ?>
                                                    <dl
                                                        class="fi-global-search-result-details"
                                                    >
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $result->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div
                                                                class="fi-global-search-result-detail"
                                                            >
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAssoc ??= Arr::isAssoc($result->details)): ?>
                                                                    <dt
                                                                        class="fi-global-search-result-detail-label"
                                                                    >
                                                                        <?php echo e($label); ?>:
                                                                    </dt>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                                <dd
                                                                    class="fi-global-search-result-detail-value"
                                                                >
                                                                    <?php echo e($value); ?>

                                                                </dd>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </dl>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </a>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resultVisibleActions): ?>
                                                <div
                                                    class="fi-global-search-result-actions"
                                                >
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $resultVisibleActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $actionAttributes = $action->getExtraAttributes();
                                                            $actionAttributes['aria-describedby'] = trim(($actionAttributes['aria-describedby'] ?? '') . ' ' . $groupHeadingId . ' ' . $resultHeadingId);

                                                            if (in_array($action->getView(), [Action::LINK_VIEW, Action::BUTTON_VIEW, Action::ICON_BUTTON_VIEW, Action::BADGE_VIEW])) {
                                                                $actionAttributes['data-global-search-action'] = true;
                                                            }
                                                        ?>

                                                        <?php echo e((clone $action)->extraAttributes($actionAttributes)); ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php echo e(FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_END)); ?>

</div>
<?php /**PATH /var/www/html/vendor/filament/filament/resources/views/livewire/global-search.blade.php ENDPATH**/ ?>