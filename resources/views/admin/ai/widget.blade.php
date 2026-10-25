<!-- ══════════════════════════════════
     AI FLOATING WIDGET
══════════════════════════════════ -->

<style>
    @keyframes typing-dot {
        0%,80%,100% { transform:scale(1); opacity:.4; }
        40%          { transform:scale(1.4); opacity:1; }
    }
    @keyframes panelIn {
        from { opacity:0; transform:translateY(10px) scale(.97); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    @keyframes btnFadeOut {
        from { opacity:1; transform:scale(1); }
        to   { opacity:0; transform:scale(.8); pointer-events:none; }
    }
    @keyframes btnFadeIn {
        from { opacity:0; transform:scale(.8); }
        to   { opacity:1; transform:scale(1); }
    }
    #aiChatBody::-webkit-scrollbar { width:3px; }
    #aiChatBody::-webkit-scrollbar-track { background:transparent; }
    #aiChatBody::-webkit-scrollbar-thumb { background:#dde3ed; border-radius:4px; }
    #aiPanel.ai-opening { animation: panelIn .22s cubic-bezier(.22,1,.36,1) both; }
    #aiDragWrapper.btn-hide { animation: btnFadeOut .18s ease forwards; }
    #aiDragWrapper.btn-show { animation: btnFadeIn .22s ease forwards; }
</style>

<!-- Mobile backdrop -->
<div id="aiBackdrop" class="hidden fixed inset-0 z-[9990] lg:hidden"
     style="background:rgba(0,0,0,.25); backdrop-filter:blur(2px);"
     onclick="closeAiPanel()"></div>

<!-- Chat Panel -->
<div id="aiPanel"
     class="hidden fixed z-[9998] flex flex-col"
     style="width:380px; height:520px; bottom:24px; right:24px;
            border-radius:16px; overflow:hidden;
            background:#fff;
            box-shadow:0 20px 60px rgba(15,30,75,.18), 0 2px 8px rgba(15,30,75,.06);
            border:1px solid #dde3ed;">

    <!-- Header -->
    <div id="aiPanelHeader"
         class="flex items-center gap-3 px-4 py-3.5 select-none cursor-grab flex-shrink-0"
         style="background:#1e3a6e;">

        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
            <svg width="26" height="24" viewBox="0 0 30 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="5" width="22" height="14" rx="3" stroke="white" stroke-width="1.5" fill="none"/>
                <circle cx="7" cy="12" r="2.2" fill="white"/>
                <circle cx="15" cy="12" r="2.2" fill="white"/>
                <path d="M11 2.5v2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 2.5h6" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M4 19v2.5M18 19v2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M23 10l5 2-5 2" stroke="rgba(255,255,255,.5)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-semibold text-white leading-none tracking-tight">SIGA</h3>
            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.55);">AI Asisten</p>
        </div>

        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>

        <button onclick="closeAiPanel()"
                class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 ml-1 transition-colors"
                style="background:rgba(255,255,255,.1);"
                onmouseover="this.style.background='rgba(255,255,255,.2)'"
                onmouseout="this.style.background='rgba(255,255,255,.1)'"
                title="Tutup">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Quick suggestions -->
    <div class="px-3 py-2.5 flex gap-1.5 overflow-x-auto flex-shrink-0"
         style="border-bottom:1px solid #f0f2f7; background:#f7f9fc;
                scrollbar-width:none;">
        <button class="ai-suggestion flex-shrink-0 px-2.5 py-1 rounded-lg text-xs font-medium
                       bg-white border border-slate-200 text-slate-500
                       hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-colors"
                style="white-space:nowrap;">
            Jumlah mahasiswa TI
        </button>
        <button class="ai-suggestion flex-shrink-0 px-2.5 py-1 rounded-lg text-xs font-medium
                       bg-white border border-slate-200 text-slate-500
                       hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-colors"
                style="white-space:nowrap;">
            Wilayah promosi terbaik
        </button>
        <button class="ai-suggestion flex-shrink-0 px-2.5 py-1 rounded-lg text-xs font-medium
                       bg-white border border-slate-200 text-slate-500
                       hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-colors"
                style="white-space:nowrap;">
            Prediksi mahasiswa 2028
        </button>
    </div>

    <!-- Data Panel Buttons -->
    <div class="px-3 py-2.5 flex gap-1.5 flex-shrink-0"
         style="border-bottom:1px solid #f0f2f7; background:#fff; display:flex; flex-direction:row; gap:8px;">
        <button onclick="loadTrendInPanel()"
            style="flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
                   padding:7px 10px; border-radius:8px;
                   border:1px solid #dde3ed; background:#fff;
                   color:#1e3a6e; font-size:12px; font-weight:500;
                   cursor:pointer; transition:all .15s ease;"
            onmouseover="this.style.background='#e8eef7'"
            onmouseout="this.style.background='#fff'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="#1e3a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                <polyline points="16 7 22 7 22 13"/>
            </svg>
            Trend
        </button>
        <button onclick="loadAnomalyInPanel()"
            style="flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
                   padding:7px 10px; border-radius:8px;
                   border:1px solid #dde3ed; background:#fff;
                   color:#b45309; font-size:12px; font-weight:500;
                   cursor:pointer; transition:all .15s ease;"
            onmouseover="this.style.background='#fff8ed'"
            onmouseout="this.style.background='#fff'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            Anomaly
        </button>
        <button onclick="loadDistributionInPanel()"
            style="flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
                   padding:7px 10px; border-radius:8px;
                   border:1px solid #dde3ed; background:#fff;
                   color:#065f46; font-size:12px; font-weight:500;
                   cursor:pointer; transition:all .15s ease;"
            onmouseover="this.style.background='#ecfdf5'"
            onmouseout="this.style.background='#fff'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="#065f46" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.21 15.89A10 10 0 118 2.83"/>
                <path d="M22 12A10 10 0 0012 2v10z"/>
            </svg>
            Distribution
        </button>
    </div>

    <!-- Chat messages -->
    <div id="aiChatBody" class="flex-1 overflow-y-auto px-4 py-4 space-y-3"
         style="scroll-behavior:smooth;">
        <div class="flex gap-2 items-end">
            <div style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-bottom:2px;">
                <svg width="18" height="17" viewBox="0 0 30 26" fill="none">
                    <rect x="1" y="5" width="22" height="14" rx="3" stroke="#1e3a6e" stroke-width="1.5" fill="none"/>
                    <circle cx="7" cy="12" r="2.2" fill="#1e3a6e"/>
                    <circle cx="15" cy="12" r="2.2" fill="#1e3a6e"/>
                    <path d="M11 2.5v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M8 2.5h6" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M4 19v2.5M18 19v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="max-w-[82%] px-3.5 py-2.5 rounded-2xl rounded-bl-sm text-sm leading-relaxed"
                 style="background:#f0f4fb; color:#1e293b; font-size:12.5px;">
               <div id="aiBriefing"></div>
            </div>
        </div>
    </div>

    <!-- Typing indicator -->
    <div id="aiTyping" class="hidden px-4 pb-2">
        <div class="flex gap-2 items-end">
            <div style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="17" viewBox="0 0 30 26" fill="none">
                    <rect x="1" y="5" width="22" height="14" rx="3" stroke="#1e3a6e" stroke-width="1.5" fill="none"/>
                    <circle cx="7" cy="12" r="2.2" fill="#1e3a6e"/>
                    <circle cx="15" cy="12" r="2.2" fill="#1e3a6e"/>
                    <path d="M11 2.5v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M8 2.5h6" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M4 19v2.5M18 19v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="px-3.5 py-2.5 rounded-2xl rounded-bl-sm flex gap-1.5 items-center"
                 style="background:#f0f4fb;">
                <span class="w-1.5 h-1.5 rounded-full" style="background:#1e3a6e; opacity:.5; animation:typing-dot .8s ease infinite 0s; display:inline-block;"></span>
                <span class="w-1.5 h-1.5 rounded-full" style="background:#1e3a6e; opacity:.5; animation:typing-dot .8s ease infinite .15s; display:inline-block;"></span>
                <span class="w-1.5 h-1.5 rounded-full" style="background:#1e3a6e; opacity:.5; animation:typing-dot .8s ease infinite .3s; display:inline-block;"></span>
            </div>
        </div>
    </div>

    <!-- Input bar -->
    <div class="px-3 py-3 flex-shrink-0" style="border-top:1px solid #f0f2f7; background:#fff;">
        <div class="flex gap-2 items-end">
            <textarea
                id="aiInput"
                rows="1"
                placeholder="Tanyakan sesuatu..."
                class="flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50
                       px-3.5 py-2 text-sm leading-relaxed
                       focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-800
                       placeholder-slate-400 transition-colors"
                style="max-height:96px; overflow-y:auto; font-size:13px;"
                onkeydown="aiHandleKey(event)"
                oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,96)+'px'"></textarea>
            <button id="aiSendBtn" onclick="aiSend()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0
                           transition-all active:scale-95 hover:opacity-90"
                    style="background:#1e3a6e;" title="Kirim">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
        <p class="text-xs mt-1.5 pl-0.5" style="color:#94a3b8; font-size:10.5px;">Enter kirim · Shift+Enter baris baru</p>
    </div>
</div>

<!-- Floating AI Button -->
<div id="aiDragWrapper"
     style="position:fixed; bottom:24px; right:24px; z-index:9999; touch-action:none; user-select:none;">

    <button id="aiBtn"
            onclick="toggleAiPanel()"
            class="w-12 h-12 flex items-center justify-center transition-transform duration-200 hover:scale-110 active:scale-95"
            style="background:transparent; border:none; cursor:pointer;"
            title="Buka AI Assistant">
        <svg width="44" height="44" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" stroke="#1e3a6e" stroke-width="1" fill="none" opacity="0.18"/>
            <rect x="10" y="16" width="26" height="17" rx="4" stroke="#1e3a6e" stroke-width="1.8" fill="none"/>
            <circle cx="18" cy="24" r="2.8" fill="#1e3a6e"/>
            <circle cx="30" cy="24" r="2.8" fill="#1e3a6e"/>
            <path d="M24 10v6" stroke="#1e3a6e" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M20 10h8" stroke="#1e3a6e" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M16 33v4" stroke="#1e3a6e" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M32 33v4" stroke="#1e3a6e" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </button>

    <div id="aiDragHint"
         class="absolute pointer-events-none select-none transition-opacity duration-200"
         style="bottom:calc(100% + 6px); left:50%; transform:translateX(-50%);
                white-space:nowrap; opacity:0;
                font-size:11px; font-weight:500; color:#64748b;
                background:#fff; padding:3px 8px; border-radius:6px;
                box-shadow:0 2px 8px rgba(0,0,0,.08); border:1px solid #e2e8f0;">
        Seret untuk pindah
    </div>
</div>

<!-- ══════════ SCRIPT ══════════ -->
<script>
(function () {

    /* ── Panel open/close ── */
    let panelOpen = false;
    const wrapper = document.getElementById('aiDragWrapper');

    window.toggleAiPanel = function () { panelOpen ? closeAiPanel() : openAiPanel(); };

    window.openAiPanel = function () {
        const panel = document.getElementById('aiPanel');
        positionPanel();
        panel.classList.remove('hidden');
        panel.classList.add('ai-opening');
        setTimeout(() => panel.classList.remove('ai-opening'), 250);
        if (window.innerWidth < 1024)
            document.getElementById('aiBackdrop').classList.remove('hidden');
        panelOpen = true;

        loadInsight();

        wrapper.style.pointerEvents = 'none';
        wrapper.style.opacity = '0';
        wrapper.style.transform = 'scale(.75)';
        wrapper.style.transition = 'opacity .18s ease, transform .18s ease';
        setTimeout(() => document.getElementById('aiInput').focus(), 80);
    };

    window.closeAiPanel = function () {
        document.getElementById('aiPanel').classList.add('hidden');
        document.getElementById('aiBackdrop').classList.add('hidden');
        panelOpen = false;
        wrapper.style.pointerEvents = '';
        wrapper.style.opacity = '1';
        wrapper.style.transform = 'scale(1)';
        wrapper.style.transition = 'opacity .22s ease, transform .22s ease';
    };

    function positionPanel() {
        const panel = document.getElementById('aiPanel');
        const btn   = wrapper.getBoundingClientRect();
        const pw = 380, ph = 520, margin = 16;
        let right  = window.innerWidth  - btn.right;
        let bottom = window.innerHeight - btn.top + 10;
        if (right  + pw > window.innerWidth  - margin) right  = margin;
        if (bottom + ph > window.innerHeight - margin) bottom = margin;
        panel.style.right  = Math.max(margin, right)  + 'px';
        panel.style.bottom = Math.max(margin, bottom) + 'px';
        panel.style.left   = 'auto';
        panel.style.top    = 'auto';
    }

    async function loadInsight() {
        try {
            const insightRes = await fetch("{{ route('admin.ai.insight') }}");
            const data = await insightRes.json();
            document.getElementById('aiBriefing').innerHTML = `
                <div style="
                    background:#f8fafc;
                    border:1px solid #e2e8f0;
                    padding:14px;
                    border-radius:12px;
                    font-size:12px;
                    line-height:1.7;
                ">
                    <b>🧠 Executive Insight</b><br><br>
                    ${data.insight}
                </div>
            `;
        } catch (e) {
            console.error(e);
        }
    }

    /* ── Chat send ── */
    window.aiSend = async function () {
        const input = document.getElementById('aiInput');
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        input.style.height = 'auto';
        appendMsg('user', text);
        showTyping(true);
        try {
            const response = await fetch('{{ route("admin.ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });
            const data = await response.json();
            showTyping(false);
            appendMsg('bot', data.reply || 'Maaf saya tidak menemukan jawaban.');
        } catch (error) {
            console.error(error);
            showTyping(false);
            appendMsg('bot', '⚠️ Terjadi kesalahan pada server AI.');
        }
    };

    window.aiHandleKey = function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); aiSend(); }
    };

    function robotSvg() {
        return `<div style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-bottom:2px;">
            <svg width="18" height="17" viewBox="0 0 30 26" fill="none">
                <rect x="1" y="5" width="22" height="14" rx="3" stroke="#1e3a6e" stroke-width="1.5" fill="none"/>
                <circle cx="7" cy="12" r="2.2" fill="#1e3a6e"/>
                <circle cx="15" cy="12" r="2.2" fill="#1e3a6e"/>
                <path d="M11 2.5v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 2.5h6" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M4 19v2.5M18 19v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
            </svg></div>`;
    }

    function appendMsg(role, text) {
        const body = document.getElementById('aiChatBody');
        const isBot = role === 'bot';
        const wrap = document.createElement('div');
        wrap.className = 'flex gap-2 items-end' + (isBot ? '' : ' flex-row-reverse');
        if (isBot) {
            wrap.innerHTML = robotSvg() +
                `<div style="max-width:82%;padding:10px 14px;border-radius:16px 16px 16px 4px;
                             background:#f0f4fb;color:#1e293b;font-size:12.5px;line-height:1.65;
                             white-space:pre-wrap;">${escHtml(text)}</div>`;
        } else {
            wrap.innerHTML =
                `<div style="max-width:82%;padding:10px 14px;border-radius:16px 16px 4px 16px;
                             background:#1e3a6e;color:#fff;font-size:12.5px;line-height:1.65;
                             white-space:pre-wrap;">${escHtml(text)}</div>`;
        }
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
    }

    function showTyping(show) {
        document.getElementById('aiTyping').classList.toggle('hidden', !show);
        document.getElementById('aiChatBody').scrollTop = document.getElementById('aiChatBody').scrollHeight;
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    /* Quick suggestions */
    document.querySelectorAll('.ai-suggestion').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('aiInput').value = btn.innerText.trim();
            if (!panelOpen) openAiPanel();
            else document.getElementById('aiInput').focus();
        });
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape' && panelOpen) closeAiPanel(); });

    /* ── Drag: floating button ── */
    const hint = document.getElementById('aiDragHint');
    let isDragging = false, didDrag = false, startX, startY, origRight, origBottom;

    function btnDragStart(cx, cy) {
        isDragging = true; didDrag = false;
        startX = cx; startY = cy;
        origRight  = parseFloat(wrapper.style.right)  || 24;
        origBottom = parseFloat(wrapper.style.bottom) || 24;
        wrapper.style.transition = 'none';
        document.body.style.userSelect = 'none';
    }
    function btnDragMove(cx, cy) {
        if (!isDragging) return;
        const dx = cx - startX, dy = cy - startY;
        if (Math.abs(dx) > 4 || Math.abs(dy) > 4) didDrag = true;
        wrapper.style.right  = Math.max(8, Math.min(window.innerWidth  - 56, origRight  - dx)) + 'px';
        wrapper.style.bottom = Math.max(8, Math.min(window.innerHeight - 56, origBottom + dy)) + 'px';
        if (panelOpen) positionPanel();
    }
    function btnDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        document.body.style.userSelect = '';
    }

    wrapper.addEventListener('mousedown',  e => { if (e.button === 0) btnDragStart(e.clientX, e.clientY); });
    document.addEventListener('mousemove', e => btnDragMove(e.clientX, e.clientY));
    document.addEventListener('mouseup',   btnDragEnd);
    wrapper.addEventListener('touchstart', e => { const t = e.touches[0]; btnDragStart(t.clientX, t.clientY); }, { passive: true });
    document.addEventListener('touchmove', e => { if (!isDragging) return; e.preventDefault(); const t = e.touches[0]; btnDragMove(t.clientX, t.clientY); }, { passive: false });
    document.addEventListener('touchend',  btnDragEnd);

    document.getElementById('aiBtn').addEventListener('click', e => {
        if (didDrag) { didDrag = false; e.stopImmediatePropagation(); }
    }, true);

    wrapper.addEventListener('mouseenter', () => { if (!isDragging) hint.style.opacity = '1'; });
    wrapper.addEventListener('mouseleave', () => { hint.style.opacity = '0'; });

    /* ── Drag: chat panel header ── */
    const panelEl  = document.getElementById('aiPanel');
    const panelHdr = document.getElementById('aiPanelHeader');
    let pDrag = false, pSX, pSY, pOL, pOT;

    panelHdr.addEventListener('mousedown', e => {
        if (e.target.closest('button')) return;
        pDrag = true; pSX = e.clientX; pSY = e.clientY;
        const r = panelEl.getBoundingClientRect();
        pOL = r.left; pOT = r.top;
        panelEl.style.left = pOL + 'px'; panelEl.style.top = pOT + 'px';
        panelEl.style.right = 'auto'; panelEl.style.bottom = 'auto';
        document.body.style.userSelect = 'none';
        panelHdr.style.cursor = 'grabbing';
    });
    document.addEventListener('mousemove', e => {
        if (!pDrag) return;
        panelEl.style.left = Math.max(0, Math.min(window.innerWidth  - 380, pOL + e.clientX - pSX)) + 'px';
        panelEl.style.top  = Math.max(0, Math.min(window.innerHeight - 520, pOT + e.clientY - pSY)) + 'px';
    });
    document.addEventListener('mouseup', () => {
        if (!pDrag) return;
        pDrag = false;
        document.body.style.userSelect = '';
        panelHdr.style.cursor = 'grab';
    });

})();

/* ── Data Panel Functions ── */
async function loadTrendInPanel() {
    try {
        const res = await fetch('/admin/ai/trend');
        const data = await res.json();
        appendDataMsg('📈 Trend', data.insight || data.message || 'Tidak ada data.');
    } catch (e) {
        appendDataMsg('📈 Trend Error', e.toString());
    }
}

async function loadAnomalyInPanel() {
    try {
        const res = await fetch('/admin/ai/anomaly');

        const data = await res.json();
        appendDataMsg('⚠️ Anomaly', data.insight);

    } catch (e) {
        appendDataMsg(
            '⚠️ Anomaly Error',
            e.toString()
        );
    }

}


async function loadDistributionInPanel() {
    
    try {
        const res = await fetch('/admin/ai/distribution');
        const data = await res.json();

        appendDataMsg(
            '🥧 Distribution',
            data.insight
        );

    } catch (e) {
        appendDataMsg(
            '🥧 Distribution Error',
            e.toString()
        );
    }
}

function appendDataMsg(title, jsonData) {
    const body = document.getElementById('aiChatBody');
    const wrap = document.createElement('div');
    wrap.className = 'flex gap-2 items-end';
    
    const robotIcon = `<div style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-bottom:2px;">
        <svg width="18" height="17" viewBox="0 0 30 26" fill="none">
            <rect x="1" y="5" width="22" height="14" rx="3" stroke="#1e3a6e" stroke-width="1.5" fill="none"/>
            <circle cx="7" cy="12" r="2.2" fill="#1e3a6e"/>
            <circle cx="15" cy="12" r="2.2" fill="#1e3a6e"/>
            <path d="M11 2.5v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M8 2.5h6" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M4 19v2.5M18 19v2.5" stroke="#1e3a6e" stroke-width="1.5" stroke-linecap="round"/>
        </svg></div>`;
    
    wrap.innerHTML = robotIcon +
        `<div style="max-width:82%;padding:10px 14px;border-radius:16px 16px 16px 4px;
                     background:#f0f4fb;color:#1e293b;font-size:11.5px;line-height:1.6;
                     white-space:pre-wrap; font-family:monospace; overflow-x:auto;">
            <b style="font-family:sans-serif;font-size:12px;color:#1e3a6e;">${title}</b>\n\n${jsonData}
        </div>`;
    
    body.appendChild(wrap);
    body.scrollTop = body.scrollHeight;
}
</script>