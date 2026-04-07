<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppChatLabel;
use App\Models\User;
use App\Models\WhatsAppChatAssignment;
use App\Models\WhatsAppChatPriority;
use App\Models\WhatsAppLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    private string $baseUrl;
    private string $apiKey;
    private string $instance;

    public function __construct()
    {
        $this->baseUrl  = rtrim(config('evolutionapi.url'), '/');
        $this->apiKey   = config('evolutionapi.key');
        $this->instance = config('evolutionapi.instance');
    }

    public function index()
    {
        return view('dashboard.whatsapp.index');
    }

    /**
     * Fetch all chats (contacts + groups)
     */
    public function fetchChats(Request $request)
    {
        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/findChats/{$this->instance}");

            if (! $response->successful()) {
                return response()->json(['error' => 'Falha ao buscar conversas.'], 500);
            }

            // Unifica conversas que vêm duplicadas como LID (ex.: 259...@lid) e JID clássico (@s.whatsapp.net)
            $byDisplayId = collect($response->json())->reduce(function ($carry, $chat) {
                $remoteJid    = $chat['remoteJid']    ?? '';
                $remoteJidAlt = $chat['remoteJidAlt']
                    ?? ($chat['lastMessage']['key']['remoteJidAlt'] ?? null);
                $displayId    = $remoteJidAlt ?: $remoteJid; // usamos o JID clássico como chave visível

                $entry = [
                    'id'           => $displayId,
                    'remoteJids'   => array_values(array_filter([$remoteJid, $remoteJidAlt])),
                    'remoteJidAlt' => $remoteJidAlt,
                    'name'         => $chat['pushName'] ?? ($chat['id'] ?? $displayId),
                    'isGroup'      => $chat['isGroup'] ?? false,
                    'unread'       => $chat['unreadCount'] ?? 0,
                    'lastMsg'      => $chat['lastMessage']['message']['conversation']
                        ?? $chat['lastMessage']['message']['extendedTextMessage']['text']
                            ?? '',
                    'timestamp'    => $chat['lastMessage']['messageTimestamp'] ?? 0,
                    'photo'        => null,
                ];

                if (isset($carry[$displayId])) {
                    // Mescla duplicatas: soma unread, mantém a última mensagem mais recente e agrega remoteJids
                    $existing = $carry[$displayId];
                    $entry['remoteJids'] = array_values(array_unique(array_merge(
                        $existing['remoteJids'],
                        $entry['remoteJids']
                    )));
                    $entry['unread'] = ($existing['unread'] ?? 0) + ($entry['unread'] ?? 0);

                    // escolhe a mensagem mais recente
                    if (($existing['timestamp'] ?? 0) >= ($entry['timestamp'] ?? 0)) {
                        $entry['lastMsg']   = $existing['lastMsg'];
                        $entry['timestamp'] = $existing['timestamp'];
                    }
                }

                $carry[$displayId] = $entry;
                return $carry;
            }, []);

            $chats = collect($byDisplayId)->sortByDesc('timestamp')->values();

            return response()->json($chats);
        } catch (\Exception $e) {
            Log::error('EvolutionAPI fetchChats error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch contacts (auxiliary endpoint to resolver nomes de contato)
     */
    public function fetchContacts(Request $request)
    {
        $where = [];
        if ($request->filled('id')) {
            $where['id'] = $request->input('id');
        }

        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/findContacts/{$this->instance}", [
                    'where' => $where ?: (object)[],
                ]);

            if (! $response->successful()) {
                return response()->json(['error' => 'Falha ao buscar contatos.'], 500);
            }

            $contacts = collect($response->json() ?: [])->map(function ($c) {
                $jid = $c['remoteJid'] ?? $c['id'] ?? '';
                return [
                    'id'    => $jid,
                    'name'  => $c['name'] ?? $c['pushName'] ?? '',
                    'about' => $c['status'] ?? null,
                ];
            })->filter(fn ($c) => ! empty($c['id']))->values();

            return response()->json($contacts);
        } catch (\Exception $e) {
            Log::error('EvolutionAPI fetchContacts error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch messages for a specific chat
     */
    public function fetchMessages(Request $request)
    {
        $request->validate([
            'chat_id'  => 'required|string',
            'chat_ids' => 'nullable|array',
            'chat_ids.*' => 'string',
            'limit'    => 'nullable|integer|min:1|max:100',
        ]);

        $chatId  = $request->input('chat_id');
        $chatIds = collect($request->input('chat_ids', []))
            ->filter()
            ->push($chatId)
            ->unique()
            ->values();
        $limit   = $request->input('limit', 50);

        try {
            $allMessages = collect();

            foreach ($chatIds as $jid) {
                $response = Http::withHeaders(['apikey' => $this->apiKey])
                    ->timeout(30)
                    ->post("{$this->baseUrl}/chat/findMessages/{$this->instance}", [
                        'where' => [
                            'key' => [
                                'remoteJid' => $jid,
                            ],
                        ],
                        'limit' => $limit,
                    ]);

                if (! $response->successful()) {
                    Log::warning('EvolutionAPI fetchMessages partial failure', [
                        'jid'    => $jid,
                        'status' => $response->status(),
                    ]);
                    continue;
                }

                $data      = $response->json();
                $messages  = $data['messages']['records'] ?? $data['records'] ?? $data ?? [];
                $allMessages = $allMessages->merge($messages);
            }

            $normalized = $allMessages->map(function ($msg) {
                $msgContent = $msg['message'] ?? [];

                // Tipo informado pelo Evolution API vem em messageType; se ausente, inferimos pela primeira chave do payload
                $type = $msg['messageType'] ?? array_key_first($msgContent) ?? 'conversation';

                $text = $msgContent['conversation']
                    ?? $msgContent['extendedTextMessage']['text']
                    ?? $msgContent['imageMessage']['caption']
                    ?? $msgContent['videoMessage']['caption']
                    ?? $msgContent['documentMessage']['caption']
                    ?? null;

                // jpegThumbnail pode vir em imageMessage, videoMessage ou stickerMessage
                $thumbnail = $msgContent['imageMessage']['jpegThumbnail']
                    ?? $msgContent['videoMessage']['jpegThumbnail']
                    ?? $msgContent['stickerMessage']['jpegThumbnail']
                    ?? null;

                $mimeType = $msgContent['imageMessage']['mimetype']
                    ?? $msgContent['videoMessage']['mimetype']
                    ?? $msgContent['documentMessage']['mimetype']
                    ?? $msgContent['stickerMessage']['mimetype']
                    ?? null;

                $isAudio = in_array($type, ['audioMessage', 'pttMessage']);

                // Quando o Evolution API devolve múltiplas atualizações de status (MessageUpdate), usamos o último status
                $statusHistory = $msg['MessageUpdate'] ?? [];
                $lastStatus    = is_array($statusHistory) && count($statusHistory)
                    ? (end($statusHistory)['status'] ?? '')
                    : '';

                $timestamp = $msg['messageTimestamp']
                    ?? $msg['timestamp']
                    ?? $msg['key']['messageTimestamp']
                    ?? 0;

                return [
                    'id'        => $msg['id'] ?? $msg['key']['id'] ?? '',
                    'key'       => $msg['key'] ?? [],
                    'fromMe'    => $msg['key']['fromMe'] ?? false,
                    'text'      => $text,
                    'thumbnail' => $thumbnail,
                    'timestamp' => $timestamp,
                    'status'    => $msg['status'] ?? $lastStatus,
                    'type'      => $type,
                    'mime'      => $mimeType,
                    'audioData' => $isAudio ? [
                        'key'     => $msg['key'],
                        'message' => $msgContent,
                    ] : null,
                    'mediaData' => (! $isAudio && $thumbnail) ? [
                        'key'     => $msg['key'] ?? [],
                        'message' => $msgContent,
                        'mime'    => $mimeType,
                        'type'    => $type,
                    ] : null,
                ];
            })->sortBy('timestamp')->values();

            return response()->json($normalized);
        } catch (\Exception $e) {
            Log::error('EvolutionAPI fetchMessages error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a text message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|string',
            'message' => 'required|string|max:4096',
        ]);

        $chatId  = $request->input('chat_id');
        $message = $request->input('message');

        $senderName = $request->user()?->name;
        if ($senderName) {
            $message = "*{$senderName}:*\n{$message}";
        }

        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(30)
                ->post("{$this->baseUrl}/message/sendText/{$this->instance}", [
                    'number'  => $chatId,
                    'text'    => $message,
                    'options' => [
                        'delay'    => 500,
                        'presence' => 'composing',
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('EvolutionAPI sendMessage error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return response()->json(['error' => 'Falha ao enviar mensagem.'], 500);
            }

            return response()->json(['success' => true, 'data' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('EvolutionAPI sendMessage exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get instance connection status
     */
    public function status()
    {
        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(10)
                ->get("{$this->baseUrl}/instance/connectionState/{$this->instance}");

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Return active users for assignment
     */
    public function getUsers()
    {
        $users = User::where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($users);
    }

    /**
     * Assign or unassign a chat to a user
     */
    public function assignChat(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $chatId = $request->input('chat_id');
        $userId = $request->input('user_id');

        if ($userId) {
            WhatsAppChatAssignment::updateOrCreate(
                ['chat_id' => $chatId],
                ['user_id' => $userId]
            );
        } else {
            WhatsAppChatAssignment::where('chat_id', $chatId)->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get all chat assignments indexed by chat_id
     */
    public function getAssignments()
    {
        $assignments = WhatsAppChatAssignment::with('user:id,name')
            ->get()
            ->keyBy('chat_id')
            ->map(fn($a) => ['user_id' => $a->user_id, 'user_name' => $a->user->name ?? '']);

        return response()->json($assignments);
    }

    /**
     * List all labels
     */
    public function getLabels()
    {
        $labels = WhatsAppLabel::query()
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        return response()->json($labels);
    }

    /**
     * Create label
     */
    public function createLabel(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'color' => ['required', 'string', 'max:20', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $label = WhatsAppLabel::create([
            'name' => trim($data['name']),
            'color' => $data['color'],
            'created_by' => $request->user()?->id,
        ]);

        return response()->json([
            'success' => true,
            'label' => $label->only(['id', 'name', 'color']),
        ]);
    }

    /**
     * Delete label and its chat links
     */
    public function deleteLabel(WhatsAppLabel $label)
    {
        $label->delete();

        return response()->json(['success' => true]);
    }

    /**
     * List chat labels mapped by chat_id
     */
    public function getChatLabels()
    {
        $mapped = [];

        WhatsAppChatLabel::query()
            ->select(['chat_id', 'label_id'])
            ->orderBy('chat_id')
            ->get()
            ->each(function ($row) use (&$mapped) {
                $mapped[$row->chat_id] ??= [];
                $mapped[$row->chat_id][] = (string) $row->label_id;
            });

        return response()->json($mapped);
    }

    /**
     * Toggle a label for a chat
     */
    public function toggleChatLabel(Request $request)
    {
        $data = $request->validate([
            'chat_id' => 'required|string',
            'label_id' => 'required|exists:whatsapp_labels,id',
        ]);

        $existing = WhatsAppChatLabel::query()
            ->where('chat_id', $data['chat_id'])
            ->where('label_id', $data['label_id'])
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            WhatsAppChatLabel::create([
                'chat_id' => $data['chat_id'],
                'label_id' => $data['label_id'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * List chat priorities mapped by chat_id
     */
    public function getPriorities()
    {
        $mapped = WhatsAppChatPriority::query()
            ->select(['chat_id', 'priority'])
            ->get()
            ->mapWithKeys(fn ($p) => [$p->chat_id => $p->priority]);

        return response()->json($mapped);
    }

    /**
     * Set or clear chat priority
     */
    public function setPriority(Request $request)
    {
        $data = $request->validate([
            'chat_id' => 'required|string',
            'priority' => 'nullable|in:maxima,alta,media,baixa',
        ]);

        if (empty($data['priority'])) {
            WhatsAppChatPriority::query()
                ->where('chat_id', $data['chat_id'])
                ->delete();

            return response()->json(['success' => true]);
        }

        WhatsAppChatPriority::updateOrCreate(
            ['chat_id' => $data['chat_id']],
            [
                'priority' => $data['priority'],
                'updated_by' => $request->user()?->id,
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Proxy media (audio/video/image) base64 from Evolution API
     */
    public function fetchMedia(Request $request)
    {
        $request->validate([
            'key'     => 'required|array',
            'message' => 'required|array',
        ]);

        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/getBase64FromMediaMessage/{$this->instance}", [
                    'message'      => [
                        'key'     => $request->input('key'),
                        'message' => $request->input('message'),
                    ],
                    'convertToMp4' => false,
                ]);

            if (! $response->successful()) {
                return response()->json(['error' => 'Falha ao buscar mídia.'], 500);
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('EvolutionAPI fetchMedia error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get profile picture URL for a number
     */
    public function profilePicture(Request $request)
    {
        $request->validate(['number' => 'required|string']);

        try {
            $response = Http::withHeaders(['apikey' => $this->apiKey])
                ->timeout(10)
                ->get("{$this->baseUrl}/chat/fetchProfilePictureUrl/{$this->instance}", [
                    'number' => $request->input('number'),
                ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
