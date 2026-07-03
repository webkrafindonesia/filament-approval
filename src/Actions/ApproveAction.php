<?php

namespace Wezlo\FilamentApproval\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\Services\ApprovalEngine;

class ApproveAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'approve';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-approval::approval.actions.approve'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(function (): bool {
                $record = $this->getRecord();
                $approval = $record->currentApproval();

                if (! $approval) {
                    return false;
                }

                $stepInstance = $approval->currentStepInstance();

                if (! $stepInstance) {
                    return false;
                }

                return $stepInstance->canUserAct(auth()->id())
                    && ! $stepInstance->hasUserActed(auth()->id());
            })
            ->schema([
                Textarea::make('comment')
                    ->label(__('filament-approval::approval.actions.comment_optional'))
                    ->rows(3),
            ])
            ->action(function (array $data): void {
                $record = $this->getRecord();
                $stepInstance = $record->currentApproval()->currentStepInstance();

                app(ApprovalEngine::class)->approve(
                    $stepInstance,
                    auth()->id(),
                    $data['comment'] ?? null,
                );

                // Notification::make()
                //     ->title(__('filament-approval::approval.actions.approved_success'))
                //     ->success()
                //     ->send();
            })
            ->requiresConfirmation()
            ->modalHeading(__('filament-approval::approval.actions.approve_heading'));
    }
}
