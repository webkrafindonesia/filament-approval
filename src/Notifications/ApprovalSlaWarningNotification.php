<?php

namespace Wezlo\FilamentApproval\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\FilamentApprovalPlugin;
use Wezlo\FilamentApproval\Models\ApprovalStepInstance;

class ApprovalSlaWarningNotification
{
    public static function send(ApprovalStepInstance $stepInstance, string|int $userId): void
    {
        // $userModel = FilamentApprovalPlugin::resolveUserModel();
        // $recipient = $userModel::find($userId);
        //
        // if (! $recipient) {
        //     return;
        // }
        //
        // $approvable = $stepInstance->approval->approvable;
        // $modelLabel = class_basename($approvable);
        // $deadline = $stepInstance->sla_deadline->diffForHumans();
        //
        // Notification::make()
        //     ->title(__('filament-approval::approval.notifications.sla_warning_title'))
        //     ->body(__('filament-approval::approval.notifications.sla_warning_body', ['model' => $modelLabel, 'id' => $approvable->getKey(), 'deadline' => $deadline]))
        //     ->icon(Heroicon::OutlinedClock)
        //     ->warning()
        //     ->broadcast($recipient)
        //     ->sendToDatabase($recipient);
    }
}
