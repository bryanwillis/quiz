<?php
global $wpdb;
if (isset($_GET['do']) && isset($_GET['id']) && $_GET['do'] == 'delete' && !empty($_GET['id'])) {
    $team_member_exist = $wpdb->get_var("SELECT COUNT(*) from wp_team as t1 LEFT JOIN wp_usermeta as t2 ON t1.team_code=t2.meta_value WHERE t2.meta_key='team_name' and t1.id=" . $_GET['id'] . "");
    if ($team_member_exist) {
        echo "<div class='notice notice-error is-dismissible'><p>You can't delete this team because this team already has members.</p></div>";
    } else {
        $wpdb->query("DELETE FROM {$wpdb->prefix}team WHERE id=" . $_GET['id'] . "");
        wp_redirect('?page=manage-team');
    }

}

?>
<div class="wrap">
	<h1>Manage Teams <a href="?page=add-team" class="page-title-action">Add new team</a></h1>
    <form action="" method="get">
         <div class="form-group">
    <label style="color: #000;">Search Team</label>
    <input type="hidden" name="page" value="manage-team">
    <input type="text" placeholder="Enter team name" name="team_name" value="<?php echo ($_GET['team_name']) ? $_GET['team_name'] : ''; ?>">
    <input type="submit" name="submit" id="submit" class="button team_search button-primary" value="Search team">
  </div>
    </form>
    <?php
$qty = 1;
if (isset($_GET['team_name']) && !empty($_GET['team_name'])) {

    $qty = "team_name LIKE '%{$_GET['team_name']}%'";
    echo '<div align="right"><a href="?page=manage-team" class="button team_search button-primary">Reset Search</a></div>';
}
?>

         <table class="wp-list-table widefat fixed striped posts">
            <tr>
                <th><b>#</b></th>
                <th><b>Team Name</b></th>
                <th><b>Team Code</b></th>
                <th><b>Action</b></th>
            </tr>

           <?php
$data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}team WHERE " . $qty . "");
foreach ($data as $key => $value) {
    echo '<tr>';
    echo '<td>' . ++$key . '</td>';
    echo '<td>' . $value->team_name . '</td>';
    echo '<td>' . $value->team_code . '</td>';
    echo '<td><a href="?page=add-team&do=edit&id=' . $value->id . '">Edit</a> | <a href="?page=manage-team&do=delete&id=' . $value->id . '">Delete</a> | <a href="?page=view-team-members&team_code=' . $value->team_code . '">View team members</a> | <a href="' . home_url('/team-reports?team_code=' . $value->team_code) . '" target="_blank">Team reports</a></td>';
    echo '</tr>';
}

?>

         </table>

     </div>