<?php

namespace Wezlo\FilamentApproval\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Wezlo\FilamentApproval\Services\ApprovalEngine;

class RejectAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'reject';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-approval::approval.actions.reject'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
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
                    ->label(__('filament-approval::approval.actions.rejection_reason'))
                    ->required()
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

                app(ApprovalEngine::class)->reject(
                    $stepInstance,
                    auth()->id(),
                    $data['comment'],
                );

                // Notification::make()
                //     ->title(__('filament-approval::approval.actions.rejected_success'))
                //     ->danger()
                //     ->send();
            })
            ->requiresConfirmation()
            ->modalHeading(__('filament-approval::approval.actions.reject_heading'));
    }
}
