<?php

namespace Wezlo\FilamentApproval\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\Models\ApprovalFlow;
use Wezlo\FilamentApproval\Services\ApprovalEngine;

class SubmitForApprovalAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'submitForApproval';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-approval::approval.actions.submit'))
            ->icon(Heroicon::OutlinedPaperAirplane)
            ->color('info')
            ->visible(function (): bool {
                $record = $this->getRecord();

                if (! method_exists($record, 'canBeSubmittedForApproval')) {
                    return false;
                }

                return $record->canBeSubmittedForApproval();
            })
            ->schema(function (): array {
                $record = $this->getRecord();
                $flows = ApprovalFlow::forModel($record)->get();

                $commentSchema = Textarea::make('comment')
                    ->label(__('filament-approval::approval.actions.comment_optional'))
                    ->rows(3);

                if ($flows->count() <= 1) {
                    return [$commentSchema];
                }

                return [
                    Select::make('approval_flow_id')
                        ->label(__('filament-approval::approval.actions.approval_flow'))
                        ->options($flows->pluck('name', 'id'))
                        ->required(),
                    $commentSchema,
                ];
            })
            ->action(function (array $data): void {
                $record = $this->getRecord();
                $flow = isset($data['approval_flow_id'])
                    ? ApprovalFlow::find($data['approval_flow_id'])
                    : null;

                app(ApprovalEngine::class)->submit(
                    $record,
                    $flow,
                    null,
                    $data['comment'] ?? null,
                );

                // Notification::make()
                //     ->title(__('filament-approval::approval.actions.submitted_success'))
                //     ->success()
                //     ->send();
            })
            ->requiresConfirmation();
    }
}
