<?php
if (isset($_GET['do']) && $_GET['do'] == 'edit') {

    echo '<h1>Edit Team</h1>';
} else {

    echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
}

class add_team {
    public function __construct() {
        $this->team_form();
        $this->save_team_form_data();
        $this->update_team_form_data();
    }

    public function team_form() {
        global $wpdb;
        $team_name    = $team_code = $team_approval = $individual_report = '';
        $update       = 0;
        $button_value = 'Add New Team';
        if (isset($_GET['code']) && $_GET['code'] == 'exist') {
            $team_name     = get_site_transient('team_name');
            $team_code     = get_site_transient('team_code');
            $team_approval = get_site_transient('team_approval');
            $individual_report = get_site_transient('individual_report');
            echo '<div class="notice notice-error is-dismissible"><p>Team code already exist please use another code</p></div>';
        }
        if (isset($_GET['code']) && $_GET['code'] == 'added') {
            echo '<div class="updated"><p>Team successfully created</p></div>';

        }
        if (isset($_GET['code']) && $_GET['code'] == 'update') {
            echo '<div class="updated"><p>Team successfully updated</p></div>';

        }
        if (isset($_GET['do']) && isset($_GET['id']) && $_GET['do'] == 'edit' && !empty($_GET['id'])) {
            $button_value = 'Update Team';
            $update       = 1;
            $data         = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}team WHERE id=" . $_GET['id'] . "");
            if (isset($data[0])) {
                $team_name     = $data[0]->team_name;
                $team_code     = $data[0]->team_code;
                $team_approval = $data[0]->team_approval;
                $individual_report = $data[0]->individual_report;
            }
        }

        ?>
<div class="wrap">
	<form action="" method="post">
	<table cellpadding="15">
		<tr><th>Team Name</th><td><input type="text" name="team_name" size="50" value="<?php echo $team_name ?>" required="required"></td></tr>
		<tr><th>Team Code</th><td><input type="text" name="team_code" size="50" value="<?php echo $team_code; ?>" <?php echo ($update == 1) ? 'readonly="readonly"' : ''; ?> required="required"></td></tr>
		<tr><th></th><td>
			<input type="checkbox" name="team_approval" value="1" id="team_approval" <?php echo ($team_approval) ? 'checked="checked"' : ''; ?>>
			<label for="team_approval"> No approval needed for this team</label>
		</td></tr>

        <tr><th></th><td>
            <input type="checkbox" name="individual_report" value="1" id="individual_report" <?php echo ($individual_report) ? 'checked="checked"' : ''; ?>>
            <label for="individual_report"> Individual member reports for this team</label>
        </td></tr>
		<tr><th></th><td><?php submit_button(__($button_value, 'textdomain'), 'team_submit button-primary');?></td></tr>
	</table>
	<?php
if ($update == 1) {
            wp_nonce_field('update_new_team', 'update_new_team');
        } else {
            wp_nonce_field('save_new_team', 'add_new_team');
        }

        ?>
</form>
	</div>
<?php
}
    public function save_team_form_data() {
        if (isset($_POST['add_new_team']) || wp_verify_nonce($_POST['add_new_team'], 'save_new_team')
        ) {
            global $wpdb;
            $exist_team = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}team WHERE team_code='" . $_POST['team_code'] . "'");
            if ($exist_team) {
                set_site_transient('team_code', $_POST['team_code'], 86400);
                set_site_transient('team_name', $_POST['team_name'], 86400);
                set_site_transient('team_approval', $_POST['team_approval'], 86400);
                set_site_transient('individual_report', $_POST['individual_report'], 86400);
                wp_redirect('?page=add-team&code=exist');
            } else {
                $_POST['team_approval'] = ($_POST['team_approval']) ? $_POST['team_approval'] : 0;
                $_POST['individual_report'] = ($_POST['individual_report']) ? $_POST['individual_report'] : 0;
                $wpdb->query("INSERT INTO {$wpdb->prefix}team (team_name,team_code,team_approval,individual_report)VALUES('" . $_POST['team_name'] . "','" . $_POST['team_code'] . "'," . $_POST['team_approval'] . "," . $_POST['individual_report'] . ")");
                delete_site_transient('team_name');
                delete_site_transient('team_code');
                delete_site_transient('team_approval');
                delete_site_transient('individual_report');
                wp_redirect('?page=add-team&code=added');
            }
        }
    }

    public function update_team_form_data() {
        if (isset($_POST['update_new_team']) || wp_verify_nonce($_POST['update_new_team'], 'update_new_team')
        ) {
            global $wpdb;
            $exist_team = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}team WHERE team_code='" . $_POST['team_code'] . "' AND id!=" . $_GET['id'] . "");
            if ($exist_team) {
                wp_redirect('?page=add-team&code=exist&do=edit&id=' . $_GET['id'] . '');
            } else {
                $_POST['team_approval'] = ($_POST['team_approval']) ? $_POST['team_approval'] : 0;
                $_POST['individual_report'] = ($_POST['individual_report']) ? $_POST['individual_report'] : 0;
                $wpdb->query("UPDATE {$wpdb->prefix}team SET team_name='" . $_POST['team_name'] . "',team_code='" . $_POST['team_code'] . "',team_approval=" . $_POST['team_approval'] . ",individual_report=" . $_POST['individual_report'] . " WHERE id=" . $_GET['id'] . "");
                wp_redirect('?page=add-team&code=update&do=edit&id=' . $_GET['id'] . '');
            }
        }
    }
}

$team_obj = new add_team();