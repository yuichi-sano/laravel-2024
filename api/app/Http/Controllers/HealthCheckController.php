<?php

namespace App\Http\Controllers;

use App\Services\RoleServiceInterface;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Message;

class HealthCheckController extends Controller
{
    protected RoleServiceInterface $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }
    /**
     * Check app health check connection.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {

        try {
            Mail::raw('メール本文', function($message){
                $message->to('sample@insight-inc.biz')->from('sample@domain.local')->subject('件名');
            });
            $test = Client::account('default');
            $imap = $test->connect();
            $folder = $imap->getFolder('INBOX');
            $unreadMessages = $folder->query()->whereUnseen();

            //$message = new Message();
            //$message->getSubject()->get()
            //$message->getTextBody();
            //$message->getUid();
            //$message->getMessageId();
            //$message->getMessageNo();
            //$message->getRawBody();
            //$message->getFrom();
            //$message->getHeader();
            //$message->getHTMLBody();
            //var_dump($newMessages2);
            foreach ($unreadMessages->get()as $message){
                var_dump($message->getSubject()->get()[0]);
                var_dump($message->getTextBody());
                //var_dump($message->getUid());
            }

            //foreach ($folders as $folder){
            //    var_dump($folder->name);
            //}
            //var_dump($test->connect()->getFolders());
            // return $this->response([
            //     'result' => 'ok'
            // ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            \Log::error($e);
            abort(Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
