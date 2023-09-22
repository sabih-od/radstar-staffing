<?php

namespace App\Http\Controllers\Api\Contact;

use App\ContactMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\APIResponse;
use App\Services\ContactMessageService;
use App\Repositories\ContactMessage\ContactMessageRepository;
use Illuminate\Support\Facades\Validator;
class ContactController extends Controller
{

    protected $contactMessageService,$contactMessageRepository;

    public function __construct
    (
        ContactMessageRepository     $contactMessageRepository,
        ContactMessageService        $contactMessageService
    )

    {
        $this->contactMessageRepository = $contactMessageRepository;
        $this->contactMessageService = $contactMessageService;

    }

    public function contact(Request $request)
    {
        try {
            $contactMessageData = $this->contactMessageService->getFormFields($request);
            $this->contactMessageRepository->create($contactMessageData);
            return APIResponse::success("Contact Message Sent Successfully");
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }




}
