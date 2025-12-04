<?php

namespace App\Actions\Chat;

use App\Http\Requests\Content\ConversationRequest;
use App\Models\Auth\User;
use App\Models\Content\Conversation;
use Illuminate\Support\Facades\DB;

class StartConversation
{
    public function create(ConversationRequest $request, User $me)
    {
        DB::beginTransaction();
        try {
            if ($request->type === 'private') {
                $conversation = $this->createPrivateConversation($request, $me);
            } elseif ($request->type === 'group') {
                $conversation = $this->createGroupConversation($request, $me);
            }

            DB::commit();

            return $conversation;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createPrivateConversation(ConversationRequest $request, User $me)
    {
        $user1 = $me->id;
        $user2 = $request->user_id;

        $conversation = Conversation::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user1) {
                $query->where('user_id', $user1);
            })
            ->whereHas('participants', function ($query) use ($user2) {
                $query->where('user_id', $user2);
            })
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'name' => 'Private '.$user1.'-'.$user2,
                'type' => 'private',
                'created_by' => $user1,
            ]);

            $conversation->participants()->attach([
                $user1 => [
                    'joined_at' => now(),
                    'role' => 'member',
                ],
                $user2 => [
                    'joined_at' => now(),
                    'role' => 'member',
                ],
            ]);
        }

        return $conversation;
    }

    private function createGroupConversation(ConversationRequest $request, User $me)
    {
        $conversation = Conversation::create([
            'name' => $request->name,
            'type' => 'group',
            'created_by' => $me->id,
            'description' => $request->description ?? null,
        ]);

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
}
