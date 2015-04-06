<h1><?php echo $heading; ?></h1>
<?php if (empty($users)): ?>
    <h2>Oops! There are not users in the database.</h2>
<?php else: ?>
    <?php foreach ($users as $user): ?>
        <p><strong>First Name : </strong><?php echo $user->fname; ?></p>
        <p><strong>Last Name : </strong><?php echo $user->lname; ?></p>
        <p><strong>Email : </strong><?php echo $user->email; ?></p>
        <hr />
    <?php endforeach ?>
<?php endif ?>
<?php print_r($session->test) ?>
