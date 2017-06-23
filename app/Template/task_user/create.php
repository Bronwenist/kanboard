<div class="page-header">
    <h2><?= t('Add a new user') ?></h2>
</div>

<form action="<?= $this->url->href('TaskUserController', 'save', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>" method="post" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('task_id', array('task_id' => $task['id'])) ?>
    <?= $this->form->hidden('owner_id', array('owner_id' => $task['owner_id'])) ?>

    <?= $this->form->label(t('User'), 'user_id') ?>
    <?= $this->form->select('user_id', $users_list, $values, $errors) ?>
    
    <?= $this->modal->submitButtons() ?>
</form>
