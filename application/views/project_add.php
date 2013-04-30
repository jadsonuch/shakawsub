<?php
// Load Menu
if (isset($id))
    $this->template->menu('return_to_tasks');
else
    $this->template->menu('projects');
?>
 
<div id="container">
 
    <?php echo form_open('project/save'); ?>
 
    <table>
        <tr>
            <td>
                <?php echo form_label('Name', 'name'); ?>
            </td>
            <td>
                <?php echo form_input('name', $name); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo form_label('Description', 'description'); ?>
            </td>
            <td>
                <?php
                $data = array('name'        => 'description',
                              'id'          => 'description',
                              'value'       => $description,
                              'rows'        => '6',
                              'cols'        => '80');
 
                echo form_textarea($data); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="project-users blue-gradient">
                    Associated Users
                    <span class="show-hide-event expand" target-id="associated-users"></span>
                </div>
            </td>
        </tr>
        <tbody id="associated-users">
        <tr>
            <td colspan="2">
            <?php foreach ($users as $user) { ?>
            <div class="half-width">
                <label>
                <?php echo form_checkbox('users[]', $user['id'], ($user['project'])?1:0); ?>
                <?php echo $user['email']; ?></label>
            </div>
            <?php } ?>
            </td>
        </tr>
        </tbody>
        <tr>
                <td colspan="2">
                    <?php if (isset($id)) echo form_hidden('id', $id); ?>
 
                    <div class="form-save-buttons">
                        <?php echo form_submit('save', 'Save', 'class="btn-blue"'); ?>
                        <?php echo form_button('cancel', 'Cancel', 'class="btn-blue" onClick="history.go(-1)"');; ?>
                    </div>
                </td>
        </tr>
    </table>
 
    <?php echo form_close(); ?>
 
</div>