<section class="accordion-section <?= empty($taskusers) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3><a href="#" class="fa accordion-toggle"></a> <?= t('Assigned Users') ?></h3>
    </div>
    <div class="accordion-content">
        <?= $this->render('task_user/listing', array(
            'taskusers' => $taskusers,
            'task' => $task,
            'project' => $project,
            'editable' => $editable,
            'is_public' => $is_public,
        )) ?>

    </div>
</section>
