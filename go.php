<?php


require_once  __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'TemporaryFolder.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Event.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Worker.php';

require_once __DIR__.DIRECTORY_SEPARATOR.'config.php';

$worker = new Worker(DAYS, URL, MAILCHIMP_FROM_NAME, MAILCHIMP_REPLY_TO, MAILCHIMP_API_KEY, MAILCHIMP_MAILING_LIST_ID);
$worker->go();


