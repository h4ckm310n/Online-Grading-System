<?php
session_start();
require_once "../include/database/User.php";
require_once "../include/check_auth.php";
$row = User::select_by_uid($_SESSION['uid'], $_SESSION['urole']);

if ($row)
{
    //Display user information
    ?>
    <form method="post">
        <div class="form-group row">
            <label class="col-md-4 col-form-label" for="edit_user_name">Name: </label>
            <div class="col-md-5">
                <input id="edit_user_name" name="edit_user_name" type="text" class="form-control" required="required"
                       value="<?php echo $row['name']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label" for="edit_user_password">Password: </label>
            <div class="col-md-5">
                <input id="edit_user_password" name="edit_user_password" type="password" class="form-control" required="required"
                value="<?php echo $row['password']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label" for="edit_user_phone">Phone Number: </label>
            <div class="col-md-5">
                <input id="edit_user_phone" name="edit_user_phone" type="number" class="form-control" required="required"
                       value="<?php echo $row['phone']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label" for="edit_user_email">Email: </label>
            <div class="col-md-5">
                <input id="edit_user_email" name="edit_user_email" type="email" class="form-control" required="required"
                       value="<?php echo $row['email']; ?>">
            </div>
        </div>
        <?php
        if (check_auth() == 1) {
            ?>
            <div class="form-group row">
                <label class="col-md-4 col-form-label" for="edit_user_office">Office: </label>
                <div class="col-md-5">
                    <input id="edit_user_office" name="edit_user_office" type="text" class="form-control" required="required"
                           value="<?php echo $row['office']; ?>">
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-group row">
            <div class="col-md-4"></div>
            <div class="col-md-2">
                <button type="button" id="edit_user_post" class="btn btn-primary" onclick="updateUser()">Update</button>
            </div>
        </div>
    </form>
    <?php
}
else
    echo "Failed to get user information";

