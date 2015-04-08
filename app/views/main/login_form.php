<?php
$cont = getContentByPageName('register_page_text');

if ($cont) {
    ?>
    <div class="information_section">
        <?php
        echo $cont;
        ?>
    </div>
    <?php
}
?>
<div class="inner-box-main sign-in-page">
    <div class="left_signin">

        <div id="sign_in" class="Xpop-up">
            <div class="pop-up-title">
                <h4 class="active">SIGNIN HERE</h4>
                <div class="clear"></div>
            </div>
            <div class="pop-up-details">
                <form  action="<?php echo getUrl('main/login') ?>" method="post" id="signin"  name="signin">
                    <p>
                        <label>User Name</label>
                        <input type="email" id="username1" value="<?php echo $username ?>" required="required" name="username">
                    </p>
                    <p>
                        <label>Password</label>
                        <input type="password" id="password" required="required" name="password" pattern="(?=^.{6,46}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                    </p>
                    <p>
                        <input type="submit" value="Sign In" name="submit">
                    </p>
                </form>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div class="register">
            <?php
            if (isset($registerusername)) {
                ?>
                <div class="pop-up-title">
                    <h4 class="active">THANK YOU</h4>
                    <div class="clear"></div>
                </div>
                <div class="pop-up-details">
                    Thank you for registration.
                </div>
            </div>
        <?php } else { ?>
            <div class="pop-up-title">
                <h4 class="active">REGISTER WITH US</h4>
                <div class="clear"></div>
            </div>
            <div class="pop-up-details">
                <form action="<?php echo getUrl('main/register') ?>" method="post" id="register" name="register">
                    <p>
                        <label>First Name</label>
                        <input type="text" required="required" class="" id="firstname" name="data[firstname]" value="<?php echo $user->firstname ?>" pattern="[a-zA-Z ]+">
                    </p>
                    <p>
                        <label>Last Name</label>
                        <input type="text" required="required" class="" id="lastname" name="data[lastname]" value="<?php echo $user->lastname ?>" pattern="[a-zA-Z ]+">
                    </p>
                    <p>
                        <label>Email Address</label>
                        <input type="email" required="required" autocomplete="off" class="" id="username" name="data[username]" value="<?php echo $user->username ?>" >
                    <div style="clear:both;"></div>
                    <span id="email_status" style="font-size:13px; color:#ff0000; "></span>
                    <span id="handle_status"></span>

                    </p>
                    <p style="margin-bottom:0px;">
                        <label>Password</label>
                        <input type="password" required="required" autocomplete="off" class="" id="password1" name="data[password]" pattern="(?=^.{6,46}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                        <span id="pass_chek1"></span>                        
                    <div style="color: grey; font-size: 12px; line-height: 16px; margin-left: 149px; margin-top: 3px; width: 329px;">
                        Password must be 6 characters including 
                        one uppercase letter and number.
                    </div>                        
                    </p>
                    <p>
                        <label>Confirm Password</label>
                        <input type="password" required="required" class="" id="password2" name="data[re_password]">
                        <span id="pass_chek"></span>
                    </p>
                    <p>
                        <label>Country</label>
                        <?php
                        $countrylist = getCountryList();
                        ?>
                        <select id="country" name="data[country]" class="listmenu" required="required">
                            <option value=''>-Select one-</option>
                            <?php
                            foreach ($countrylist AS $ckey => $cval) {
                                echo "<option value='$ckey'>$cval</option>";
                            }
                            ?>
                        </select>
                    </p>
                    <input type='hidden' id='iserror1' name='iserror1' value='0'>
                    <input type='hidden' id='iserror2' name='iserror2' value='0'>
                    <input type="submit" value="Register" id="submit" class="" name="submit" style="margin-top:20px;">
                    </p>

                </form>
            </div>
        <?php } ?>
    </div>
    <div style="clear:both;"></div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#username").focusout(function () {
            var $username = $("#username").val();
            if ($username) {
                $.post("<?php echo getUrl('ajax/main/userexist') ?>", {username: $username}, function (data) {
                    if (data == 1) {
                        $('#email_status').html('Email already taken');
                        $('#iserror1').val(1);
                    } else {
                        $('#email_status').html('');
                        $('#iserror1').val(0);
                    }
                });
            }
        });

        $("#register").submit(function (event) {
            showprice();

            var pass1 = document.getElementById("password1").value;
            var pass2 = document.getElementById("password2").value;

            if (pass1.length < 6) {
                $('#pass_chek').html('Password must be minimum 6 characters');
                passerror = 1;
            } else {
                $('#pass_chek').html('');
                passerror = 0;
            }

            if (pass1 != pass2) {
                $('#pass_chek').html('Password Not Match');
                $('#iserror2').val(1);
            } else {
                $('#pass_chek').html('');
                $('#iserror2').val(0);
            }

            if (passerror == 1 || $('#iserror1').val() == 1 || $('#iserror2').val() == 1) {
                event.preventDefault();
            } else {
                return;
            }

        });

    });
</script>
