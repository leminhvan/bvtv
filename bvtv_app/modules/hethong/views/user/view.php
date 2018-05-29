
<div class="card">
    <div class="card-header bgm-cyan">
        <h2 class="text-center"><?php echo lang('head_title_groups'); ?></h2>

        <!--action menu -->
        <ul class="actions actions-alt">
            <li data-toggle="tooltip" data-placement="left" title="Thêm mới"><a href="<?php echo site_url('hethong/users/add'); ?>"><i class="md md-my-library-add"></i></a> </li>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown"><i class="md md-more-vert"></i></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#">Refresh</a></li>
                </ul>
            </li>
        </ul>
        <!--end action menu -->
    </div><!--end card heder -->
    
    <div class="card-body card-padding table-responsive" style="overflow: hidden, outline: none;">
        <?php if ($users) : ?>
        <!--Table-->
        <table class="table display compact" id="tabledata"  >
            <!--Table head-->
            <thead>
                <tr>
                    <th width="5">
                        <input type="checkbox" id="select_all-menu">
                    </th>
                        <th><?php echo lang('username');?></th>
                        <th><?php echo lang('first_name');?></th>
                        <th><?php echo lang('last_name');?></th>
                        <th><?php echo lang('email');?></th>
                        <th><?php echo lang('groups');?></th>
                        <th><?php echo lang('active');?></th>
                        <th class="red header" align="right" width="120"><?php echo lang('action_title'); ?></th>
                    </tr>
                </thead>
                <tbody>
            <?php $i=0; foreach ($users as $user): $i++;?>
                    <tr id="data_id_<?php echo $user->id; ?>">
                        <th width="5">
                            <input type="checkbox" id="checkbox<?php echo $i;?>">
                            <label for="checkbox<?php echo $i;?>" class="label-table"></label>
                        </th>
                        <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
            <?php foreach ($user->groups as $group):?>
                            <?php echo anchor('hethong/groups/edit/'.$group->id, '<span class="label" style="background:'.$group->bgcolor.';">'.htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8').'</span>'); ?>
            <?php endforeach?>
                        </td>
                        <td><?php echo ($user->active) ? '<span class="label label-success status" data-id="'.$user->id.'_'.$user->active.'">'.lang('users_active').'</span>' : '<span class="label label-default status" data-id="'.$user->id.'_'.$user->active.'">'.lang('users_inactive').'</span>'; ?></td>
                    
                    <td class="text-right" width="50">
                        <span class="detail" data-id="<?php echo $user->id; ?>" ><a href="" data-toggle="modal" data-target="#<?php echo $user->id; ?>" ><i class=" md-report"></i></a></span>
                        <span ><a href="<?php echo site_url('hethong/users/edit/').$user->id; ?>" ><i class=" md-create" ></i></a></span>
                        <span class="xoa" data-id="<?php echo $user->id; ?>" ><a href="#" ><i class=" md-remove-circle"></i></a></span>
                    </td>

                    </tr>
            <?php endforeach;?>
                </tbody>
            </table>
            <!--Table-->
        <?php else: ?>
            <?php  echo notify('Không có data','info');?>
        <?php endif; ?>
    </div>
</div>

<!--Modal: form-->
    <div class="modal fade" id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog cascading-modal" role="document">
            <!--Content-->
            <div class="modal-content">
                <div class="list-group z-depth-1">
                    <h3 class="list-group-item active text-center"> Thông tin</h3>
                    <table class="table result" >
                       
                    </table>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
<!--Modal: form-->