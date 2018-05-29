<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-xs-12 col-sm-8">
<div class="card">
    <div class="card-header bgm-cyan">
            <h2 class="text-center"><?php if(strpos(current_url(), 'add')) {echo lang('box_title_create');} else{echo lang('box_title_edit');} ?></h2>
        </div>
        
        <div class="card-body card-padding">
                <?php echo form_open_multipart(current_url(), array('class' => 'form-horizontal', 'id' => 'form-create_user')); ?>
                    <div class="form-group">
                        <label  for="username" class="col-sm-3 control-label"><?php echo lang('username').'  <span class="c-red">*</span>'; ?></label>
                        <div class="col-sm-8">
                            <div class="fg-line">
                                <?php echo form_input($username);?>
                                <?php echo form_error('username');?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="first_name" class="col-sm-3 control-label"><?php echo lang('first_name').'<span class="c-red">*</span> '; ?></label>
                        <div class="col-sm-8">
                            <div class="fg-line">
                                <?php echo form_input($first_name);?>
                                <?php echo form_error('first_name');?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="gioitinh" class="col-sm-3 control-label"><?php echo lang('gioitinh'); ?></label>
                        <div class="col-sm-8" style="margin-top: 7px">
                                <label class="radio radio-inline m-r-20" style="padding-top: 0px">
                                    <input type="radio" name="gioitinh" <?php if (isset($gioitinh) && $gioitinh=="Nam") echo "checked";?> value="Nam">
                                    <i class="input-helper"></i> Nam 
                                </label>
                                <label class="radio radio-inline m-r-20" style="padding-top: 0px">
                                    <input type="radio" name="gioitinh" <?php if (isset($gioitinh) && $gioitinh=="Nữ") echo "checked";?> value="Nữ">
                                    <i class="input-helper"></i>  Nữ
                                </label>
                                <label class="radio radio-inline m-r-20" style="padding-top: 0px">
                                    <input type="radio" name="gioitinh" <?php if (isset($gioitinh) && $gioitinh=="Khác") echo "checked";?> value="Khác">
                                    <i class="input-helper"></i>  Khác
                                </label>
                                    <?php echo form_error('gioitinh');?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="sinhnhat" class="col-sm-3 control-label"><?php echo lang('sinhnhat').' '; ?></label>
                            <div class="col-sm-8">
                                <div class="fg-line">
                                <?php echo form_input($sinhnhat);?>
                                <?php echo form_error('sinhnhat');?>
                                </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="email" class="col-sm-3 control-label"><?php echo lang('email').'<span class="c-red">*</span> '; ?></label>
                            <div class="col-sm-8">
                                <div class="fg-line">
                                <?php echo form_input($email);?>
                                <?php echo form_error('email');?>
                                </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="phone" class="col-sm-3 control-label"><?php echo lang('phone').' '; ?></label>
                            <div class="col-sm-8">
                                <div class="fg-line">
                                    <?php echo form_input($phone);?>
                                    <?php echo form_error('phone');?>
                                    <?php echo form_error('phone');?>
                                </div>
                            </div>
                        </div>

                    <div class="form-group">
                        <label  for="password" class="col-sm-3 control-label"><?php echo lang('password').'  <span class="c-red">*</span>'; ?></label>
                             <div class="col-sm-8">
                                <div class="fg-line">
                                    <?php echo form_input($password);?>
                                    <?php echo form_error('password');?>
                                    <div class="progress" style="margin:0">
                                        <div class="pwstrength_viewport_progress"></div>
                                    </div>
                               </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="password_confirm" class="col-sm-3 control-label"><?php echo lang('password_confirm').'  <span class="c-red">*</span>'; ?></label>
                            <div class="col-sm-8">
                                <div class="fg-line">
                                    <?php echo form_input($password_confirm);?>
                                    <?php echo form_error('password_confirm');?>
                                    </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="phanquyen" class="col-sm-3 control-label">Phân quyền</label>
        <?php foreach ($get_module  as $key => $val): 
                if(isset($val['sub']) AND $val['sub']) : ?>
                        <div class="col-sm-offset-3 col-sm-9" id="phanquyen" style="margin-top: 7px">
                            <div class="col-sm-4">
                                <span ><?php echo $val['menu_title'];?></span>
                            </div>
                            <div class="col-sm-8">
                                    <input value="" name="phanquyen[]" type="hidden" id="parent_<?php echo $val['menu_id']; ?>">
                            </div>                       
                         </div>
                         <div class="sub-menu" data-id="parent_<?php echo $val['menu_id']; ?>">
                            <?php foreach ($sub as $k => $v) :
                                if($k == $val['menu_id']) :
                                    foreach ($v as $k1 => $v1) :
                                    ?>
                                <div class="col-sm-offset-3 col-sm-9 " style="margin-top: 7px" >
                                    <div class="col-sm-4">
                                        <span><?php echo $v1['menu_title'];?></span>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                            <input type="checkbox" value="<?php echo $v1['menu_id'];?>_0" name="phanquyen[]">
                                            <i class="input-helper"></i>    
                                             Menu
                                        </label>

                                        <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                            <input type="checkbox" value="<?php echo $v1['menu_id'];?>_1" name="phanquyen[]">
                                            <i class="input-helper"></i>    
                                            Xem
                                        </label>

                                        <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                            <input type="checkbox" value="<?php echo $v1['menu_id'];?>_2" name="phanquyen[]">
                                            <i class="input-helper"></i>    
                                            Thêm
                                        </label>

                                        <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                            <input type="checkbox" value="<?php echo $v1['menu_id'];?>_3" name="phanquyen[]">
                                            <i class="input-helper"></i>    
                                            Sửa
                                        </label>
                                        <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                            <input type="checkbox" value="<?php echo $v1['menu_id'];?>_4" name="phanquyen[]">
                                            <i class="input-helper"></i>    
                                            Xóa
                                        </label>
                                    </div>
                                </div>
                            <?php 
                            endforeach;
                            endif; 
                        endforeach; ?>
                    </div>
            <?php else :?>
                        <div class="col-sm-offset-3 col-sm-9" id="phanquyen" style="margin-top: 7px">
                            <div class="col-sm-4">
                                <span><?php echo $val['menu_title'];?></span>
                            </div>
                            <div class="col-sm-8">
                                <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                    <input type="checkbox" value="<?php echo $val['menu_id'];?>_0" name="phanquyen[]">
                                    <i class="input-helper"></i>    
                                     Menu
                                </label>

                                <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                    <input type="checkbox" value="<?php echo $val['menu_id'];?>_1" name="phanquyen[]">
                                    <i class="input-helper"></i>    
                                    Xem
                                </label>

                                <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                    <input type="checkbox" value="<?php echo $val['menu_id'];?>_2" name="phanquyen[]">
                                    <i class="input-helper"></i>    
                                    Thêm
                                </label>

                                <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                    <input type="checkbox" value="<?php echo $val['menu_id'];?>_3" name="phanquyen[]">
                                    <i class="input-helper"></i>    
                                    Sửa
                                </label>
                                <label class="checkbox checkbox-inline " style="padding-top: 0px">
                                    <input type="checkbox" value="<?php echo $val['menu_id'];?>_4" name="phanquyen[]">
                                    <i class="input-helper"></i>    
                                    Xóa
                                </label>
                            </div>
                        </div>
                <?php endif;?>
                <?php endforeach; ?>

                    </div>

                    <label  for="avatar" class="col-sm-3 control-label"><?php echo lang('avatar').' '; ?></label>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                        </div>
                        <div>
                            <span class="btn btn-info btn-file waves-effect waves-button waves-float">
                                <span class="fileinput-new">Chọn ảnh</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" id="avatar" name="avatar">
                            </span>
                        </div>
                    </div>
                  
                    

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <a href="<?php echo site_url('hethong/users'); ?>" class="btn btn-primary btn-sm" ><?php echo lang('actions_back');?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo lang('actions_save');?></button>
                        </div>
                    </div>
                </div>
    <?php echo form_close(); ?>  
</div>
<div class="col-sm-2"></div>
</div>