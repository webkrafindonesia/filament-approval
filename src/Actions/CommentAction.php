<?php

namespace Wezlo\FilamentApproval\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\Services\ApprovalEngine;

class CommentAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'approvalComment';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-approval::approval.actions.comment'))
            ->icon(Heroicon::OutlinedChatBubbleLeftEllipsis)
            ->color('gray')
            ->visible(function (): bool {
                $record = $this->getRecord();

                if (! method_exists($record, 'currentApproval')) {
                    return false;
                }

                $approval = $record->currentApproval();

                if (! $approval) {
                    return false;
                }

                $stepInstance = $approval->currentStepInstance();

                // Approver assigned to current step
                if ($stepInstance && $stepInstance->canUserAct(auth()->id())) {
                    return true;
                }

                // Submitter / Salesman / Creator can comment while approval is pending
                $userId = auth()->id();
                $isSubmitter = $approval->submitted_by === $userId
                    || ($record->sales_id ?? null) === $userId
                    || ($record->created_by ?? null) === $userId
                    || auth()->user()?->hasRole('Super Admin')
                    || auth()->user()?->hasRole('super_admin');

                return $isSubmitter && $approval->status === \Wezlo\FilamentApproval\Enums\ApprovalStatus::Pending;
            })
            ->schema([
                Textarea::make('comment')
                    ->label(__('filament-approval::approval.actions.comment'))
                    ->required()
                    ->rows(3),
            ])
            ->action(function (array $data): void {
                $record = $this->getRecord();
                $approval = $record->currentApproval();
                $stepInstance = $approval->currentStepInstance();

                app(ApprovalEngine::class)->comment(
                    $approval,
                    auth()->id(),
                    $data['comment'],
                    $stepInstance,
                );

                // Notification::make()
                //     ->title(__('filament-approval::approval.actions.comment_success'))
                //     ->success()
                //     ->send();
            });
    }
}
