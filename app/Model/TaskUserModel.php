<?php

namespace Kanboard\Model;

use Kanboard\Core\Base;

/**
 * TaskUser model
 *
 * @package Kanboard\Model
 * @author  Olivier Maridat
 * @author  Frederic Guillot
 */
class TaskUserModel extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'task_has_users';

    /**
     * Events
     *
     * @var string
     */
    const EVENT_CREATE = 'task_user.create';
    const EVENT_DELETE = 'task_user.delete';


    /**
     * Fetch one task user
     *
     * @param  integer   $task_id  Task id
     * @param  integer   $user_id  User id
     * @return array|null
     */
    public function getById($task_id, $user_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->columns(
                self::TABLE.'.user_id',
                self::TABLE.'.task_id',
                UserModel::TABLE.'.username',
                UserModel::TABLE.'.name',
                UserModel::TABLE.'.email',
                UserModel::TABLE.'.avatar_path'
            )
            ->join(UserModel::TABLE, 'id', 'user_id', self::TABLE)
            ->eq(self::TABLE.'.task_id', $task_id)
            ->eq(self::TABLE.'.user_id', $user_id)
            ->findOne();
    }


    /**
     * Get all users assigned to this task
     *
     * @access public
     * @param  integer   $task_id   Task id
     * @return array
     */
    public function getAll($task_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->columns(
                self::TABLE.'.user_id',
                self::TABLE.'.task_id',
                UserModel::TABLE.'.username',
                UserModel::TABLE.'.name',
                UserModel::TABLE.'.email',
                UserModel::TABLE.'.avatar_path'
            )
            ->eq(self::TABLE.'.task_id', $task_id)
            ->join(UserModel::TABLE, 'id', 'user_id', self::TABLE)
            ->asc(UserModel::TABLE.'.username')
            ->asc(UserModel::TABLE.'.name')
            ->findAll();
    }


    /**
     * Add a new user to the task list
     *
     * @access public
     * @param  integer   $task_id  Task id
     * @param  integer   $user_id  User id
     * @return boolean
     */
    public function create($task_id, $user_id)
    {
        $this->db->startTransaction();

        $record = array(
            'task_id' => $task_id,
            'user_id' => $user_id,
        ) ;

        if (! $this->db->table(self::TABLE)->insert($record)) {
            $this->db->cancelTransaction();
            return false;
        }
        $this->db->closeTransaction();

        $this->queueManager->push($this->taskUserEventJob->withParams($task_id, $user_id, self::EVENT_CREATE));
        return true ;
    }


    /**
     * Remove a user from the task
     *
     * @access public
     * @param  integer   $task_id  Task id
     * @param  integer   $user_id  User id
     * @return boolean
     */
    public function remove($task_id, $user_id)
    {
        $this->taskUserEventJob->execute($task_id, $user_id, self::EVENT_DELETE);

        $this->db->startTransaction();
        $result = $this->db
            ->table(self::TABLE)
            ->eq('task_id', $task_id)
            ->eq('user_id', $user_id)
            ->remove();

        if ($result === false) {
            $this->db->cancelTransaction();
            return false;
        }

        $this->db->closeTransaction();

        return true;
    }


}
