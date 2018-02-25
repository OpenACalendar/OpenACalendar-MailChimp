<?php

class Worker {

    /** @var int */
    protected $days;

    protected $url;

    protected $mailChimpFromName;

    protected $mailChimpReplyTo;

    protected $mailChimpAPIKey;

    protected $mailChimpMailingListId;

    /**
     * Worker constructor.
     * @param int $days
     */
    public function __construct($days = 30, $url = 'https://opentechcalendar.co.uk/api1/events.json', $mailChimpFromName, $mailChimpReplyTo, $mailChimpAPIKey, $mailChimpMailingListId)
    {
        $this->days = $days;
        $this->url = $url;
        $this->mailChimpFromName = $mailChimpFromName;
        $this->mailChimpReplyTo = $mailChimpReplyTo;
        $this->mailChimpMailingListId = $mailChimpMailingListId;
        $this->mailChimpAPIKey = $mailChimpAPIKey;
    }

    public function go() {


        $client = new \GuzzleHttp\Client();

        ########## Build Options

        $from = new \DateTime('', new \DateTimeZone('Europe/London'));
        $from->setTime(0, 0, 0);

        $to = new \DateTime('', new \DateTimeZone('Europe/London'));
        $to->setTime(23, 59, 59);
        $to->add(new \DateInterval('P'.$this->days.'D'));

        ######### Get Events

        $res = $client->request('GET', $this->url, array());

        if ($res->getStatusCode() != 200) {
            throw new Exception('Did not get 200!');
        }

        $eventData = \GuzzleHttp\json_decode($res->getBody());

        $events = array();
        foreach($eventData->data as $d) {
            if (count($events) < 100 && !$d->deleted && !$d->cancelled) {
                $event = new Event( $d );
                $eventStartLocal = $event->getStartLocalTime();
                if ($eventStartLocal->getTimestamp() >= $from->getTimestamp() && $eventStartLocal->getTimestamp() <= $to->getTimestamp()) {
                    $events[] = $event;
                }
            }
        }

        ######## Build Templates

        $cacheDir = new TemporaryFolder();
        $templates = array(
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates',
        );

        $loader = new Twig_Loader_Filesystem($templates);
        $twig = new Twig_Environment($loader, array(
            'cache' => $cacheDir->get(),
            'debug' => false,
        ));
        $twig->addExtension(new \JMBTechnologyLimited\Twig\Extensions\SameDayExtension());
        $twig->addExtension(new \JMBTechnologyLimited\Twig\Extensions\TimeZoneExtension());
        $twig->addExtension(new \JMBTechnologyLimited\Twig\Extensions\LinkInfoExtension());
        $twig->addExtension(new Twig_Extensions_Extension_Text());
        //$this->twig->addExtension(new Twig_Extension_Debug());

        $twigData = array(
            'events' => $events,
        );

        $subject    = $twig->render( 'subject.twig', $twigData );
        $html       = $twig->render( 'html.twig',  $twigData );
        $text       = $twig->render( 'text.twig', $twigData );

        #file_put_contents('/tmp/subject.txt', $subject);
        #file_put_contents('/tmp/html.txt', $html);
        #file_put_contents('/tmp/text.txt', $text);

        ######## MailChimp

        $dc = array_pop( explode( "-", $this->mailChimpAPIKey ) );

        try {

            $res = $client->request( 'POST', 'https://' . $dc . '.api.mailchimp.com/3.0/campaigns', array(
                'auth' => array(
                    'username',
                    $this->mailChimpAPIKey,
                ),
                'body' => json_encode( array(
                    'recipients' => array(
                        'list_id' => $this->mailChimpMailingListId,
                    ),
                    'settings'   => array(
                        'subject_line' => $subject,
                        'title'        => $subject,
                        'from_name'    => $this->mailChimpFromName,
                        'reply_to'     => $this->mailChimpReplyTo,
                    ),
                    'type' => 'regular',
                ) ),

            ) );

            if ( $res->getStatusCode() != 200 ) {
                throw new Exception( 'Did not get 200!' );
            }

            $campaignData = \GuzzleHttp\json_decode( $res->getBody() );

            //var_dump( $campaignData );

            $res = $client->request( 'PUT', 'https://' . $dc . '.api.mailchimp.com/3.0/campaigns/' . $campaignData->id . '/content', array(
                'auth' => array(
                    'username',
                    $this->mailChimpAPIKey,
                ),
                'body' => json_encode( array(
                    'plain_text' => $text,
                    'html'       => $html,
                ) ),
            ) );

            if ( $res->getStatusCode() != 200 ) {
                throw new Exception( 'Did not get 200!' );
            }

            //var_dump( \GuzzleHttp\json_decode( $res->getBody() ) );


        } catch (ClientException $e) {
            var_dump($e->getResponse()->getBody(true)->read(100000000));
            throw $e;
        }

    }

}