<?php
require_once('../Entity/EReview.php');
require_once('FReview.php');
require_once('../Tools/TType.php');
require_once('../Entity/EOwner.php');
require_once('FOwner.php');
require_once('../Entity/ESupportRequest.php');
require_once('FSupportRequest.php');


/*REVIEW


 $FRev=FReview::getInstance();

 $review=new EReview(26, 'Hello World', 3, null, [], TType::STUDENT, new DateTime("now"), TType::STUDENT, 1, 1);
  $store=FReview::getInstance()->store($review);
if ($store) {
    print 'Store: All good';
}
else {
    print 'Store: Something went wrong';
}
*/
/*
$load=$FRev->load($review->getId(), $review->getRecipientType());
if ($load) {
    print 'Load: All good';
}
else {
    print 'Load: Something went wrong';
}
*/
/*
$review->setTitle('Modified');
$new = $FRev->update($review);
if ($new) {
    print 'Update: All good';
}
else {
    print 'Update: Something went wrong';
}
*/
/*
$delete=$FRev->delete($review);
if ($delete) {
    print 'Delete: All good';
}
else {
    print 'Delete: Something went wrong';
}
*/

//OWNER
/*
$FOwn=FOwner::getInstance();

$owner=new EOwner(12, 'testOwner', 'My_s3cr37_p4sswd', 'Gianni', 'Rossi', null, 'gianni.rossi@gmail.com', '3331234567', 'IT1234567890123456789012345');

/*$store=$FOwn->store($owner);
if ($store) {
    print 'Store: All good';
}
else {
    print 'Store: Something went wrong';
}
$load=$FOwn->load($owner->getId());
if ($load) {
    print 'Load: All good';
}
else {
    print 'Load: Something went wrong';
}

$owner->setUsername('username');
$new = $FOwn->update($owner);
if ($new) {
    print 'Update: All good';
}
else {
    print 'Update: Something went wrong';
}

$delete=$FOwn->delete($owner);
if ($delete) {
    print 'Delete: All good';
}
else {
    print 'Delete: Something went wrong';
}
*/

// SUPPORT REQUEST

$FS=FSupportRequest::getInstance();

$supreq=new ESupportRequest(1, 'random message', TRequestType::BUG, 1, TType::OWNER);
/*
$store=$FS->store($supreq);
if ($store) {
    print 'Store: All good';
}
else {
    print 'Store: Something went wrong';
}


$load=$FS->load($supreq->getId());
if ($load) {
    print 'Load: All good';
}
else {
    print 'Load: Something went wrong';
}


$supreq->setMessage('username');
$new = $FS->update($supreq);
if ($new) {
    print 'Update: All good';
}
else {
    print 'Update: Something went wrong';
}
*/
$delete=$FS->delete($supreq);
if ($delete) {
    print 'Delete: All good';
}
else {
    print 'Delete: Something went wrong';
}
