<?php

namespace Kanboard\Controller;

use Kanboard\Core\Controller\PageNotFoundException;

/**
 * TaskUserController Controller
 *
 * @package  Kanboard\Controller
 * @author   BronwenReid
 * @author   Olivier Maridat
 * @author   Frederic Guillot
 */
class TaskUserController extends BaseController
{
    /**
     * Get the current link
     *
     * @access private
     * @return array
     * @throws PageNotFoundException
     */
    private function getTaskLink()
    {
        $link = $this->taskLinkModel->getById($this->request->getIntegerParam('link_id'));

        if (empty($link)) {
            throw new PageNotFoundException();
        }

        return $link;
    }

    /**
     * Creation form
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws PageNotFoundException
     * @throws \Kanboard\Core\Controller\AccessForbiddenException
     */
    public function create(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task_user/create', array(
            'values' => $values,
            'errors' => $errors,
            'task' => $task,
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($task['project_id'], false, false, false)
        )));
    }

    /**
     * Validation and creation
     *
     * @access public
     */
    public function save()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskUserValidator->validateCreation($values);

        if ($valid) {
            if ($this->taskUserModel->create($values['task_id'], $values['user_id'])) {
                $this->flash->success(t('User assigned to task successfully.'));
                return $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])), true);
            }

            $errors = array('user_id' => array(t('User already assigned to task')));
            $this->flash->failure(t('Unable to assign user'));
        }

        return $this->create($values, $errors);
    }


    /**
     * Confirmation dialog before removing a user
     *
     * @access public
     */
    public function confirm()
    {
        $task = $this->getTask();
        $taskuser = $this->taskUserModel->getById($this->request->getIntegerParam('task_id'),$this->request->getIntegerParam('user_id'));
        if (empty($taskuser)) {
            throw new PageNotFoundException();
        }

        $this->response->html($this->template->render('task_user/remove', array(
            'taskuser' => $taskuser,
            'task' => $task,
        )));
    }

    /**
     * Remove a link
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $task = $this->getTask();

        if ($this->taskUserModel->remove($this->request->getIntegerParam('task_id'),$this->request->getIntegerParam('user_id'))) {
            $this->flash->success(t('User removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this user'));
        }

        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])));
    }
}
