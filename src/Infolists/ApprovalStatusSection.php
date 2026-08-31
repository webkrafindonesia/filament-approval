<?php

namespace Wezlo\FilamentApproval\Infolists;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Wezlo\FilamentApproval\FilamentApprovalPlugin;

class ApprovalStatusSection
{
    public static function make(): Section
    {
        return Section::make(__('filament-approval::approval.infolist.approval_status'))
            ->schema([
                TextEntry::make('latestApproval.status')
                    ->label(__('filament-approval::approval.infolist.status'))
                    ->badge()
                    ->placeholder(__('filament-approval::approval.infolist.not_submitted'))
                    ->columnSpan(1),
                TextEntry::make('latestApproval.flow.name')
                    ->label(__('filament-approval::approval.infolist.flow'))
                    ->placeholder('-')
                    ->columnSpan(1),
                TextEntry::make('latestApproval.submitter.name')
                    ->label(__('filament-approval::approval.infolist.submitted_by'))
                    ->placeholder('-')
                    ->columnSpan(1),
                TextEntry::make('latestApproval.submitted_at')
                    ->label(__('filament-approval::approval.infolist.submitted'))
                    ->dateTime()
                    ->placeholder('-')
                    ->columnSpan(1),
                TextEntry::make('latestApproval.completed_at')
                    ->label(__('filament-approval::approval.infolist.completed'))
                    ->dateTime()
                    ->placeholder(__('filament-approval::approval.infolist.in_progress'))
                    ->columnSpan(1),

                Section::make(__('filament-approval::approval.infolist.current_step'))
                    ->schema([
                        TextEntry::make('currentApproval.currentStepInstance.step.name')
                            ->label(__('filament-approval::approval.infolist.step'))
                            ->placeholder('N/A'),
                        TextEntry::make('currentApproval.currentStepInstance.status')
                            ->label(__('filament-approval::approval.infolist.status'))
                            ->badge()
                            ->placeholder('N/A'),
                        TextEntry::make('pending_approvers_display')
                            ->label(__('filament-approval::approval.infolist.pending_approvers'))
                            ->state(function ($record): string {
                                $ids = $record->currentApproval()?->currentStepInstance()?->assigned_approver_ids;

                                if (empty($ids)) {
                                    return '-';
                                }

                                $userModel = FilamentApprovalPlugin::resolveUserModel();

                                return $userModel::whereIn('id', $ids)
                                    ->pluck('name')
                                    ->join(', ') ?: '-';
                            }),
                        TextEntry::make('currentApproval.currentStepInstance.received_approvals')
                            ->label(__('filament-approval::approval.infolist.progress'))
                            ->formatStateUsing(function ($state, $record): string {
                                $stepInstance = $record->currentApproval()?->currentStepInstance();

                                if (!$stepInstance) {
                                    return '-';
                                }

                                return __('filament-approval::approval.infolist.approvals_count', [
                                    'received' => $stepInstance->received_approvals,
                                    'required' => $stepInstance->required_approvals,
                                ]);
                            })
                            ->placeholder('-'),
                        TextEntry::make('currentApproval.currentStepInstance.sla_deadline')
                            ->label(__('filament-approval::approval.infolist.sla_deadline'))
                            ->dateTime()
                            ->placeholder(__('filament-approval::approval.infolist.no_sla'))
                            ->color(function ($state) {
                                if (!$state) {
                                    return null;
                                }

                                return $state->isPast() ? 'danger' : null;
                            }),
                    ])
                    ->columns(3)
                    ->visible(fn($record): bool => $record->currentApproval()?->currentStepInstance() !== null),

                Section::make(__('filament-approval::approval.infolist.recent_activity'))
                    ->schema([
                        RepeatableEntry::make('latestApproval.actions')
                            ->label('')
                            ->schema([
                                TextEntry::make('type')
                                    ->label(__('filament-approval::approval.fields.type'))
                                    ->badge(),
                                TextEntry::make('user.name')
                                    ->label(__('filament-approval::approval.infolist.by'))
                                    ->placeholder(__('filament-approval::approval.infolist.system')),
                                TextEntry::make('comment')
                                    ->label(__('filament-approval::approval.fields.comment'))
                                    ->formatStateUsing(fn($state) => $state ? new \Illuminate\Support\HtmlString(nl2br(e($state))) : null)
                                    ->html()
                                    ->placeholder('-'),
                                TextEntry::make('created_at')
                                    ->label(__('filament-approval::approval.infolist.date'))
                                    ->since(),
                            ])
                            ->columns(4),
                    ])
                    ->collapsible()
                    ->visible(fn($record): bool => $record->latestApproval()?->actions()->exists() ?? false),
            ])
            ->columns(3)
            ->collapsible()
            ->visible(fn($record): bool => method_exists($record, 'approvals') && $record->latestApproval() !== null);
    }
}
