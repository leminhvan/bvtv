<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo lang('head_title_users'); ?>
    </h1>
   
</section>

<section class="content">
                    <!--In thong bao cho action -->
      <?php 
          echo $this->session->flashdata('notify');
      ?>
      <!-- start box-->
        <div class="box">
          <div class="box-header with-border">
                <h3 class="box-title"><?php echo sprintf(lang('users_deactive'), '<span class="label label-primary">'.$username).'</span>'; ?> </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="<?php echo lang('actions_collapse'); ?> ">
                        <i class="fa fa-minus"></i>
                      </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="<?php echo lang('actions_delete'); ?>">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

          <div class="box-body">
                    <?php echo form_open('users/deactivate/'. $id, array('class' => 'form-horizontal', 'id' => 'form-status_user')); ?>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <label class="radio-inline">
                                    <input type="radio" name="confirm" id="confirm1" value="yes" checked="checked"> <?php echo strtoupper(lang('actions_yes', 'confirm')); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="confirm" id="confirm0" value="no"> <?php echo strtoupper(lang('actions_no', 'confirm')); ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?php echo form_hidden($csrf); ?>
                                <?php echo form_hidden(array('id'=>$id)); ?>
                                <div class="btn-group">
                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                    <?php echo anchor('users', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close();?>
        </div><!--end box body -->
  </div><!--end box -->
</section>

