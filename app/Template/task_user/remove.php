<div class="page-header">
    <h2><?= t('Remove an assigned user') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove the user %s from this task?', ($taskuser['name'] ?: $taskuser['username']) ) ?>
    </p>

    <?= $this->modal->confirmButtons(
        'TaskUserController',
        'remove',
        array('user_id' => $taskuser['user_id'], 'task_id' => $task['id'], 'project_id' => $task['project_id'])
    ) ?>
</div>
