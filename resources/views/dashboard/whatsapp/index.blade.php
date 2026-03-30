<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreAtende</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #111b21;
            overflow: hidden;
        }

        /* ─── Layout ─────────────────────────────────────────── */
        .wpp-wrapper {
            display: flex;
            height: 100vh;
            background: #111b21;
            overflow: hidden;
        }

        /* ─── Sidebar ─────────────────────────────────────────── */
        .wpp-sidebar {
            width: 420px;
            min-width: 280px;
            display: flex;
            flex-direction: column;
            background: #1f2c34;
            border-right: 1px solid #2a3942;
            flex-shrink: 0;
        }
        .wpp-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: #202c33;
            border-bottom: 1px solid #2a3942;
        }
        .wpp-sidebar-header h6 {
            color: #e9edef;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .wpp-status-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #8696a0;
            display: inline-block;
            margin-right: 6px;
            transition: background .3s;
        }
        .wpp-status-dot.connected { background: #00a884; }

        .wpp-search {
            padding: 8px 12px;
            background: #1f2c34;
            border-bottom: 1px solid #2a3942;
        }
        .wpp-search input {
            width: 100%;
            background: #2a3942;
            border: none;
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            color: #d1d7db;
            font-size: 14px;
            outline: none;
        }
        .wpp-search input::placeholder { color: #8696a0; }
        .wpp-search-wrap { position: relative; }
        .wpp-search-wrap i {
            position: absolute; left: 10px; top: 50%;
            transform: translateY(-50%);
            color: #8696a0; font-size: 14px;
        }

        .wpp-tabs {
            display: flex;
            background: #1f2c34;
            border-bottom: 1px solid #2a3942;
        }
        .wpp-tabs button {
            flex: 1;
            background: none;
            border: none;
            color: #8696a0;
            padding: 10px 0;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .2s;
        }
        .wpp-tabs button.active {
            color: #00a884;
            border-bottom-color: #00a884;
        }

        .wpp-contacts {
            flex: 1;
            overflow-y: auto;
        }
        .wpp-contacts::-webkit-scrollbar { width: 4px; }
        .wpp-contacts::-webkit-scrollbar-thumb { background: #374045; border-radius: 4px; }

        .wpp-contact-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #2a3942;
            transition: background .15s;
            gap: 12px;
        }
        .wpp-contact-item:hover, .wpp-contact-item.active { background: #2a3942; }

        .wpp-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: #374045;
            display: flex; align-items: center; justify-content: center;
            color: #8696a0; font-size: 18px;
            flex-shrink: 0;
            overflow: hidden;
        }
        .wpp-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .wpp-contact-info { flex: 1; min-width: 0; }
        .wpp-contact-name {
            color: #e9edef;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .wpp-contact-preview {
            color: #8696a0;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }
        .wpp-contact-meta { text-align: right; flex-shrink: 0; }
        .wpp-contact-time { color: #8696a0; font-size: 11px; }
        .wpp-unread-badge {
            background: #00a884;
            color: #fff;
            border-radius: 50%;
            min-width: 18px; height: 18px;
            font-size: 10px;
            display: inline-flex;
            align-items: center; justify-content: center;
            margin-top: 4px;
            padding: 0 4px;
        }
        .wpp-empty-list {
            padding: 40px 20px;
            text-align: center;
            color: #8696a0;
            font-size: 13px;
        }

        /* ─── Chat Panel ──────────────────────────────────────── */
        .wpp-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #0b141a;
            position: relative;
        }
        .wpp-chat-bg {
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23182229' fill-opacity='0.5'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: .4;
            pointer-events: none;
            z-index: 0;
        }
        .wpp-chat-placeholder {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #8696a0;
            z-index: 1;
        }
        .wpp-chat-placeholder i { font-size: 64px; margin-bottom: 16px; opacity: .5; }
        .wpp-chat-placeholder p { font-size: 14px; }

        .wpp-chat-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            background: #202c33;
            border-bottom: 1px solid #2a3942;
            z-index: 1;
        }
        .wpp-chat-header-name { color: #e9edef; font-size: 15px; font-weight: 600; }
        .wpp-chat-header-sub  { color: #8696a0; font-size: 12px; }

        .wpp-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px 6%;
            display: flex;
            flex-direction: column;
            gap: 4px;
            z-index: 1;
        }
        .wpp-messages::-webkit-scrollbar { width: 4px; }
        .wpp-messages::-webkit-scrollbar-thumb { background: #374045; border-radius: 4px; }

        .wpp-bubble {
            max-width: 65%;
            padding: 7px 12px 6px;
            border-radius: 8px;
            font-size: 13.5px;
            line-height: 1.5;
            position: relative;
            word-break: break-word;
        }
        .wpp-bubble.incoming {
            background: #1f2c34;
            color: #e9edef;
            align-self: flex-start;
            border-top-left-radius: 0;
        }
        .wpp-bubble.outgoing {
            background: #005c4b;
            color: #e9edef;
            align-self: flex-end;
            border-top-right-radius: 0;
        }
        .wpp-bubble-time {
            font-size: 10px;
            color: #8696a0;
            text-align: right;
            margin-top: 3px;
        }
        .wpp-bubble.outgoing .wpp-bubble-time { color: #8ac7ba; }

        .wpp-bubble-thumb {
            display: block;
            max-width: 220px;
            max-height: 180px;
            width: 100%;
            border-radius: 6px;
            margin-bottom: 4px;
            object-fit: cover;
            cursor: pointer;
        }
        .wpp-bubble-thumb:hover { opacity: .85; }
        .wpp-media-label {
            font-size: 11px;
            color: #8696a0;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        /* ─── Audio Player ──────────────────────────────────── */
        .wpp-audio-player {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 200px;
            padding: 4px 0;
        }
        .wpp-audio-btn {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: none;
            background: #00a884;
            color: #fff;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background .2s;
        }
        .wpp-audio-btn:hover { background: #008f72; }
        .wpp-audio-btn:disabled { background: #4a6460; cursor: default; }
        .wpp-audio-btn .ti { font-size: 16px; }
        .wpp-audio-progress {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .wpp-audio-bar-track {
            width: 100%;
            height: 3px;
            background: rgba(255,255,255,.2);
            border-radius: 2px;
            cursor: pointer;
            position: relative;
        }
        .wpp-bubble.outgoing .wpp-audio-bar-track { background: rgba(255,255,255,.25); }
        .wpp-audio-bar-fill {
            height: 100%;
            width: 0%;
            background: #00a884;
            border-radius: 2px;
            pointer-events: none;
        }
        .wpp-bubble.outgoing .wpp-audio-bar-fill { background: #fff; }
        .wpp-audio-time {
            font-size: 10px;
            color: #8696a0;
            text-align: right;
        }
        .wpp-bubble.outgoing .wpp-audio-time { color: #8ac7ba; }

        .wpp-day-divider { text-align: center; margin: 10px 0; }
        .wpp-day-divider span {
            background: #182229;
            color: #8696a0;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 8px;
            border: 1px solid #2a3942;
        }

        /* ─── Input ───────────────────────────────────────────── */
        .wpp-input-bar {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            padding: 12px 16px;
            background: #202c33;
            border-top: 1px solid #2a3942;
            z-index: 1;
        }
        .wpp-input-bar textarea {
            flex: 1;
            background: #2a3942;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            color: #d1d7db;
            font-size: 14px;
            resize: none;
            min-height: 42px;
            max-height: 120px;
            outline: none;
            line-height: 1.5;
        }
        .wpp-input-bar textarea::placeholder { color: #8696a0; }
        .wpp-send-btn {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: #00a884;
            border: none;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background .2s;
        }
        .wpp-send-btn:hover { background: #017a63; }
        .wpp-send-btn:disabled { background: #374045; cursor: not-allowed; }

        /* ─── Loading ─────────────────────────────────────────── */
        .wpp-loading {
            display: flex; align-items: center; justify-content: center;
            padding: 30px;
            color: #8696a0;
            gap: 8px;
        }
        .wpp-spinner {
            width: 18px; height: 18px;
            border: 2px solid #374045;
            border-top-color: #00a884;
            border-radius: 50%;
            animation: wpp-spin .7s linear infinite;
        }
        @keyframes wpp-spin { to { transform: rotate(360deg); } }

        .wpp-thumb-wrapper {
            position: relative;
            display: inline-block;
        }
        .wpp-media-download {
            position: absolute;
            top: 8px; right: 8px;
            border: none;
            background: rgba(0,0,0,0.5);
            color: #fff;
            border-radius: 50%;
            width: 34px; height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: opacity .2s, background .2s;
        }
        .wpp-media-download:hover { background: rgba(0,0,0,0.7); }
        .wpp-thumb-wrapper:hover .wpp-media-download { opacity: 1; }

        #wppActiveChat { min-height: 0; overflow: hidden; }
        * { scrollbar-width: thin; scrollbar-color: #374045 transparent; }

        /* ─── Labels ──────────────────────────────────────────── */
        .wpp-label-badge {
            display: inline-flex;
            align-items: center;
            padding: 1px 7px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            line-height: 1.6;
        }
        .wpp-contact-labels {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            margin-top: 4px;
        }
        /* ─── Filter Bar ──────────────────────────────────────── */
        .wpp-filter-bar {
            display: flex;
            gap: 6px;
            padding: 7px 10px;
            background: #1f2c34;
            border-bottom: 1px solid #2a3942;
            flex-shrink: 0;
        }
        .wpp-filter-bar select {
            flex: 1;
            background: #2a3942;
            border: none;
            border-radius: 7px;
            padding: 5px 8px;
            color: #d1d7db;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238696a0'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 22px;
        }
        .wpp-filter-bar select:focus { box-shadow: 0 0 0 1.5px #00a884; }
        .wpp-filter-bar select option { background: #2a3942; color: #d1d7db; }

        /* ─── Context Menu ────────────────────────────────────── */
        .wpp-ctx-menu {
            position: fixed;
            background: #2a3942;
            border: 1px solid #374045;
            border-radius: 8px;
            padding: 4px 0;
            z-index: 9999;
            min-width: 210px;
            box-shadow: 0 4px 24px rgba(0,0,0,.6);
        }
        .wpp-ctx-title {
            padding: 6px 14px 4px;
            font-size: 10px;
            font-weight: 700;
            color: #8696a0;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .wpp-ctx-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            cursor: pointer;
            font-size: 13px;
            color: #e9edef;
            transition: background .1s;
        }
        .wpp-ctx-item:hover { background: #374045; }
        .wpp-ctx-item .lbl-dot {
            width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
        }
        .wpp-ctx-item.is-assigned { font-weight: 600; }
        .wpp-ctx-item.is-assigned .lbl-check { margin-left: auto; color: #00a884; font-size: 12px; }
        .wpp-ctx-sep { height: 1px; background: #374045; margin: 4px 0; }

        .wpp-assigned-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #005c4b;
            color: #00a884;
            border-radius: 8px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: 600;
            margin-top: 3px;
        }
        .wpp-priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border-radius: 8px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: 600;
            margin-top: 3px;
            color: #fff;
        }

        /* ─── Label Modal ─────────────────────────────────────── */
        #labelModal .modal-content {
            background: #1f2c34;
            color: #e9edef;
            border: 1px solid #2a3942;
        }
        #labelModal .modal-header {
            border-bottom: 1px solid #2a3942;
            padding: 14px 18px;
        }
        #labelModal .modal-title { font-size: 15px; font-weight: 600; }
        #labelModal .modal-body  { padding: 16px 18px; }
        .lbl-mgmt-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #2a3942;
        }
        .lbl-mgmt-item:last-child { border-bottom: none; }
        .lbl-color-dot {
            width: 14px; height: 14px; border-radius: 50%; flex-shrink: 0;
        }
        .lbl-swatch {
            width: 22px; height: 22px; border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            display: inline-block;
            transition: border-color .1s, transform .1s;
            flex-shrink: 0;
        }
        .lbl-swatch:hover { transform: scale(1.15); }
        .lbl-swatch.selected { border-color: #fff !important; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="wpp-wrapper">

    <!-- ── Sidebar ── -->
    <div class="wpp-sidebar">
        <div class="wpp-sidebar-header">
            <h6>
                <span class="wpp-status-dot" id="wppStatusDot"></span>
                Chat
            </h6>
            <div style="display:flex;gap:4px;align-items:center;">
                <button style="background:none;border:none;color:#8696a0;cursor:pointer;padding:4px 6px;"
                        onclick="openLabelModal()" title="Gerenciar etiquetas">
                    <i class="ti ti-tag" style="font-size:18px;"></i>
                </button>
                <button style="background:none;border:none;color:#8696a0;cursor:pointer;padding:4px 6px;"
                        onclick="loadChats()" title="Atualizar">
                    <i class="ti ti-refresh" style="font-size:18px;"></i>
                </button>
            </div>
        </div>

        <div class="wpp-search">
            <div class="wpp-search-wrap">
                <i class="ti ti-search"></i>
                <input type="text" id="wppSearch" placeholder="Pesquisar contato…">
            </div>
        </div>

        <!-- Filter bar -->
        <div class="wpp-filter-bar">
            <select id="wppLabelSelect" onchange="setLabelFilter(this.value || null)" title="Filtrar por etiqueta">
                <option value="">Etiqueta</option>
            </select>
            <select id="wppUserSelect" onchange="setUserFilter(this.value ? parseInt(this.value) : null)" title="Filtrar por responsável">
                <option value="">Responsável</option>
            </select>
            <select id="wppPrioritySelect" onchange="setPriorityFilter(this.value || null)" title="Filtrar por prioridade">
                <option value="">Prioridade</option>
            </select>
        </div>

        <div class="wpp-tabs">
            <button class="active" id="tabContatos" onclick="switchTab('contacts')">Contatos</button>
            <button id="tabGrupos" onclick="switchTab('groups')">Grupos</button>
        </div>

        <div class="wpp-contacts" id="wppContactList">
            <div class="wpp-loading">
                <div class="wpp-spinner"></div> Carregando…
            </div>
        </div>
    </div>

    <!-- ── Chat Panel ── -->
    <div class="wpp-chat" id="wppChatPanel">
        <div class="wpp-chat-bg"></div>

        <div class="wpp-chat-placeholder" id="wppPlaceholder">
            <i class="ti ti-brand-whatsapp"></i>
            <p>Selecione um contato para conversar</p>
        </div>

        <div id="wppActiveChat" style="display:none;flex-direction:column;flex:1;z-index:1;">
            <div class="wpp-chat-header" id="wppChatHeader"></div>
            <div class="wpp-messages" id="wppMessages"></div>
            <div class="wpp-input-bar">
                <textarea id="wppMsgInput" rows="1" placeholder="Escreva uma mensagem…"
                          onkeydown="handleMsgKey(event)"></textarea>
                <button class="wpp-send-btn" id="wppSendBtn" onclick="sendMessage()" title="Enviar">
                    <i class="ti ti-send"></i>
                </button>
            </div>
        </div>
    </div>

</div>

<!-- ── Context Menu ── -->
<div class="wpp-ctx-menu" id="wppCtxMenu" style="display:none;"></div>

<!-- ── Label Management Modal ── -->
<div class="modal fade" id="labelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="ti ti-tag me-2"></i>Etiquetas</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Existing labels -->
                <div id="lblListContainer" style="margin-bottom:14px;min-height:20px;"></div>

                <!-- Create new -->
                <div style="border-top:1px solid #2a3942;padding-top:14px;">
                    <p style="font-size:11px;color:#8696a0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                        Nova etiqueta
                    </p>
                    <input type="text" id="newLabelName" placeholder="Nome da etiqueta…"
                           style="background:#2a3942;border:none;border-radius:6px;padding:8px 12px;
                                  color:#d1d7db;font-size:13px;width:100%;outline:none;margin-bottom:10px;"
                           onkeydown="if(event.key==='Enter') submitNewLabel()">
                    <div style="display:flex;gap:7px;flex-wrap:wrap;margin-bottom:12px;" id="colorSwatches"></div>
                    <button onclick="submitNewLabel()"
                            style="background:#00a884;border:none;border-radius:6px;padding:8px 14px;
                                   color:#fff;font-size:13px;cursor:pointer;width:100%;font-weight:600;">
                        Criar Etiqueta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    const ROUTES = {
        chats:       '{{ route('whatsapp.chats') }}',
        messages:    '{{ route('whatsapp.messages') }}',
        send:        '{{ route('whatsapp.send') }}',
        status:      '{{ route('whatsapp.status') }}',
        users:       '{{ route('whatsapp.users') }}',
        assign:      '{{ route('whatsapp.assign') }}',
        assignments: '{{ route('whatsapp.assignments') }}',
        media:       '{{ route('whatsapp.media') }}',
        contacts:    '{{ route('whatsapp.contacts') }}',
    };
    const CSRF = '{{ csrf_token() }}';

    let allChats        = [];
    let chatMap         = new Map(); // id (display) -> chat object (contains remoteJids)
    let contactsMap     = new Map(); // remoteJid -> contact data
    let currentTab      = 'contacts';
    let activeChatId    = null;
    let pollInterval    = null;
    let lastMsgTs       = 0;
    let systemUsers     = [];
    const audioDataMap  = {};   // msgId → { key, message }
    const audioObjMap   = {};   // msgId → HTMLAudioElement
    const mediaDataMap  = {};   // msgId → { key, message, mime?, type? }
    let chatAssignments = {}; // { chat_id: { user_id, user_name } }
    let activeUserFilter = null;
    let activePriorityFilter = null;


    document.addEventListener('DOMContentLoaded', () => {
        checkStatus();
        loadContacts().finally(() => loadChats());
        loadUsers();
        loadAssignments();
        renderLabelFilter();
        renderPriorityFilter();
        document.getElementById('wppSearch').addEventListener('input', e => {
            renderContactList(allChats, e.target.value.toLowerCase());
        });
        const ta = document.getElementById('wppMsgInput');
        ta.addEventListener('input', () => {
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
        });
        document.addEventListener('click', e => {
            const menu = document.getElementById('wppCtxMenu');
            if (!menu.contains(e.target)) closeCtxMenu();
        });
    });

    // ─── Core ─────────────────────────────────────────────────

    function loadContacts() {
        return fetch(ROUTES.contacts, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                const list = Array.isArray(data) ? data : [];
                contactsMap = new Map(list.map(c => [c.id, c]));
            })
            .catch(() => { contactsMap = new Map(); });
    }

    function resolveChatName(chat) {
        for (const jid of (chat.remoteJids || [])) {
            const c = contactsMap.get(jid);
            if (c?.name) return c.name;
        }
        return chat.name || chat.id;
    }

    function checkStatus() {
        fetch(ROUTES.status, { headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                const dot   = document.getElementById('wppStatusDot');
                const state = data?.instance?.state ?? data?.state ?? '';
                if (state === 'open') {
                    dot.classList.add('connected');
                    dot.title = 'Conectado';
                } else {
                    dot.classList.remove('connected');
                    dot.title = 'Desconectado: ' + state;
                }
            })
            .catch(() => {});
    }

    function loadChats() {
        const list = document.getElementById('wppContactList');
        list.innerHTML = '<div class="wpp-loading"><div class="wpp-spinner"></div> Carregando…</div>';
        fetch(ROUTES.chats, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    list.innerHTML = `<div class="wpp-empty-list"><i class="ti ti-alert-circle d-block mb-2" style="font-size:28px;color:#e15f41"></i>${data.error}</div>`;
                    return;
                }
                allChats = Array.isArray(data) ? data : [];
                allChats = allChats.map(c => ({ ...c, displayName: resolveChatName(c) }));
                chatMap  = new Map(allChats.map(c => [c.id, c]));
                renderContactList(allChats);
            })
            .catch(() => {
                list.innerHTML = `<div class="wpp-empty-list"><i class="ti ti-wifi-off d-block mb-2" style="font-size:28px"></i>Sem conexão com a API</div>`;
            });
    }

    function renderContactList(chats, query = '') {
        const list = document.getElementById('wppContactList');
        let filtered = chats.filter(c => currentTab === 'groups' ? c.isGroup : !c.isGroup);
        if (query) {
            filtered = filtered.filter(c => (c.name || c.id || '').toLowerCase().includes(query));
        }
        if (activeLabel) {
            const cl = getChatLabels();
            filtered = filtered.filter(c => (cl[c.id] || []).includes(activeLabel));
        }
        if (activeUserFilter !== null) {
            filtered = filtered.filter(c => chatAssignments[c.id]?.user_id == activeUserFilter);
        }
        const chatPriorities = getChatPriorities();
        if (activePriorityFilter) {
            filtered = filtered.filter(c => chatPriorities[c.id] === activePriorityFilter);
        }
        filtered = sortChatsByPriority(filtered, chatPriorities);
        if (!filtered.length) {
            list.innerHTML = '<div class="wpp-empty-list"><i class="ti ti-user-off d-block mb-2" style="font-size:28px"></i>Nenhum resultado</div>';
            return;
        }
        const labels   = getLabels();
        const chatLbls = getChatLabels();
        list.innerHTML = filtered.map(chat => {
            const assignedLbls = (chatLbls[chat.id] || []).filter(id => labels[id]);
            const badgesHtml = assignedLbls
                .map(id => `<span class="wpp-label-badge" style="background:${labels[id].color};">${escHtml(labels[id].name)}</span>`)
                .join('');
            const assignment = chatAssignments[chat.id];
            const priority = getPriorityConfig(chatPriorities[chat.id]);
            const assignedBadge = assignment
                ? `<span class="wpp-assigned-badge"><i class="ti ti-user" style="font-size:9px;"></i>${escHtml(assignment.user_name)}</span>`
                : '';
            const priorityBadge = priority
                ? `<span class="wpp-priority-badge" style="background:${priority.color};"><i class="ti ti-flag-2" style="font-size:9px;"></i>${escHtml(priority.name)}</span>`
                : '';
            const extraBadges = badgesHtml || assignedBadge || priorityBadge
                ? `<div class="wpp-contact-labels">${priorityBadge}${badgesHtml}${assignedBadge}</div>`
                : '';
            return `
            <div class="wpp-contact-item ${activeChatId === chat.id ? 'active' : ''}"
                 onclick="openChat('${escHtml(chat.id)}', '${escHtml(chat.displayName || chat.name || chat.id)}', ${chat.isGroup ? 'true' : 'false'})"
                 oncontextmenu="showCtxMenu(event, '${escHtml(chat.id)}', '${escHtml(chat.displayName || chat.name || chat.id)}')">
                <div class="wpp-avatar">
                    ${chat.isGroup ? '<i class="ti ti-users"></i>' : '<i class="ti ti-user"></i>'}
                </div>
                <div class="wpp-contact-info">
                    <div class="wpp-contact-name">${escHtml(chat.displayName || chat.name || chat.id)}</div>
                    <div class="wpp-contact-preview">${escHtml(chat.lastMsg || '')}</div>
                    ${extraBadges}
                </div>
                <div class="wpp-contact-meta">
                    <div class="wpp-contact-time">${formatTime(chat.timestamp)}</div>
                    ${chat.unread > 0 ? `<div class="wpp-unread-badge">${chat.unread}</div>` : ''}
                </div>
            </div>`;
        }).join('');
    }

    function switchTab(tab) {
        currentTab = tab;
        document.getElementById('tabContatos').classList.toggle('active', tab === 'contacts');
        document.getElementById('tabGrupos').classList.toggle('active', tab === 'groups');
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
    }

    function openChat(chatId, chatName, isGroup) {
        activeChatId = chatId;
        clearInterval(pollInterval);
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
        document.getElementById('wppPlaceholder').style.display = 'none';
        const activeChat = document.getElementById('wppActiveChat');
        activeChat.style.display = 'flex';
        document.getElementById('wppChatHeader').innerHTML = `
        <div class="wpp-avatar" style="width:40px;height:40px;font-size:16px;">
            <i class="ti ${isGroup ? 'ti-users' : 'ti-user'}"></i>
        </div>
        <div>
            <div class="wpp-chat-header-name">${escHtml(chatName)}</div>
            <div class="wpp-chat-header-sub">${escHtml(chatId)}</div>
        </div>`;
        loadMessages(chatId);
        pollInterval = setInterval(() => loadMessages(chatId, true), 6000);
    }

    function loadMessages(chatId, silent = false) {
        const box = document.getElementById('wppMessages');
        if (!silent) {
            box.innerHTML = '<div class="wpp-loading"><div class="wpp-spinner"></div> Carregando mensagens…</div>';
        }
        const chat = chatMap.get(chatId);
        const chatIds = chat?.remoteJids || [chatId];
        fetch(ROUTES.messages, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ chat_id: chatId, chat_ids: chatIds, limit: 50 }),
        })
            .then(r => r.json())
            .then(msgs => {
                if (!Array.isArray(msgs) || activeChatId !== chatId) return;
                const lastTs = msgs.length ? msgs[msgs.length - 1].timestamp : 0;
                if (silent && lastTs === lastMsgTs) return;
                lastMsgTs = lastTs;
                renderMessages(msgs);
            })
            .catch(() => {
                if (!silent) box.innerHTML = '<div class="wpp-empty-list">Erro ao carregar mensagens.</div>';
            });
    }

    function renderMessages(msgs) {
        const box = document.getElementById('wppMessages');
        const wasAtBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 80;
        let html = '';
        let lastDay = '';
        msgs.forEach(msg => {
            const day = formatDay(msg.timestamp);
            if (day !== lastDay) {
                lastDay = day;
                html += `<div class="wpp-day-divider"><span>${day}</span></div>`;
            }
            const cls = msg.fromMe ? 'outgoing' : 'incoming';
            const isAudio = msg.type === 'audioMessage' || msg.type === 'pttMessage';
            let mediaHtml = '';

            if (isAudio && msg.audioData) {
                audioDataMap[msg.id] = msg.audioData;
                const pttLabel = msg.type === 'pttMessage'
                    ? '<div class="wpp-media-label"><i class="ti ti-microphone"></i> Mensagem de voz</div>'
                    : '<div class="wpp-media-label"><i class="ti ti-volume"></i> Áudio</div>';
                mediaHtml = `${pttLabel}
                    <div class="wpp-audio-player">
                        <button class="wpp-audio-btn" id="audiobtn-${msg.id}" onclick="toggleAudio('${msg.id}')">
                            <i class="ti ti-player-play"></i>
                        </button>
                        <div class="wpp-audio-progress">
                            <div class="wpp-audio-bar-track" onclick="seekAudio('${msg.id}', event)" id="audiotrack-${msg.id}">
                                <div class="wpp-audio-bar-fill" id="audiofill-${msg.id}"></div>
                            </div>
                            <div class="wpp-audio-time" id="audiotime-${msg.id}">0:00</div>
                        </div>
                    </div>`;
            } else if (msg.thumbnail) {
                if (msg.mediaData) mediaDataMap[msg.id] = msg.mediaData;
                const src  = msg.thumbnail.startsWith('data:') ? msg.thumbnail : `data:image/jpeg;base64,${msg.thumbnail}`;
                const icon = msg.type === 'videoMessage'
                    ? '<i class="ti ti-video"></i> Vídeo'
                    : msg.type === 'stickerMessage'
                        ? '<i class="ti ti-mood-smile"></i> Sticker'
                        : '<i class="ti ti-photo"></i> Imagem';
                const downloadBtn = msg.mediaData ? `<button class="wpp-media-download" onclick="downloadMedia('${msg.id}')" title="Baixar"><i class="ti ti-download"></i></button>` : '';
                mediaHtml = `<div class="wpp-media-label">${icon}</div><div class="wpp-thumb-wrapper">${downloadBtn}<img class="wpp-bubble-thumb" src="${src}" alt="mídia" onclick="this.requestFullscreen?.()"></div>`;
            }

            const textHtml = msg.text
                ? `<div>${escHtml(msg.text)}</div>`
                : (!msg.thumbnail && !isAudio ? `<div class="wpp-media-label"><i class="ti ti-file"></i> ${escHtml(msg.type ?? 'mídia')}</div>` : '');
            html += `<div class="wpp-bubble ${cls}">${mediaHtml}${textHtml}<div class="wpp-bubble-time">${formatHour(msg.timestamp)}</div></div>`;
        });
        box.innerHTML = html || '<div class="wpp-empty-list">Nenhuma mensagem ainda.</div>';
        if (wasAtBottom || msgs.length < 5) box.scrollTop = box.scrollHeight;
    }

    async function downloadMedia(msgId) {
        const data = mediaDataMap[msgId];
        if (!data) return;
        const btn = event?.currentTarget;
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader" style="animation:spin 1s linear infinite"></i>';
        }
        try {
            const res = await fetch(ROUTES.media, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ key: data.key, message: data.message }),
            });
            const json = await res.json();
            if (!json.base64) throw new Error(json.error || 'Sem base64');

            const rawB64 = json.base64;
            const b64str = rawB64.includes(',') ? rawB64.split(',')[1] : rawB64;
            // Prefer backend-provided mime; fallback to Evolution response; last resort jpeg
            const hintedMime = data.mime || json.mediaType || json.mimetype || 'image/jpeg';
            const rawMime    = hintedMime.split(';')[0].trim();
            const bytes = atob(b64str);
            const buf   = new Uint8Array(bytes.length);
            for (let i = 0; i < bytes.length; i++) buf[i] = bytes.charCodeAt(i);
            const blob = new Blob([buf], { type: rawMime });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href = url;
            const ext = rawMime.split('/')[1] || mimeToExt(rawMime) || 'jpg';
            a.download = `midia-${msgId}.${ext}`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        } catch (e) {
            console.error('Download media error', e);
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-download"></i>';
            }
        }
    }

    // ─── Audio Player ─────────────────────────────────────────

    function mimeToExt(mime) {
        const map = {
            'image/jpeg': 'jpg',
            'image/png': 'png',
            'image/webp': 'webp',
            'image/gif': 'gif',
            'video/mp4': 'mp4',
            'video/3gpp': '3gp',
            'video/quicktime': 'mov',
            'application/pdf': 'pdf',
        };
        return map[mime] || null;
    }
    function fmtAudioTime(s) {
        const m = Math.floor(s / 60);
        const sec = String(Math.floor(s % 60)).padStart(2, '0');
        return `${m}:${sec}`;
    }

    function stopAllAudio(exceptId) {
        Object.entries(audioObjMap).forEach(([id, audio]) => {
            if (id !== exceptId && !audio.paused) {
                audio.pause();
                const btn = document.getElementById(`audiobtn-${id}`);
                if (btn) btn.innerHTML = '<i class="ti ti-player-play"></i>';
            }
        });
    }

    function bindAudioEvents(msgId, audio) {
        const btn   = () => document.getElementById(`audiobtn-${msgId}`);
        const fill  = () => document.getElementById(`audiofill-${msgId}`);
        const tspan = () => document.getElementById(`audiotime-${msgId}`);

        audio.addEventListener('timeupdate', () => {
            const pct = audio.duration ? (audio.currentTime / audio.duration) * 100 : 0;
            const f = fill(); if (f) f.style.width = pct + '%';
            const t = tspan(); if (t) t.textContent = fmtAudioTime(audio.currentTime);
        });
        audio.addEventListener('ended', () => {
            const b = btn(); if (b) b.innerHTML = '<i class="ti ti-player-play"></i>';
            const f = fill(); if (f) f.style.width = '0%';
            const t = tspan(); if (t) t.textContent = fmtAudioTime(audio.duration || 0);
        });
        audio.addEventListener('loadedmetadata', () => {
            const t = tspan(); if (t) t.textContent = fmtAudioTime(audio.duration);
        });
    }

    function seekAudio(msgId, event) {
        const audio = audioObjMap[msgId];
        if (!audio || !audio.duration) return;
        const track = document.getElementById(`audiotrack-${msgId}`);
        if (!track) return;
        const rect = track.getBoundingClientRect();
        const pct  = (event.clientX - rect.left) / rect.width;
        audio.currentTime = pct * audio.duration;
    }

    async function toggleAudio(msgId) {
        const btn = document.getElementById(`audiobtn-${msgId}`);
        if (!btn) return;

        // Already loaded
        if (audioObjMap[msgId]) {
            const audio = audioObjMap[msgId];
            if (audio.paused) {
                stopAllAudio(msgId);
                audio.play();
                btn.innerHTML = '<i class="ti ti-player-pause"></i>';
            } else {
                audio.pause();
                btn.innerHTML = '<i class="ti ti-player-play"></i>';
            }
            return;
        }

        // Load from backend
        const data = audioDataMap[msgId];
        if (!data) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="ti ti-loader" style="animation:spin 1s linear infinite"></i>';

        try {
            const res = await fetch(ROUTES.media, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ key: data.key, message: data.message }),
            });
            const json = await res.json();
            console.debug('fetchMedia response keys:', Object.keys(json), 'mediaType:', json.mediaType || json.mimetype);
            if (!json.base64) throw new Error(json.error || 'Sem base64');

            // Evolution API pode retornar o base64 já com prefixo "data:...;base64,"
            const rawB64 = json.base64;
            const b64str = rawB64.includes(',') ? rawB64.split(',')[1] : rawB64;

            // MIME: remove parâmetros extras ("; codecs=opus") que invalidam o Blob
            const rawMime = json.mediaType || json.mimetype || 'audio/ogg';
            const baseMime = rawMime.split(';')[0].trim();

            // Converte base64 → Uint8Array → Blob → URL (mais compatível que data URL)
            const bytes = atob(b64str);
            const buf   = new Uint8Array(bytes.length);
            for (let i = 0; i < bytes.length; i++) buf[i] = bytes.charCodeAt(i);
            const blob    = new Blob([buf], { type: baseMime });
            const blobUrl = URL.createObjectURL(blob);

            const audio = new Audio(blobUrl);
            // Libera o objeto URL ao terminar
            audio.addEventListener('ended',  () => URL.revokeObjectURL(blobUrl), { once: true });

            audioObjMap[msgId] = audio;
            bindAudioEvents(msgId, audio);

            stopAllAudio(msgId);
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-player-pause"></i>';
            audio.play().catch(err => {
                console.error('Audio play error', err);
                btn.innerHTML = '<i class="ti ti-player-play"></i>';
            });
        } catch (e) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-player-play"></i>';
            console.error('Audio load error', e);
        }
    }

    function sendMessage() {
        const input = document.getElementById('wppMsgInput');
        const btn   = document.getElementById('wppSendBtn');
        const text  = input.value.trim();
        if (!text || !activeChatId) return;
        btn.disabled = true;
        fetch(ROUTES.send, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ chat_id: activeChatId, message: text }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    input.style.height = 'auto';
                    const box = document.getElementById('wppMessages');
                    const now = Math.floor(Date.now() / 1000);
                    box.insertAdjacentHTML('beforeend', `
                        <div class="wpp-bubble outgoing">
                            ${escHtml(text)}
                            <div class="wpp-bubble-time">${formatHour(now)}</div>
                        </div>`);
                    box.scrollTop = box.scrollHeight;
                }
            })
            .catch(() => {})
            .finally(() => { btn.disabled = false; });
    }

    function handleMsgKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    }

    // ─── Labels ───────────────────────────────────────────────

    const LABELS_KEY    = 'wpp_labels';
    const CHAT_LBLS_KEY = 'wpp_chat_labels';
    const PRIORITY_KEY  = 'wpp_chat_priority';
    const LABEL_COLORS  = [
        '#25d366','#00a884','#34b7f1','#5b8def',
        '#e15f41','#f0b429','#e67e22','#8e44ad',
        '#1abc9c','#e91e63',
    ];
    const PRIORITIES = [
        { id: 'maxima', name: 'Máxima', color: '#e74c3c', rank: 4 },
        { id: 'alta', name: 'Alta', color: '#f39c12', rank: 3 },
        { id: 'media', name: 'Média', color: '#3498db', rank: 2 },
        { id: 'baixa', name: 'Baixa', color: '#2ecc71', rank: 1 },
    ];
    const PRIORITY_BY_ID = PRIORITIES.reduce((acc, p) => { acc[p.id] = p; return acc; }, {});

    let activeLabel   = null;
    let selectedColor = LABEL_COLORS[0];

    function getLabels()       { return JSON.parse(localStorage.getItem(LABELS_KEY)    || '{}'); }
    function saveLabels(l)     { localStorage.setItem(LABELS_KEY,    JSON.stringify(l)); }
    function getChatLabels()   { return JSON.parse(localStorage.getItem(CHAT_LBLS_KEY) || '{}'); }
    function saveChatLabels(l) { localStorage.setItem(CHAT_LBLS_KEY, JSON.stringify(l)); }
    function getChatPriorities(){ return JSON.parse(localStorage.getItem(PRIORITY_KEY) || '{}'); }
    function saveChatPriorities(p) { localStorage.setItem(PRIORITY_KEY, JSON.stringify(p)); }
    function getPriorityConfig(priorityId) { return priorityId ? PRIORITY_BY_ID[priorityId] || null : null; }
    function sortChatsByPriority(chats, chatPriorities) {
        return [...chats].sort((a, b) => {
            const pa = getPriorityConfig(chatPriorities[a.id])?.rank || 0;
            const pb = getPriorityConfig(chatPriorities[b.id])?.rank || 0;
            if (pa !== pb) return pb - pa;
            return (b.timestamp || 0) - (a.timestamp || 0);
        });
    }

    function createLabel(name, color) {
        if (!name.trim()) return;
        const labels  = getLabels();
        const id      = 'lbl_' + Date.now();
        labels[id]    = { name: name.trim(), color };
        saveLabels(labels);
        renderLabelFilter();
        renderLblListInModal();
    }

    function deleteLabel(id) {
        const labels = getLabels();
        delete labels[id];
        saveLabels(labels);
        const cl = getChatLabels();
        Object.keys(cl).forEach(cid => { cl[cid] = (cl[cid] || []).filter(l => l !== id); });
        saveChatLabels(cl);
        if (activeLabel === id) activeLabel = null;
        renderLabelFilter();
        renderLblListInModal();
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
    }

    function toggleChatLabel(chatId, labelId) {
        const cl = getChatLabels();
        if (!cl[chatId]) cl[chatId] = [];
        const idx = cl[chatId].indexOf(labelId);
        if (idx >= 0) cl[chatId].splice(idx, 1);
        else cl[chatId].push(labelId);
        saveChatLabels(cl);
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
        // refresh open menu
        if (ctxChatId === chatId) buildCtxMenu(chatId);
    }

    function setLabelFilter(labelId) {
        activeLabel = labelId || null;
        renderLabelFilter();
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
    }

    function setPriority(chatId, priorityId) {
        const priorities = getChatPriorities();
        if (priorityId) priorities[chatId] = priorityId;
        else delete priorities[chatId];
        saveChatPriorities(priorities);
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
        renderPriorityFilter();
        if (ctxChatId === chatId) buildCtxMenu(chatId);
    }

    function setPriorityFilter(priorityId) {
        activePriorityFilter = priorityId || null;
        renderPriorityFilter();
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
    }

    function renderLabelFilter() {
        const labels  = getLabels();
        const entries = Object.entries(labels);
        const sel     = document.getElementById('wppLabelSelect');
        sel.innerHTML = `<option value="">Etiqueta</option>` +
            entries.map(([id, lbl]) =>
                `<option value="${id}" ${activeLabel === id ? 'selected' : ''}>${escHtml(lbl.name)}</option>`
            ).join('');
    }

    function renderPriorityFilter() {
        const sel = document.getElementById('wppPrioritySelect');
        sel.innerHTML = `<option value="">Prioridade</option>` +
            PRIORITIES.map(p =>
                `<option value="${p.id}" ${activePriorityFilter === p.id ? 'selected' : ''}>${escHtml(p.name)}</option>`
            ).join('');
    }

    // ─── Label Modal ──────────────────────────────────────────

    let _labelModal = null;

    function openLabelModal() {
        renderLblListInModal();
        renderColorSwatches();
        if (!_labelModal) _labelModal = new bootstrap.Modal(document.getElementById('labelModal'));
        _labelModal.show();
    }

    function renderLblListInModal() {
        const labels  = getLabels();
        const entries = Object.entries(labels);
        const cont    = document.getElementById('lblListContainer');
        if (!entries.length) {
            cont.innerHTML = '<p style="color:#8696a0;font-size:12px;text-align:center;margin:0;">Nenhuma etiqueta ainda</p>';
            return;
        }
        cont.innerHTML = entries.map(([id, lbl]) => `
            <div class="lbl-mgmt-item">
                <span class="lbl-color-dot" style="background:${lbl.color};"></span>
                <span style="flex:1;font-size:13px;">${escHtml(lbl.name)}</span>
                <button onclick="deleteLabel('${id}')"
                        style="background:none;border:none;color:#e15f41;cursor:pointer;padding:2px 4px;line-height:1;">
                    <i class="ti ti-trash" style="font-size:15px;"></i>
                </button>
            </div>`).join('');
    }

    function renderColorSwatches() {
        document.getElementById('colorSwatches').innerHTML = LABEL_COLORS.map(c => `
            <span class="lbl-swatch ${selectedColor === c ? 'selected' : ''}"
                  style="background:${c};border-color:${selectedColor === c ? '#fff' : 'transparent'};"
                  onclick="selectColor('${c}')">
            </span>`).join('');
    }

    function selectColor(c) {
        selectedColor = c;
        renderColorSwatches();
    }

    function submitNewLabel() {
        const inp = document.getElementById('newLabelName');
        createLabel(inp.value, selectedColor);
        inp.value = '';
    }

    // ─── Context Menu ─────────────────────────────────────────

    let ctxChatId = null;

    function showCtxMenu(e, chatId, chatName) {
        e.preventDefault();
        // if (permission === 'admin') return;
        ctxChatId = chatId;
        buildCtxMenu(chatId);
        const menu = document.getElementById('wppCtxMenu');
        menu.style.display = 'block';
        // position after render so offsetHeight is available
        requestAnimationFrame(() => {
            const x = Math.min(e.clientX, window.innerWidth  - menu.offsetWidth  - 8);
            const y = Math.min(e.clientY, window.innerHeight - menu.offsetHeight - 8);
            menu.style.left = x + 'px';
            menu.style.top  = y + 'px';
        });
    }

    function buildCtxMenu(chatId) {
        const labels        = getLabels();
        const cl            = getChatLabels();
        const priorities    = getChatPriorities();
        const assignedLbls  = cl[chatId] || [];
        const entries       = Object.entries(labels);
        const currentAssign = chatAssignments[chatId];
        const currentPriority = priorities[chatId] || null;
        const menu          = document.getElementById('wppCtxMenu');
        menu.innerHTML = `
            <div class="wpp-ctx-title">Responsável</div>
            ${systemUsers.length
            ? [
                `<div class="wpp-ctx-item ${!currentAssign ? 'is-assigned' : ''}"
                          onclick="assignChat('${chatId}', null)">
                        <i class="ti ti-user-off" style="font-size:14px;color:#8696a0;"></i>
                        Ninguém
                        ${!currentAssign ? '<span class="lbl-check ms-auto">✓</span>' : ''}
                     </div>`,
                ...systemUsers.map(u => {
                    const active = currentAssign?.user_id == u.id;
                    return `<div class="wpp-ctx-item ${active ? 'is-assigned' : ''}"
                                     onclick="assignChat('${chatId}', ${u.id})">
                                    <i class="ti ti-user" style="font-size:14px;color:#8696a0;"></i>
                                    ${escHtml(u.name)}
                                    ${active ? '<span class="lbl-check ms-auto">✓</span>' : ''}
                                </div>`;
                })
            ].join('')
            : `<div class="wpp-ctx-item" style="color:#8696a0;font-size:12px;pointer-events:none;">
                       Nenhum usuário ativo
                   </div>`
        }
            <div class="wpp-ctx-sep"></div>
            <div class="wpp-ctx-title">Prioridade</div>
            <div class="wpp-ctx-item ${!currentPriority ? 'is-assigned' : ''}" onclick="setPriority('${chatId}', null)">
                <i class="ti ti-flag-off" style="font-size:14px;color:#8696a0;"></i>
                Sem prioridade
                ${!currentPriority ? '<span class="lbl-check ms-auto">✓</span>' : ''}
            </div>
            ${PRIORITIES.map(p => {
            const active = currentPriority === p.id;
            return `<div class="wpp-ctx-item ${active ? 'is-assigned' : ''}" onclick="setPriority('${chatId}', '${p.id}')">
                            <span class="lbl-dot" style="background:${p.color};"></span>
                            ${escHtml(p.name)}
                            ${active ? '<span class="lbl-check ms-auto">✓</span>' : ''}
                        </div>`;
        }).join('')}
            <div class="wpp-ctx-sep"></div>
            <div class="wpp-ctx-title">Etiquetas</div>
            ${entries.length
            ? entries.map(([id, lbl]) => {
                const active = assignedLbls.includes(id);
                return `<div class="wpp-ctx-item ${active ? 'is-assigned' : ''}"
                                 onclick="toggleChatLabel('${chatId}','${id}')">
                                <span class="lbl-dot" style="background:${lbl.color};"></span>
                                ${escHtml(lbl.name)}
                                ${active ? '<span class="lbl-check ms-auto">✓</span>' : ''}
                            </div>`;
            }).join('')
            : `<div class="wpp-ctx-item" style="color:#8696a0;font-size:12px;pointer-events:none;">
                       Nenhuma etiqueta criada
                   </div>`
        }
            <div class="wpp-ctx-sep"></div>
            <div class="wpp-ctx-item" onclick="openLabelModal();closeCtxMenu();">
                <i class="ti ti-settings" style="font-size:14px;color:#8696a0;"></i>
                Gerenciar etiquetas
            </div>`;
    }

    function closeCtxMenu() {
        document.getElementById('wppCtxMenu').style.display = 'none';
        ctxChatId = null;
    }

    // ─── Assignments ──────────────────────────────────────────

    function loadUsers() {
        fetch(ROUTES.users, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                systemUsers = Array.isArray(data) ? data : [];
                renderUserFilter();
            })
            .catch(() => {});
    }

    function loadAssignments() {
        fetch(ROUTES.assignments, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                chatAssignments = data || {};
                renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
            })
            .catch(() => {});
    }

    function assignChat(chatId, userId) {
        fetch(ROUTES.assign, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ chat_id: chatId, user_id: userId }),
        })
            .then(r => r.json())
            .then(() => {
                if (userId) {
                    const user = systemUsers.find(u => u.id == userId);
                    chatAssignments[chatId] = { user_id: userId, user_name: user ? user.name : '' };
                } else {
                    delete chatAssignments[chatId];
                }
                renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
                renderUserFilter();
                if (ctxChatId === chatId) buildCtxMenu(chatId);
            })
            .catch(() => {});
    }

    function setUserFilter(userId) {
        activeUserFilter = userId;
        renderUserFilter();
        renderContactList(allChats, document.getElementById('wppSearch').value.toLowerCase());
    }

    function renderUserFilter() {
        const sel = document.getElementById('wppUserSelect');
        sel.innerHTML = `<option value="">Responsável</option>` +
            systemUsers.map(u =>
                `<option value="${u.id}" ${activeUserFilter == u.id ? 'selected' : ''}>${escHtml(u.name)}</option>`
            ).join('');
    }

    // ─── Helpers ──────────────────────────────────────────────

    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function formatTime(ts) {
        if (!ts) return '';
        const d = new Date(ts * 1000), now = new Date();
        if ((now - d) / 1000 < 86400 && d.getDate() === now.getDate())
            return d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
    }

    function formatHour(ts) {
        if (!ts) return '';
        return new Date(ts * 1000).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    }

    function formatDay(ts) {
        if (!ts) return '';
        const d = new Date(ts * 1000), now = new Date();
        const yesterday = new Date(now);
        yesterday.setDate(now.getDate() - 1);
        if (d.toDateString() === now.toDateString()) return 'Hoje';
        if (d.toDateString() === yesterday.toDateString()) return 'Ontem';
        return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }
</script>
</body>
</html>
