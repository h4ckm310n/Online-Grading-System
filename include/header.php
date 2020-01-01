<?php
session_start();
function display_header()
{
    //nav bar on the top

    $host = "http://".$_SERVER['HTTP_HOST'];
    ?>
    <div>
        <nav class="navbar navbar-expand-md bg-secondary navbar-dark fixed-top">
            <div class="container">
                <a class="nav-brand" style="color: white" href="<?php echo $host; ?>">Online Grading System</a>
                <ul class="navbar-nav mr-auto">
                    <?php
                    if (check_auth() == 1)
                    {
                        // if user is a teacher, show student link
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $host; ?>/student/list.php">Students</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $host; ?>/course/list.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $host; ?>/assignment/list.php">Assignments</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link"><?php echo $_SESSION['uid']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><?php echo $_SESSION['uname']; ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="user_drop" data-toggle="dropdown">User</a>
                        <div class="dropdown-menu" style="padding-right: 5px; text-align: center;">
                            <a class="dropdown-item" href="#" onclick="showSettingModal()">Setting</a>
                            <a class="dropdown-item" href="<?php echo $host; ?>/user/logout.php">Log out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="modal fade" id="user_setting_modal" data-backdrop="static">
        <div class="modal-dialog" style="background-color: white">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update User Info</h4>
                </div>
                <div class="modal-body" id="user_setting_modal_body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSettingModal() {
            $.post("<?php echo $host; ?>/user/info.php",
                    {},
                    function(data, status)
                    {
                        $('#user_setting_modal_body').html(data);
                        $('#user_setting_modal').modal('show');
                    }
            );
        }

        function updateUser() {
            var office;
            <?php
                if (check_auth() == 1)
                    echo "office = $('#edit_user_office').val();";
                else
                    echo "office = '';";
            ?>
            $.post("<?php echo $host; ?>/user/setting.php",
                    {
                        name: $('#edit_user_name').val(),
                        pwd: $('#edit_user_password').val(),
                        phone: $('#edit_user_phone').val(),
                        email: $('#edit_user_email').val(),
                        office: office
                    },
                    function(data, status)
                    {
                        alert(data);
                        location.reload();
                    }
            );
        }
    </script>
<?php
}
?>