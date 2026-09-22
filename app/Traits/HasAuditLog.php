<?php

namespace App\Traits;

use App\Enums\AuditLogAction;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasAuditLog
{
    public static function bootHasAuditLog(): void
    {
        static::created(function (Model $model) {
            static::logAudit($model, AuditLogAction::Create);
        });

        static::updated(function (Model $model) {
            $changes = [];

            foreach ($model->getChanges() as $key => $newValue) {
                if ($key === 'updated_at') {
                    continue;
                }

                $original = $model->getOriginal($key);

                if ($original != $newValue) {
                    $changes[] = [
                        'key' => $key,
                        'old' => $original,
                        'new' => $newValue,
                    ];
                }
            }

            if (! empty($changes)) {
                static::logAudit($model, AuditLogAction::Update, $changes);
            }
        });

        static::deleted(function (Model $model) {
            $data = [];

            foreach ($model->getOriginal() as $key => $value) {
                if (\in_array($key, ['id', 'created_at', 'updated_at'])) {
                    continue;
                }

                $data[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }

            static::logAudit($model, AuditLogAction::Delete, $data);
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                self::logAudit($model, AuditLogAction::Restore);
            });
        }
    }



    protected static function logAudit(Model $model, AuditLogAction $action, ?array $changes = null): void
    {
        $log = new AuditLog([
            'user_id' => Auth::id() ?? null,
            'action' => $action,
            'changes' => $changes,
            'date' => now(),
        ]);

        $log->subject()->associate($model);
        $log->save();
    }
}
