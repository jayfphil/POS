 <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php 

                            $doc_id=0;
                            if(isset($_REQUEST['doc_id']) && is_numeric($_REQUEST['doc_id'])) {
                                $doc_id=$_REQUEST['doc_id'];
                                $result_documentlabel = $GLOBALS['mysqli']->query("SELECT `td_documentname` FROM `tb_documents` WHERE `td_id`=$doc_id ");

                                if(mysqli_num_rows($result_documentlabel) > 0) {
                                    $row_documentlabel = $result_documentlabel->fetch_assoc();
                                    echo "<li class='sidebar-search'>$row_documentlabel[td_documentname]</li>";
                                }
                                
                            } else {
                                echo "<li class='sidebar-search'><a href='#' class='new_document'>Create file <i class='glyphicon glyphicon-paste'></i></a></li>";
                            }

                            $result_header = $GLOBALS['mysqli']->query("SELECT `th_id`,`th_label`,`th_documentid` FROM `tb_header` WHERE `th_documentid`=$doc_id ");
                            if(mysqli_num_rows($result_header) > 0) {
                                while ($row_header = $result_header->fetch_assoc()) {

                                    $li="";
                                    $li.="<li><a href='#".$row_header['th_id']."' data-toggle='tooltip' data-html='true' title='<button type=\"button\"data-method=\"edit\" data-target=\"tb_header".$row_header['th_id']."\" class=\"open_modal btn btn-info btn-xs\"><i class=\"glyphicon glyphicon-edit\"></i> Edit General Panel</button><button type=\"button\" data-target=\"tb_header".$row_header['th_id']."\" data-method=\"add\" class=\"open_modal btn btn-info btn-xs\"><i class=\"glyphicon glyphicon-plus-sign\"></i> Add General Panel</button><button type=\"button\" data-target=\"tb_header".$row_header['th_id']."\" data-method=\"addsub\" class=\"open_modal btn btn-info btn-xs\"><i class=\"glyphicon glyphicon-plus-sign\"></i> Add Subpanel</button>'><b>".$row_header['th_label']."</b>";

                                    $li.="<input type=\"hidden\" id=\"tb_header".$row_header['th_id']."\" index=\"tb_header|th_label|tb_subheader|ts_label\" data-value=\"th_documentid:".$doc_id."-th_id:".$row_header['th_id']."-ts_headerid:".$row_header['th_id']."\" value=\"".$row_header['th_label']."\">";

                                    $result_subheader = $GLOBALS['mysqli']->query("SELECT `ts_id`,`ts_label` FROM `tb_subheader` WHERE `ts_headerid`=$row_header[th_id] ");
                                    if(mysqli_num_rows($result_subheader) > 0) {

                                        $li.="<span class='fa arrow'></span></a>";
                                        $li.= "<ul class='nav nav-second-level'>";

                                        while ($row_subheader = $result_subheader->fetch_assoc()) {

                                            $li.= "<li><a href='#".$row_subheader['ts_id']."' data-toggle='tooltip' data-html='true' title='<button type=\"button\" data-method=\"edit\" data-target=\"tb_subheader".$row_subheader['ts_id']."\" class=\"open_modal btn btn-success btn-xs\"><i class=\"glyphicon glyphicon-edit\"></i> Edit Subpanel</button><button type=\"button\" data-method=\"add\" data-target=\"tb_subheader".$row_subheader['ts_id']."\" class=\"open_modal btn btn-success btn-xs\"><i class=\"glyphicon glyphicon-plus-sign\"></i> Add Subpanel</button><button type=\"button\" data-method=\"addsub\" data-target=\"tb_subheader".$row_subheader['ts_id']."\" class=\"open_modal btn btn-success btn-xs\"><i class=\"glyphicon glyphicon-download-alt\"></i> Add Room</button>'>".$row_subheader['ts_label'];

                                            $li.="<input type=\"hidden\" id=\"tb_subheader".$row_subheader['ts_id']."\" index=\"tb_subheader|ts_label|tb_bodyheader|tb_label\" data-value=\"ts_headerid:".$row_header['th_id']."-ts_id:".$row_subheader['ts_id']."-tb_subheaderid:".$row_subheader['ts_id']."\" value=\"".$row_subheader['ts_label']."\">";

                                            $result_body = $GLOBALS['mysqli']->query("SELECT `tb_id`,`tb_body`,`tb_label` FROM `tb_bodyheader` WHERE `tb_subheaderid`=$row_subheader[ts_id] ");
                                            if(mysqli_num_rows($result_body) > 0) {

                                                $li.="<span class='fa arrow'></span></a>";
                                                $li.= "<ul class='nav nav-third-level'>";
                                                while ($row_body = $result_body->fetch_assoc()) {

                                                    $li.= "<li><a href='#".$row_body['tb_id']."' index=\"".$row_body['tb_label']."\" data-value=\"".$row_body['tb_body']."\" data-target=\"tb_bodyheader".$row_body['tb_id']."\" class='select_content' data-toggle='tooltip' data-html='true' title='<button type=\"button\" data-method=\"edit\" data-target=\"tb_bodyheader".$row_body['tb_id']."\" class=\"open_modal btn btn-warning btn-xs\"><i class=\"glyphicon glyphicon-edit\"></i> Edit Room</button><button type=\"button\" data-method=\"add\" data-target=\"tb_bodyheader".$row_body['tb_id']."\" class=\"open_modal btn btn-warning btn-xs\"><i class=\"glyphicon glyphicon-plus-sign\"></i> Add Room</button>'>".$row_body['tb_label']."</a></li>";

                                                    $li.="<input type=\"hidden\" id=\"tb_bodyheader".$row_body['tb_id']."\" index=\"tb_bodyheader|tb_label|tb_body\" data-value=\"tb_subheaderid:".$row_subheader['ts_id']."-tb_id:".$row_body['tb_id']."\" value=\"".$row_body['tb_label']."\">";
                                                    
                                                }
                                                $li.= "</ul>";

                                            } else {
                                                $li.="</a>";
                                            }
                                            $li.= "</li>";

                                        }
                                        $li.= "</ul>";

                                    } else {
                                        $li.="</a>";
                                    }
                                    $li.="</li>";

                                    echo $li;

                                } 
                            } elseif($doc_id!=0) {

                                echo "<center><button type=\"button\" data-target=\"tb_header1\" index=\"".$doc_id."-1\" data-method=\"add\" class=\"open_modal btn btn-primary navbar-btn\"><i class=\"glyphicon glyphicon-plus-sign\"></i> Add General Panel</button></center>";

                                echo "<input type=\"hidden\" id=\"tb_header1\" index=\"tb_header|th_label\" data-value=\"th_documentid:".$doc_id."-th_id:1\" >";
                            }
                            

                        ?>
                  
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>