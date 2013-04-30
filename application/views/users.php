<?php
// Load Menu
$this->template->menu('users');
?>
 
<div id="container">
    <?php if(isset($users)) { ?>
    <table id="users_table">
        <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Level</th>
        <th>Date Created</th>
        <th>Actions</th>
        </tr>
    <?php foreach ($users as $user) { ?>
        <tr id="user_<?php echo $user['id']; ?>">
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $level_list[$user['level']]; ?></td>
        <td><?php echo date("j/M/Y, g:i a", strtotime($user['date_created'])); ?></td>
        <td>
            <?php echo anchor('user/edit/'.$user['id'], '<img src="images/edit.png" title="Edit User"/>'); ?>
            <?php echo anchor('user/remove/'.$user['id'], '<img src="images/remove.png" title="Remove User"/>', 'class="remove-user-event"'); ?>
        </td>
        </tr>
    <?php } ?>
    </div>
    <?php } ?>
</div>