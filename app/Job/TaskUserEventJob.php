<?php

namespace Kanboard\Job;

use Kanboard\EventBuilder\TaskUserEventBuilder;

/**
 * Class TaskUserEventJob
 *
 * @package Kanboard\Job
 * @author  Frederic Guillot
 */
class TaskUserEventJob extends BaseJob
{
    /**
     * Set job params
     *
     * @param  int    $taskId
     * @param  int    $userId
     * @param  string $eventName
     * @return $this
     */
    public function withParams($taskId, $userId, $eventName)
    {
        $this->jobParams = array($taskId, $userId, $eventName);
        return $this;
    }

    /**
     * Execute job
     *
     * @param  int    $taskId
     * @param  int    $userId
     * @param  string $eventName
     * @return $this
     */
    public function execute($taskId, $userId, $eventName)
    {
        $event = TaskUserEventBuilder::getInstance($this->container)
            ->withTaskId($taskId)
            ->withUserId($userId)
            ->buildEvent();

        if ($event !== null) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}
