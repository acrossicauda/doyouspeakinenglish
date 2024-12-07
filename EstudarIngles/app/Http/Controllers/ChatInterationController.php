<?php

namespace App\Http\Controllers;

use App\Models\ChatInteration;
use Illuminate\Http\Request;

class ChatInterationController extends Controller
{

    private $url;


    public function __construct()
    {
        $this->url = env('API_IA_URL');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chat.home');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $text = $request->get('text');

        $url = $this->url;

        $post = [
            'contents' => [[
                'parts' => [[
                        'text' => "Estou aprendendo inglês e gostaria que você me corrija se eu estiver errado e responda
                        como se fosse uma conversa entre dois conhecidos, se estiver certo apenas responda a pergunta feita
                        apos os dois pontos, a respota não pode passar de 200 caracteres: \"{$text}\""
                ]]
            ]]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error #:" . $err);
        }

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatInteration $chatInteration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatInteration $chatInteration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatInteration $chatInteration)
    {
        //
    }
}
