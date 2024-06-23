<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendController extends Controller
{

    public function sendMail(Request $request)
    {
        // Récupérer les données
        $destinataire = $request->input('to');
        $subject = $request->input('subject');
        $content = $request->input('content');
        $attachments = $request->file('attachments');
        $replyTo = $request->input('reply_to');

        // $destinataire = $request->input('destinataire');
        // $objet = $request->input('objet');
        // $contenu = $request->input('contenu');
        // $piecesJointes = $request->file('pieces_jointes');

        $compteur = 100;

        try {
            // Envoyer le mail
            for ($i = 0; $i < count($destinataire); $i++) {
                $to = $destinataire[$i];
                Mail::send([], [], function ($message) use ($to, $subject, $content, $attachments, $replyTo) {
                    $message->to($to)
                        ->subject($subject)
                        ->setBody($content, 'text/html');

                    if (!empty($replyTo)) {
                        $message->replyTo($replyTo);
                    }

                    // Ajout des pièces jointes
                    if (!empty($attachments)) {
                        foreach ($attachments as $attachment) {
                            $message->attach($attachments->getRealPath(), [
                                'as' => $attachments->getClientOriginalName(),
                                'mime' => $attachments->getClientMimeType(),
                            ]);
                        }
                    }
                });
            }
            $compteur -= 1;

            // Rediriger vers la page de confirmation
            return response()->json(["message" => "Send"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error " . $th->getMessage()], 500);
        }
    }
}
