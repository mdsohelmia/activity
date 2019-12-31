<?php

namespace Sohel1999\Activity;

use Illuminate\Support\Facades\Auth;

trait Activity
{
    public static function log($action, $model)
    {
        //who
        $userNameColumn = config('activity.user_name_column');

        $user = Auth::user()->name ?? 'guest';
        $ipAddress = \request()->getClientIp();
        //what
        $modelName = class_basename($model);
        $modelId = $model->getKey();
        //how
        $payload = json_encode($model->getDirty());

        ActivityLog::create([
            'user'       => $user,
            'id_address' => $ipAddress,
            'model_name' => $modelName,
            'model_id'   => $modelId,
            'payload'    => $payload,
            'action'     => $action,
        ]);
    }

    public static function bootActivity()
    {
        static::created(function ($model) {
            static::log('created', $model);
        });

        static::updated(function ($model) {
            static::log('updated', $model);
        });
        static::deleted(function ($model) {
            static::log('updated', $model);
        });
    }
}
