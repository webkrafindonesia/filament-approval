<?php

namespace Wezlo\FilamentApproval\Notifications;

use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Wezlo\FilamentApproval\FilamentApprovalPlugin;
use Wezlo\FilamentApproval\Models\Approval;

class ApprovalRejectedNotification
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
        //     ->title(__('filament-approval::approval.notifications.rejected_title'))
        //     ->body(__('filament-approval::approval.notifications.rejected_body', ['model' => $modelLabel, 'id' => $approvable->getKey()]))
        //     ->icon(Heroicon::OutlinedXCircle)
        //     ->danger()
        //     ->broadcast($recipient)
        //     ->sendToDatabase($recipient);
    }
}
