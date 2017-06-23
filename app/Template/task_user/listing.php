<?php if (! empty($taskusers)): ?>
<table class="table-striped table-scrolling">
    <?php foreach ($taskusers as $taskuser): ?>
        <tr>
            <td>
                <?php if ($editable && $this->user->hasProjectAccess('TaskUser', 'create', $task['project_id'])): ?>
                    <div class="dropdown">
                        <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog"></i><i class="fa fa-caret-down"></i></a>
                        <ul>
                            <li>
                                <?= $this->modal->confirm('trash-o', t('Remove'), 'TaskUserController', 'confirm', array('user_id' => $taskuser['user_id'], 'task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
                            </li>
                        </ul>
                    </div>
                <?php endif ?>
                <!-- <?= $this->avatar->render($taskuser['user_id'], $taskuser['username'], $taskuser['name'], $taskuser['email'], $taskuser['avatar_path']) ?> -->
                <?= $this->text->e($taskuser['name'] ?: $taskuser['username']) ?>
            </td>
        </tr>    
    <?php endforeach ?>
</table>
<?php endif ?>
