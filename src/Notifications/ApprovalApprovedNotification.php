<?php

namespace Wezlo\FilamentApproval\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\FilamentApprovalPlugin;
use Wezlo\FilamentApproval\Models\Approval;

class ApprovalApprovedNotification
{
    public static function send(Approval $approval, string|int $userId): void
    {
        // $userModel = FilamentApprovalPlugin::resolveUserModel();
        // $recipient = $userModel::find($userId);
        //
        // if (! $recipient) {
        //     return;
        // }
        //
        // $approvable = $approval->approvable;
        // $modelLabel = class_basename($approvable);
        //
        // Notification::make()
        //     ->title(__('filament-approval::approval.notifications.approved_title'))
        //     ->body(__('filament-approval::approval.notifications.approved_body', ['model' => $modelLabel, 'id' => $approvable->getKey()]))
        //     ->icon(Heroicon::OutlinedCheckCircle)
        //     ->success()
        //     ->broadcast($recipient)
        //     ->sendToDatabase($recipient);
    }
}
