<div class="doctor-right">
    <div class="doctor-about block-back doctor-data mobile-xs mobile-education-doctor-xs">
        <div class="row">
            <div class="about-top">
                <div class="col-md-12">
                    <h5>Təhsil</h5>
                    <p><?= $data['degree'][$doctor['degree']] ?></p>
                </div>
            </div>
            <hr>
            <div class="about-top">
                <div class="col-md-12">
                    <h5>Xidmətlər</h5>
                    <ul>
                        <?php
                        if(!empty($data['specialists'])) {
                            foreach ($data['specialists'] as $val) { ?>
                                <li><p><?= $val['name'] ?></p></li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>