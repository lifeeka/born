<?php


namespace App\Services\Self;


class TaskMapper
{
    public $tasks = [
            "update" => UpdateTask::class,
            "build" => BuildTask::class,
            "sync-tags" => SyncTagsTask::class,
    ];
}