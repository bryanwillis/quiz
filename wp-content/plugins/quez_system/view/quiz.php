<?php
echo "<center><h1>QUIZ</h1></center>";

class user_quiz{
    function my_quiz_table(){
        ?>
    <div class="wrap">
         <table class="wp-list-table widefat fixed striped posts">
             
            <tr>
                <th><b>ID</b></th>
                <th><b>Questions</b></th>
                <th><b>Answers</b></th>
                <th><b>Results</b></th>
                <th><b>User</b></th>
            </tr>
            
            
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "questions";
            $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
            $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
            
            foreach ($get_data as $data){
                ?>
            <tr>
                <th><?php echo $data->id; ?></th>
                <th><?php echo $data->name;  ?></th>
                <th></th>
                <th>4</th>
                <th>5</th>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
      <?php
    }
}
$quiz_obj = new user_quiz();
$quiz_obj->my_quiz_table();

