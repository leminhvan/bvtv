<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-xs-12 col-sm-6">
        <div class="card">
            <div class="card-header bgm-cyan" style="padding: 10px;">
                <h2 class="text-center">KẾT QUẢ CHUẨN ĐỘ</h2>
            </div>
          
            <div class="card-body card-padding">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step">
                            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                            <p>Phương pháp</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-2" id="step-hoachat" type="button" class="btn btn-default btn-circle " >2</a>
                            <p>Hóa chất</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-3" id="step-ketqua" type="button" class="btn btn-default btn-circle " >3</a>
                            <p>Tính kết quả</p>
                        </div>
                    </div>
                </div><!-- end stepwizard -->
        <?php $hidden = array('mau_id' => $mau_id); 
                    echo form_open(site_url($action), 'id="form_bvtv_ketqua" class="form-horizontal" role="form"', $hidden); ?>
                    <div class="row setup-content" id="step-1">
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                Phương pháp thử: <?php    echo $mau_pp;    ?>
                                
                            </div>
                        </div>
                        <div class="col-sm-12 text-center" style="padding: 10px;">
                            <a href="<?php echo site_url('mau/bvtvmau'); ?>" class="btn btn-primary btn-sm" >Quay về trang mẫu</a>
                            <button class="btn btn-primary btn-sm nextBtn" type="button" >Bước tiếp theo</button>
                        </div>
                    </div>
                    <div class="row setup-content" id="step-2">
                        <div class="col-md-12">

                            <?php                  
                              var_dump( $pp_hoachat);             
                            ?>
                        </div>
                        <div class="col-sm-12 text-center" style="padding: 10px;">
                            <a href="<?php echo site_url('mau/bvtvmau'); ?>" class="btn btn-primary btn-sm" >Quay về trang mẫu</a>
                            <button class="btn btn-primary btn-sm nextBtn" type="button" >Bước tiếp theo</button>
                        </div>
                    </div>
                    <div class="row setup-content" id="step-3">
                        <div class="col-sm-12">
                            
                           
                            <div class="form-group">
                                <input type="text" name="" placeholder="KL mẫu">
                                <input type="text" name="" placeholder="V Reagent">
                                <input type="text" name="" placeholder="C Reagent">
                            </div>
                          <hr>
                            <div class="col-sm-12 text-center" id="kq"></div>
                            
                        </div>


                        <div class="col-sm-12 text-center" style="padding: 10px;">
                            <a href="<?php echo site_url('mau/bvtvmau'); ?>" class="btn btn-primary btn-sm" >Quay về trang mẫu</a>
                            <button class="btn btn-primary btn-sm" type="submit">Lưu lại thao tác</button>
                        </div>
                    </div>
        <?php echo form_close(); ?>  
                
            </div>
        </div>
    </div>
    <div class="col-sm-3"></div>
</div>

