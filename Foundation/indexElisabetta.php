<?php
require_once('../Entity/EReview.php');
require_once('FReview.php');
require_once('../utility/Type.php');

$FRev=FReview::getInstance();

$review=new EReview(7, 'Hello World', 3, null, Type::STUDENT, new DateTime("now"), Type::OWNER, 1, 1);
//$store=FReview::getInstance()->store($review);
/*
$load=$FRev->load($review->getId(), Type::STUDENT);
*/
//$review->setTitle('Modified');
//$new = $FRev->update($review);
$delete=$FRev->delete($review);
if ($delete) {
    echo 'All good';
}
else {
    echo 'Something went wrong';
}

