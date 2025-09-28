<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity($type, $model)
    {
        $module = strtolower(class_basename($model));
        
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => $module,
            'activity_type' => $type,
            'description' => self::getDescription($type, $model),
            'old_data' => $type === 'update' ? $model->getOriginal() : null,
            'new_data' => $type !== 'delete' ? $model->getAttributes() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    protected static function getDescription($type, $model)
    {
        $modelName = class_basename($model);
        $identifier = $model->name ?? $model->id;

        return match($type) {
            'create' => "{$modelName} '{$identifier}' telah dibuat",
            'update' => "{$modelName} '{$identifier}' telah diperbarui",
            'delete' => "{$modelName} '{$identifier}' telah dihapus",
            default => "Aktivitas pada {$modelName} '{$identifier}'"
        };
    }
}