
<div class="card">
    <div class="card-header bgm-cyan">
        <h2 class="text-center"><?php if(strpos(current_url(), 'hethan')) {echo lang('head_title_chuangoc_hethan');} else{echo lang('head_title_chuangoc');} ?></h2>

        <!--action menu -->
        <ul class="actions actions-alt">
            <?php if(!strpos(current_url(), 'hethan')) :?>
            <li data-toggle="tooltip" data-placement="left" title="Thêm mới"><a href="<?php echo site_url('hoachat/chuangoc/add'); ?>"><i class="md md-my-library-add"></i></a> </li>
            <?php endif;?>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown"><i class="md md-more-vert"></i></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="<?php echo base_url('hoachat/chuangoc/excel')?>">Lưu file Excel</a></li>
                </ul>
            </li>
        </ul>
        <!--end action menu -->
    </div><!--end card heder -->
    
    <div class="card-body card-padding table-responsive" style="padding-left: 20px;padding-right: 20px;padding-bottom: 20px;">
         <?php if ($chuangocs) : ?>
        <!--Table-->
        <table class="display compact" id="tabledata"  >
            <!--Table head-->
            <thead>
                <tr>
                    <th  width="5">
                        <input type="checkbox" id="select_all-menu">
                    </th>
                
                    <th><?php echo lang('hcgoc_name');?></th>   
                
                    <th><?php echo lang('hcgoc_vicb_code');?></th>   
                
                    <th><?php echo lang('hcgoc_nhasx');?></th>   
                
                    <th><?php echo lang('hcgoc_code');?></th>   
                
                    <th><?php echo lang('hcgoc_lot');?></th>   
                
                    <th><?php echo lang('hcgoc_hamluong');?></th>   
                
                    <th><?php echo lang('hcgoc_luongnhap');?></th>   
                
                    <th><?php echo lang('hcgoc_expday');?></th>   
                
                    <th><?php echo lang('hcgoc_ngaynhap');?></th>   
                
                    <th><?php echo lang('hcgoc_baoquan');?></th> 
                    <?php if(strpos(current_url(), 'saphethan')): ?> 
                        <th><?php echo lang('hcgoc_dathang');?></th>
                    <?php endif; ?>
                    <th><?php echo lang('hcgoc_lab');?></th>
                    <th class="text-center" width="50">Actions</th>
                </tr>
            </thead>
            <!--Table head-->

            <!--Table body-->
            <tbody>
              <?php $i=0; foreach ($chuangocs as $chuangoc) : $i++;?>
                <tr id="data_id_<?php echo $chuangoc['hcgoc_id']; ?>">
                    <th scope="row" width="5">
                        <input type="checkbox" id="checkbox<?php echo $i;?>">
                        <label for="checkbox<?php echo $i;?>" class="label-table"></label>
                    </th>
                    
                    <td><?php echo $chuangoc['hcgoc_name']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_vicb_code']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_nhasx']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_code']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_lot']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_hamluong']; ?></td>
               
               <?php $donvi = explode('_', $chuangoc['hcgoc_luongnhap']); ?>
               <td><?php echo $donvi[0].' '.$donvi[1]; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_expday']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_ngaynhap']; ?></td>
               
               <td><?php echo $chuangoc['hcgoc_baoquan']; ?></td>
        <?php if(strpos(current_url(), 'saphethan')): ?> 
                <td>
                    <div class="toggle-switch " data-ts-color="green">
                        <input class="dathang" id="dathang<?php echo $chuangoc['hcgoc_id']; ?>" data-id-dathang="<?php echo $chuangoc['hcgoc_id']; ?>" name="hcgoc_dathang" type="checkbox" <?php if($chuangoc['hcgoc_dathang'] ==1 ){ echo "checked";} ?> hidden="hidden">
                        <label for="dathang<?php echo $chuangoc['hcgoc_id']; ?>" class="ts-helper"></label>
                    </div>
                </td>
        <?php endif; ?>
                <td><?php echo $chuangoc['hcgoc_lab']; ?></td>
                    <td class="text-right" width="50">
                        <span class="detail" data-id="<?php echo $chuangoc['hcgoc_id']; ?>" ><a href="" data-toggle="modal" data-target="#<?php echo $chuangoc['hcgoc_id']; ?>" ><i class=" md-report "></i></a></span>
                        <span >
                            <a href="<?php
                            if(strpos(current_url(), 'saphethan')){
                                 echo site_url('hoachat/chuangoc/edit/').$chuangoc['hcgoc_id'].'/true'; 
                             } else{
                                echo site_url('hoachat/chuangoc/edit/').$chuangoc['hcgoc_id'];
                             }
                             ?>" >
                                <i class=" md-create" ></i>
                            </a>
                        </span>
                        <span class="xoa" data-id="<?php echo $chuangoc['hcgoc_id']; ?>" ><a href="#" ><i class=" md-remove-circle"></i></a></span>
                    </td>
                </tr>
               <?php endforeach; ?>
            </tbody>
            <!--Table body-->
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

 
