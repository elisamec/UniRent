<?php
require_once('../Entity/EReview.php');
require_once('FReview.php');
require_once('../utility/Type.php');

$FRev=FReview::getInstance();

$review=new EReview(null, 'Hello World', 3, null, Type::ACCOMMODATION, new DateTime("now"), Type::STUDENT, 1, 1);
$store=FReview::getInstance()->store($review);
if ($store) {
    print 'Store: All good';
}
else {
    print 'Store: Something went wrong';
}

$load=$FRev->load($review->getId(), $review->getRecipientType());
if ($load) {
    print 'Load: All good';
}
else {
    print 'Load: Something went wrong';
}
$review->setTitle('Modified');
$new = $FRev->update($review);
if ($new) {
    print 'Update: All good';
}
else {
    print 'Update: Something went wrong';
}
$delete=$FRev->delete($review);
if ($delete) {
    print 'Delete: All good';
}
else {
    print 'Delete: Something went wrong';
}

