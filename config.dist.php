<?php

/**
 * We will list events for the next X days.
 */
define('DAYS',30);

/**
 * Enter the URL end point to get events from.
 *
 * This can be all events from the site: eg,
 *     https://opentechcalendar.co.uk/api1/events.json
 *
 * Or it can be a subset: eg,
 *     https://opentechcalendar.co.uk/api1/area/62/events.json
 */
define('URL','https://opentechcalendar.co.uk/api1/events.json');


/**
 * This is the name your emails will come from. Use something your subscribers will instantly recognize, like your company name.
 */
define('MAILCHIMP_FROM_NAME','xxxxx');


/**
 * The address you receive reply emails. Check it regularly to stay in touch with your audience.
 */
define('MAILCHIMP_REPLY_TO','xxxxx');


/**
 * Go to Account, Extras, API Keys to get this.
 */
define('MAILCHIMP_API_KEY','xxxxxxxxxxxxxxxx-us8');


/**
 * Go to The MailChimp list, Settings, List Name And Defaults to get this.
 */
define('MAILCHIMP_MAILING_LIST_ID','xxxxxxxx');
