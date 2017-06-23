<div class="tooltip">
    <table class="table-small">
    <tr>
        <th colspan="4"><?= t('Assigned Users') ?></th>
    </tr>
    <?php foreach ($taskusers as $taskuser): ?>
        <tr>
            <td>
                <?= $this->text->e($taskuser['name'] ?: $taskuser['username']) ?>
            </td>
        </tr>
    <?php endforeach ?>
    </table>
</div>
