<?php
class viewTeamMembers {
    public function __construct() {
        $this->team_member_table();
    }

    public function team_member_table() {
        global $wpdb;
        $user_quiz_data = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}user_quiz", ARRAY_A);
        $user_ids       = array_unique(array_column($user_quiz_data, 'user_id'));
        echo '<div class="wrap">
    <h1>' . esc_html(get_admin_page_title()) . '</h1>
         <table class="wp-list-table widefat fixed striped posts">
            <tr>
                <th><b>#</b></th>
                <th><b>Name</b></th>
                <th><b>Assessment Status</b></th>
                <th><b>Action</b></th>
            </tr>';

        if (isset($_GET['team_code'])) {
            $users = get_users(array(
                'meta_key'   => 'team_name',
                'meta_value' => $_GET['team_code'],
            ));
            if ($users) {
                foreach ($users as $key => $value) {

                    echo '<tr>';
                    echo '<td>' . ++$key . '</td>';
                    echo '<td>' . $value->display_name . '</td>';
                    echo '<td>';
                    echo in_array($value->ID, $user_ids) ? 'Yes' : 'No';
                    echo '</td>';
                    echo '<td>';
                    echo in_array($value->ID, $user_ids) ? '<a href="' . home_url('/assessment?userresult=' . $value->ID . '&report_type=team') . '" target="_blank">View Result</a>' : '<a style="color: #ccc;">View Result</a>';

                    echo '</td>';
                    echo '</tr>';
                }
            } else {

                echo '<tr><th colspan="2">No members found!</th></tr>';
            }
        }
        echo '</table></div>';

        ?>

<?php
}
}

new viewTeamMembers();
