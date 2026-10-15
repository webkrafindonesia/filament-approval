<?php

namespace Wezlo\FilamentApproval\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
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
            ->visible(function (?Model $record = null): bool {
                $record ??= $this->getRecord();
                if (! $record) {
                    return false;
                }

                $approval = $record->currentApproval();

                if (! $approval) {
                    return false;
                }

                $stepInstance = $approval->currentStepInstance();

                if (! $stepInstance) {
                    return false;
                }

                $userId = auth()->id();
                if (! $userId) {
                    return false;
                }

                return $stepInstance->canUserAct($userId)
                    && ! $stepInstance->hasUserActed($userId);
            })
            ->schema([
                Textarea::make('comment')
                    ->label(__('filament-approval::approval.actions.comment_optional'))
                    ->rows(3),
            ])
            ->action(function (array $data, ?Model $record = null): void {
                $record ??= $this->getRecord();
                if (! $record) {
                    return;
                }
                $stepInstance = $record->currentApproval()?->currentStepInstance();
                if (! $stepInstance) {
                    return;
                }

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
