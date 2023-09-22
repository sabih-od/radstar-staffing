<?php
namespace App\Services;


class ContactMessageService
{
    public function getFormFields($requestData)
    {
        return
            [
                'full_name' => $requestData->full_name,
                'email' => $requestData->email,
                'phone' => $requestData->phone,
                'subject' => $requestData->subject,
                'message_txt' => $requestData->message_txt,

            ];

    }
}