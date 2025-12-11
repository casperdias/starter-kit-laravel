<?php

namespace App\Actions\Chat;

use App\Http\Requests\Content\ConversationRequest;
use App\Models\Auth\User;
use App\Models\Content\Conversation;
use Illuminate\Http\UploadedFile;

class CreateGroupChat
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(ConversationRequest $request, User $me): Conversation
    {
        $conversationData = [
            'name' => $request->name,
            'type' => 'group',
            'created_by' => $me->id,
            'description' => $request->description ?? null,
        ];

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatarPath = $this->storeAvatar($request->file('avatar'));
            $conversationData['avatar'] = $avatarPath;
        }

        $conversation = Conversation::create($conversationData);

        $participantsData = [];

        $participantsData[$me->id] = [
            'role' => 'admin',
            'joined_at' => now(),
        ];

        foreach ($request->members as $member) {
            if ($member['id'] == $me->id) {
                continue;
            }

            $participantsData[$member['id']] = [
                'role' => 'member',
                'joined_at' => now(),
            ];
        }

        $conversation->participants()->attach($participantsData);

        return $conversation;
    }

    protected function storeAvatar(UploadedFile $file): string
    {
        $storagePath = 'avatars/conversations';
        $filename = 'avatar_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($storagePath, $filename);

        if ($path === false) {
            throw new \RuntimeException('Failed to store avatar file.');
        }

        return $path;
    }
}
