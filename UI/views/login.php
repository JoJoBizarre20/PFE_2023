<?php 
require_once "UI/header.php";
require_once "include/router.php";
?>
<div class="container">
<h2><img src="UI/assets/logo.svg" alt=""></h2>
    <form action="/login" name="login" method="post">
        <h3>Login</h3>
        <div class="form-group">
            <label for="Email1">Email address</label>
            <input name="Email" type="email" class="form-control" id="Email1" aria-describedby="emailHelp" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="Password1">Password</label>
            <input name="Password" type="password" class="form-control" id="Password1" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-info"><i class="material-icons">keyboard_tab</i></button>
    </form>
<h3>Register as new User</h3>
    <form action="/register" method="post" name="register">
        <div class="form-group">
            <label for="Email">Email address</label>
            <input name="Email" type="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Enter email" required>
        </div>
        
        <div class="form-group">
            <label for="Password">Password</label>
            <input name="Password" type="password" class="form-control" id="Password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="confirm_Password">Confirm Password</label>
            <input name="confirm_Password" type="password" class="form-control" id="confirm_Password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info"><i class="material-icons">create</i></button>
        </div>
    </form>
</div>

<?php require_once "UI/footer.php";?>