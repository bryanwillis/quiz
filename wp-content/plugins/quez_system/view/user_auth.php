<?php
echo "<center><h1>USER AUTHENTICATION</h1></center>";

class user_authentication{
    function my_authentication_table(){
        $do = $_GET['do'];
        if($do == 'edit'){
           $this->edit();
        }else if($do == 'add'){
               $this->add();
            }else{
        ?>
    <div class="wrap">
        <a href="?page=user-authentication&do=add">Add User</a>
         <table class="wp-list-table widefat fixed striped posts">
             
            <tr>
                <th><b>ID</b></th>
                <th><b>User</b></th>
                <th><b>Status</b></th>
                <th><b>Action</b></th>
            </tr>
            
            
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "quiz_authentication";
            $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
            $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
            foreach ($get_data as $data){
                if($data->status==1){
                    $status = 'Enable';
                }else{
                    $status = 'Disable';
                }
                ?>
            <tr>
                <th><?php echo $data->id; ?></th>
                <?php
                $user_info = get_userdata($data->user_id);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;
                ?>
                <th><?php echo $first_name.' '.$last_name;  ?></th>
                <th><?php echo $status; ?></th>
                <th>
                    <a href="?page=user-authentication&do=edit&id=<?php echo $data->id ?>">Edit</a> | <a href="/assessment?userresult=<?php echo $data->user_id ?>" target="_blank">View Result</a>
                </th>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
      <?php
    }
    }
    
    public function edit(){
           if(isset($_GET['id'])){
           $id = $_GET['id'];
           }
        
           global $wpdb;
           $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
           $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_quiz_authentication WHERE id = %d", $id));
           
           
            if(isset($_POST['submit'])){
                $status = $_POST['status'];
                $wpdb->update(
                $table_quiz_authentication, 
                   array( 
                        'status'=> $status
               ),
                array( 'id' => $id )
             ); 
                
                $user_info = get_userdata($row->user_id);
                $name = $user_info->first_name.' '.$user_info->last_name;
                $this->z_send_email($name, $user_info->user_email);
             ?>
            <script type="text/javascript">
                window.location.href = "?page=user-authentication";
            </script>
            <?php
           }
    ?>
        <div id="quiz_system" style="position: relative; margin: 0">
            <form method="post" id="my_qzquestions">
                <div id="quesn_result"></div>
                <div class="question_name">
                    <select id="tr_fls" name="status">
                        <option value="1" <?php if($rowan->status==1){ echo 'selected'; } ?>>Enable</option>
                        <option value="0" <?php if($rowan->status==0){ echo 'selected'; } ?>>Disable</option>
                    </select>
                </div>
                <input type="submit" name="submit" value="submit" class="button button-primary button-large">
            </form>
        </div>
    
    
        <?php
        }
        public function add(){
      
       if(isset($_POST['submit'])){
            global $wpdb;
            $user_id = $_POST['user_id'];
            $status = $_POST['status'];
            $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
            $check_row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = $user_id");
            if($check_row){
                    $wpdb->update(
                $table_quiz_authentication, 
                   array( 
                        'status'=> $status
               ),
                array( 'id' => $check_row->id )
             ); 
            }else{
                    $wpdb->insert(
                    $table_quiz_authentication, 
                    array( 
                        'user_id' => $user_id,
                        'status'=> $status
                    )
                );
            }
            
            $user_info = get_userdata($user_id);
            $name = $user_info->first_name.' '.$user_info->last_name;
            $this->z_send_email($name, $user_info->user_email);
              ?>
            <script type="text/javascript">
               window.location.href = "?page=user-authentication";
            </script>
        <?php
            echo "<b> User \"{$user_id}\" - is successfully added</b>";
       }
       ?>
    <div id="quiz_system" style="position: relative; margin: 0">
            <form method="post" id="my_qzquestions">
                <div id="quesn_result"></div>
                
                <div class="question_name">
                    <select id="user_id" name="user_id">
                        <?php
                        $blogusers = get_users();
                        // Array of WP_User objects.
                        foreach ( $blogusers as $user ) {
                            ?>
                        <option value="<?php echo $user->ID; ?>"><?php echo $user->user_email; ?></option>
                        <?php
                        }
                         ?>
                        
                    </select>
                </div>
                <div class="question_name">
                    <select id="tr_fls" name="status">
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
                <input type="submit" name="submit" value="submit" class="button button-primary button-large">
            </form>
        </div>
    <?php
   }
   
   public function z_send_email($name, $email) {
		$to = $email;
		$headers = array('Content-Type: text/html; charset=UTF-8');
                $template= '<html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title></title>
                    </head>
                    <body>
                        <div style="background: #f2f2f2; padding: 40px 0">
                        <div style="margin: 0 auto; width: 500px; padding: 20px; background-color: #fff;">
                        <h1 style="border-bottom: 2px solid #ccc; padding: 10px 0; font-size: 18px; text-align:center;">ICBI Request</h1>
                            <p>Dear '.$name.',</p>
                            <p>Your request to complete the ICBI has been approved. Please <a href="http://assessment.globalcoachcenter.com/assessment/">click here</a> to complete your assessment</p>
                            <p>If you have any questions, please contact us at <a href="mailto:info@globalcoachcenter.com">info@globalcoachcenter.com</a>.</p>
                            <p style="margin-top: 20px; border-top: 2px solid #ccc; padding: 10px 0;">Regards,</br>The Global Coach Center</p>
                        </div>
                        </div>
                    </body>
                </html>';
		if( wp_mail($to, 'Individual Culture Blueprint Indicator Results', $template, $headers) ) {
			return true;
		} else {
			return false;
		}
	}
}
$quiz_obj = new user_authentication();
$quiz_obj->my_authentication_table();

