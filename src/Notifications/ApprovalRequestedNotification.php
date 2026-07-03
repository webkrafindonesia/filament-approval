<?php

namespace Wezlo\FilamentApproval\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\FilamentApprovalPlugin;
use Wezlo\FilamentApproval\Models\ApprovalStepInstance;

class ApprovalRequestedNotification
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
        //
        // Notification::make()
        //     ->title(__('filament-approval::approval.notifications.requested_title', ['step' => $stepInstance->step->name]))
        //     ->body(__('filament-approval::approval.notifications.requested_body', ['model' => $modelLabel, 'id' => $approvable->getKey()]))
        //     ->icon(Heroicon::OutlinedClipboardDocumentCheck)
        //     ->warning()
        //     ->broadcast($recipient)
        //     ->sendToDatabase($recipient);
    }
}
