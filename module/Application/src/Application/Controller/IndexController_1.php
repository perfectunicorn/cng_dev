<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use User\Form\Add;

use Google_Client;
use Google_Service_YouTube_LiveBroadcastSnippet;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveStreamSnippet;
use Google_Service_YouTube_LiveStream;
use Google_Service_YouTube_CdnSettings;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $form=new Add();
        $viewModel = new ViewModel(array('form'=>$form));
        $viewModel->setTerminal(true);

    return $viewModel;
    }

    public function aboutAction()
    {
        return new ViewModel();

    }
    
    public function pruebaAction()
    {
        session_start();

        // Call set_include_path() as needed to point to your client library.


        /*
         * You can acquire an OAuth 2.0 client ID and client secret from the
         * Google Developers Console <https://console.developers.google.com/>
         * For more information about using OAuth 2.0 to access Google APIs, please see:
         * <https://developers.google.com/youtube/v3/guides/authentication>
         * Please ensure that you have enabled the YouTube Data API for your project.
         */
        $OAUTH2_CLIENT_ID = '606785504095-1qdktcvaf6m983h6cp0fkhikf4v0qam5.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET = 'X0cqUG4KH0cCy2WjW5jAE2JF';

        $client = new Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');

        /*$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);*/
        $redirect='http://codeneedsgirls.hol.es/cng/application/index/prueba';
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.

        $youtube = new \Google_Service_YouTube($client);

        if (isset($_GET['code'])) {

          if (strval($_SESSION['state']) !== strval($_GET['state'])) {
            die('The session state did not match.');
          }

          $client->authenticate($_GET['code']);
          $_SESSION['token'] = $client->getAccessToken();
          header('Location: ' . $redirect);
        }

        if (isset($_SESSION['token'])) {
          $client->setAccessToken($_SESSION['token']);
        }

        // Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {
          try {
            // Create an object for the liveBroadcast resource's snippet. Specify values
            // for the snippet's title, scheduled start time, and scheduled end time.
            $broadcastSnippet = new Google_Service_YouTube_LiveBroadcastSnippet();
            $broadcastSnippet->setTitle('Nuevo broadcast');
            $broadcastSnippet->setScheduledStartTime('2016-11-01T00:00:00.000Z');
            //$broadcastSnippet->setScheduledEndTime('2034-01-31T00:00:00.000Z');

            // Create an object for the liveBroadcast resource's status, and set the
            // broadcast's status to "private".
            $status = new \Google_Service_YouTube_LiveBroadcastStatus();
            $status->setPrivacyStatus('private');

            // Create the API request that inserts the liveBroadcast resource.
            $broadcastInsert = new Google_Service_YouTube_LiveBroadcast();
            $broadcastInsert->setSnippet($broadcastSnippet);
            $broadcastInsert->setStatus($status);
            $broadcastInsert->setKind('youtube#liveBroadcast');

            // Execute the request and return an object that contains information
            // about the new broadcast.
            $broadcastsResponse = $youtube->liveBroadcasts->insert('snippet,status',
                $broadcastInsert, array());

            // Create an object for the liveStream resource's snippet. Specify a value
            // for the snippet's title.
            $streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
            $streamSnippet->setTitle('Nuevo streaming');

            // Create an object for content distribution network details for the live
            // stream and specify the stream's format and ingestion type.
            $cdn = new Google_Service_YouTube_CdnSettings();
            $cdn->setFormat("1080p");
            $cdn->setIngestionType('rtmp');

            // Create the API request that inserts the liveStream resource.
            $streamInsert = new Google_Service_YouTube_LiveStream();
            $streamInsert->setSnippet($streamSnippet);
            $streamInsert->setCdn($cdn);
            $streamInsert->setKind('youtube#liveStream');

            // Execute the request and return an object that contains information
            // about the new stream.
            $streamsResponse = $youtube->liveStreams->insert('snippet,cdn',
                $streamInsert, array());

            // Bind the broadcast to the live stream.
            $bindBroadcastResponse = $youtube->liveBroadcasts->bind(
                $broadcastsResponse['id'],'id,contentDetails',
                array(
                    'streamId' => $streamsResponse['id'],
                ));

            $htmlBody .= "<h3>Añadir broadcast</h3><ul>";
            $htmlBody .= sprintf('<li>%s publicado el %s (%s)</li>',
                $broadcastsResponse['snippet']['title'],  //Título del video
                $broadcastsResponse['snippet']['publishedAt'],  //fecha de publicación del video
                $broadcastsResponse['id']);   // ID del video en youtube
            $htmlBody .= '</ul>';

            $htmlBody .= "<h3>Añadir stream</h3><ul>";
            $htmlBody .= sprintf('<li>%s (%s)</li>',
                $streamsResponse['snippet']['title'],
                $streamsResponse['id']);
            $htmlBody .= '</ul>';

            $htmlBody .= "<h3>Bound Broadcast</h3><ul>";
            $htmlBody .= sprintf('<li>Broadcast (%s) fue vinculado al stream (%s).</li>',
                $bindBroadcastResponse['id'],
                $bindBroadcastResponse['contentDetails']['boundStreamId']);
            $htmlBody .= '</ul>';

          } catch (Google_Service_Exception $e) {
            $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          } catch (Google_Exception $e) {
            $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          }

          $_SESSION['token'] = $client->getAccessToken();
        } else {
          // If the user hasn't authorized the app, initiate the OAuth flow
          $state = mt_rand();
          $client->setState($state);
          $_SESSION['state'] = $state;

          $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h3>Autorización requerida</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}

        return new ViewModel(array(
            'htmlBody'=>$htmlBody,
        ));

    }

    
    public function listaAction()
    {
        session_start();

        // Call set_include_path() as needed to point to your client library.


        /*
         * You can acquire an OAuth 2.0 client ID and client secret from the
         * Google Developers Console <https://console.developers.google.com/>
         * For more information about using OAuth 2.0 to access Google APIs, please see:
         * <https://developers.google.com/youtube/v3/guides/authentication>
         * Please ensure that you have enabled the YouTube Data API for your project.
         */
        $OAUTH2_CLIENT_ID = '606785504095-1qdktcvaf6m983h6cp0fkhikf4v0qam5.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET = 'X0cqUG4KH0cCy2WjW5jAE2JF';

        $client = new Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');

        /*$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);*/
        $redirect='http://codeneedsgirls.hol.es/cng/application/index/lista';
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.

        $youtube = new \Google_Service_YouTube($client);

        if (isset($_GET['code'])) {

          if (strval($_SESSION['state']) !== strval($_GET['state'])) {
            die('The session state did not match.');
          }

          $client->authenticate($_GET['code']);
          $_SESSION['token'] = $client->getAccessToken();
          header('Location: ' . $redirect);
        }

        if (isset($_SESSION['token'])) {
          $client->setAccessToken($_SESSION['token']);
        }

        // Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {
          try {
            // Execute an API request that lists broadcasts owned by the user who
            // authorized the request.
            $broadcastsResponse = $youtube->liveBroadcasts->listLiveBroadcasts(
                'id,snippet,status',
                array(
                    'mine' => 'true',
                ));

            $htmlBody .= "<h3>Live Broadcasts</h3><ul>";
            foreach ($broadcastsResponse['items'] as $broadcastItem) {
                if ($broadcastItem['status']['lifeCycleStatus']=='live'){
              $htmlBody .= sprintf('<li>%s (%s)</li>', $broadcastItem['snippet']['title'],
                $broadcastItem['id']);}
            }
            $htmlBody .= '</ul>';

          } catch (Google_Service_Exception $e) {
            $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          } catch (Google_Exception $e) {
            $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          }

          $_SESSION['token'] = $client->getAccessToken();
        } else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}

        return new ViewModel(array(
            'htmlBody'=>$htmlBody,
            $broadcastItem,
        ));

    }



    
    private function makeSlug($slugStr) 
    {   
        $value = strtolower($slugStr);
        $separator="-";

        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^[a-z0-9]+/", ' ', $value);
        $value = trim($value);
        $value = preg_replace("/[\s]/", $separator, $value);
        
        return $value;
           
    }
    
    public function ChatAction()
    {
        return new ViewModel();
    }
    
    public function practiceAction()
    {
        return new ViewModel();
    }
    
    public function addBroadcastAction()
    {
        return new ViewModel();
    }
}