<?php

namespace Kanboard\EventBuilder;

use Kanboard\Event\TaskUserEvent;
use Kanboard\Model\TaskUserModel;

/**
 * Class TaskUserEventBuilder
 *
 * @package Kanboard\EventBuilder
 * @author  Frederic Guillot
 */
class TaskUserEventBuilder extends BaseEventBuilder
{
    protected $userId = 0;
    protected $taskId = 0;

    /**
     * Set userId
     *
     * @param  int $userId
     * @return $this
     */
    public function withUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Set taskId
     *
     * @param  int $userId
     * @return $this
     */
    public function withTaskId($taskId)
    {
        $this->taskId = $taskId;
        return $this;
    }

    /**
     * Build event data
     *
     * @access public
     * @return TaskUserEvent|null
     */
    public function buildEvent()
    {
        $taskUser = $this->taskUserModel->getById($this->taskId,$this->userId);
        if (empty($taskUser)) {
            $this->logger->debug(__METHOD__.': taskUser not found');
            return null;
        }
        return new TaskUserEvent(array(
            'taskuser' => $taskUser,
            'task' => $this->taskFinderModel->getDetails($this->taskId),
        ));
    }

    /**
     * Get event title with author
     *
     * @access public
     * @param  string $author
     * @param  string $eventName
     * @param  array  $eventData
     * @return string
     */
    public function buildTitleWithAuthor($author, $eventName, array $eventData)
    {
        $name = $eventData['taskuser']['name'] ?: $eventData['taskuser']['username'];
        if ($eventName === TaskUserModel::EVENT_CREATE) {
            return e('%s added user %s to the task #%d', $author, $name, $eventData['task']['id']);
        } elseif ($eventName === TaskUserModel::EVENT_DELETE) {
            return e('%s removed user %s from the task #%d', $author, $name, $eventData['task']['id']);
        }

        return '';
    }

    /**
     * Get event title without author
     *
     * @access public
     * @param  string $eventName
     * @param  array  $eventData
     * @return string
     */
    public function buildTitleWithoutAuthor($eventName, array $eventData)
    {
        $name = $eventData['taskuser']['name'] ?: $eventData['taskuser']['username'];
        if ($eventName === TaskUserModel::EVENT_CREATE) {
            return e('User %s added to the task #%d', $name, $eventData['task']['id']);
        } elseif ($eventName === TaskUserModel::EVENT_DELETE) {
            return e('User %s removed from the task #%d', $name, $eventData['task']['id']);
        }

        return '';
    }
}
