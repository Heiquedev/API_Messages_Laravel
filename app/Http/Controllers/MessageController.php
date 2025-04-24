<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsFalse;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::all();
        return response()->json([
            'success' => true,
            'msg' => 'Message retryvied successfully',
            'dataCount' => $messages->count(),
            'data' => $messages,
        ], 200);
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
        try {
            $request->validate(
                [
                    'sender_id' => 'required|exists:users,id',
                    'receiver_id' => 'required|exists:users,id',
                    'message' => 'required|string'
                ],
                [
                    'sender_id.required' => 'Sender is required',
                    'sender_id.exists' => 'Sender must be a valid user',
                    'receiver_id.required' => 'Receiver is required',
                    'receier_id.exists' => 'Receiver must be a valid user',
                    'message.required' => 'Message is required'
                ]
            );

            $message = Message::create($request->all());
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while sending message',
                'error' => $error->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Messages sent successfully',
            'data' => $message->load('sender','receiver')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'sender_id' => 'required|exists:users,id',
                    'receiver_id' => 'required|exists:users,id',
                    'message' => 'required|string'
                ],
                [
                    'sender_id.required' => 'Sender is required',
                    'receiver_id.required' => 'Receiver is required',
                    'message.required' => 'Message is required'
                ]
            );

            $message = Message::findOrFail($id);
            $message->update($request->all());
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while updating message',
                'error' => $error->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'msg' => "Message from $message->sender updated successfully",
            'data' => $message->load('sender','receiver')
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();

            return response()->json([
                'succes' => true,
                'msg' => "Message from $message->sender deleted successfully"
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while deleting message',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
