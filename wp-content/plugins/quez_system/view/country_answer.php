<?php
echo "<center><h1>Country Answer</h1></center>";

class countryAnswers{
    function my_authentication_table(){
        $do = $_GET['do'];
        if($do == 'edit'){
           $this->edit();
        }else if($do == 'add'){
               $this->add();
            }elseif ($do=='delete'){
                    $this->delete();
            }else{
        ?>
    <div class="wrap">
        <a href="?page=country-answer&do=add">Add Answer</a>
         <table class="wp-list-table widefat fixed striped posts">
             
            <tr>
                <th><b>ID</b></th>
                <th>Country Name</th>
                <th><b>Question</b></th>
                <th><b>Veriable</b></th>
                <th><b>Total</b></th>
                <th><b>Action</b></th>
            </tr>
            
            
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "country_answer";
            $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
            $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY question_id DESC" );
            foreach ($get_data as $data){
                ?>
            <tr>
                <th><?php echo $data->id; ?></th>
                <th><?php echo $data->country_name; ?></th>
                <?php
                global $wpdb;
                $table_questions = $wpdb->prefix . "questions";
                $question = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $data->question_id));

                ?>
                <th><?php echo $question->name;  ?></th>
                <th><?php echo $question->veriable;  ?></th>
                <th><?php echo $data->total; ?></th>
                <th>
                    <a href="?page=country-answer&do=edit&id=<?php echo $data->id ?>">Edit</a> | 
                    <a name="delete" href="?page=country-answer&do=delete&id=<?php echo $data->id ?>">Delete</a>
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
           $table_quiz_authentication = $wpdb->prefix . "country_answer";
           $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_quiz_authentication WHERE id = %d", $id));
           
           
            if(isset($_POST['submit'])){
                $status = $_POST['total'];
                $country_name = $_POST['country_name'];
                $wpdb->update(
                $table_quiz_authentication, 
                   array( 
                        'total'=> $status,
                       'country_name'=> $country_name
               ),
                array( 'id' => $id )
             ); 
             ?>
            <script type="text/javascript">
                window.location.href = "?page=country-answer";
            </script>
            <?php
           }
    ?>
        <form method="post" id="my_qzquestions"> 
            <div class="question_name">
                <input type="text" name="total" value="<?php echo $row->total; ?>">
            </div>
            <div class="question_name">
                <input type="text" name="country_name" value="<?php echo $row->country_name; ?>">
            </div>
            <input type="submit" name="submit" value="submit" class="button button-primary button-large">
        </form>
    
    
        <?php
        }
        public function add(){
      
       if(isset($_POST['submit'])){
            global $wpdb;
            $question_id = $_POST['question_id'];
            $total = $_POST['total'];
            $country_name = $_POST['country_name'];
            $table_country_answer = $wpdb->prefix . "country_answer";
            
            $wpdb->insert(
                $table_country_answer, 
                array( 
                    'question_id' => $question_id,
                    'total'=> $total,
                    'country_name'=> $country_name
                )
            );
            
              ?>
            <script type="text/javascript">
               window.location.href = "?page=country-answer";
            </script>
        <?php
            echo "<b> User \"{$question_id}\" - is successfully added</b>";
       }
       ?>
    <div id="quiz_system" style="position: relative; margin: 0">
            <form method="post" id="my_qzquestions">
                <div id="quesn_result"></div>
                
                <div class="question_name">
                    <select id="user_id" name="question_id">
                        <?php
                        global $wpdb;
                        $table_name = $wpdb->prefix . "questions";
                        $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
                        $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
                        foreach ($get_data as $data){
                            ?>
                        <option value="<?php echo $data->id; ?>"><?php echo $data->veriable; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="question_name">
                    <input type="text" name="country_name" placeholder="Country Name">
                </div>
                <div class="question_name">
                    <input type="text" name="total"  placeholder="Total">
                </div>
                <input type="submit" name="submit" value="submit" class="button button-primary button-large">
            </form>
        </div>
    <?php
   }
   
   public function delete(){
            if(isset($_GET['id'])){
              $id = $_GET['id'];
            }
            global $wpdb;
                $table_questions = $wpdb->prefix . "country_answer";
                $wpdb->delete( $table_questions, array( 'id' => $id ), array( '%d' ) );
                ?>
            <script type="text/javascript">
                window.location.href = "?page=country-answer";
            </script>
         <?php
    }
}
$quiz_obj = new countryAnswers();
$quiz_obj->my_authentication_table();

