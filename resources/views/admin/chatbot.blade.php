<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-robot text-blue-600 me-2"></i> AI Assistant SAPRAS
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-md transition-all text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <!-- Resources: Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .card-chat { border-radius: 25px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.05); overflow: hidden; }
        #chat-box { height: 450px; overflow-y: auto; background-color: #f8fafc; padding: 25px; display: flex; flex-direction: column; gap: 15px; scroll-behavior: smooth; }
        .msg-ai { background: white; border: 1px solid #e2e8f0; padding: 15px; border-radius: 20px 20px 20px 5px; max-width: 80%; align-self: flex-start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .msg-user { background: #2563eb; color: white; padding: 15px; border-radius: 20px 20px 5px 20px; max-width: 80%; align-self: flex-end; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
        .chat-input-container { background: white; padding: 20px; border-top: 1px solid #f1f5f9; }
        .btn-send { border-radius: 15px; padding: 10px 25px; font-weight: 800; transition: all 0.3s; }
        .btn-send:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3); }
        .typing-indicator { font-size: 12px; color: #64748b; font-style: italic; }
        
        /* Utility Styles */
        .grayscale { filter: grayscale(100%); opacity: 0.6; transition: all 0.5s; }
        .transition-all { transition: all 0.3s ease-in-out; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Singkat (Indikator Koneksi) -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div id="ai-status-card" class="bg-white p-4 rounded-4 shadow-sm border-0 border-bottom border-4 border-primary transition-all">
                        <p class="text-muted text-xs font-bold uppercase tracking-widest mb-1">Status Sistem AI</p>
                        <h3 class="text-dark font-black mb-0">
                            <span id="ai-status-text">Checking...</span> 
                            <small id="ai-status-detail" class="fs-6">
                                <i id="ai-status-icon" class="bi bi-circle-fill"></i> 
                                <span id="ai-status-label">Memuat...</span>
                            </small>
                        </h3>
                    </div>
                </div>
                <div class="col-md-8 text-end d-flex align-items-center justify-content-end">
                    <div class="bg-blue-50 px-4 py-2 rounded-pill border border-blue-100">
                        <span class="text-blue-700 fw-bold small"><i class="bi bi-info-circle me-2"></i> Tanyakan data laporan atau panduan sarana prasarana.</span>
                    </div>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="card card-chat bg-white">
                <div id="chat-header" class="bg-dark p-3 text-white d-flex align-items-center justify-content-between transition-all">
                    <div class="d-flex align-items-center">
                        <div id="robot-icon-bg" class="bg-primary rounded-circle p-2 me-3 transition-all">
                            <i class="bi bi-robot fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Asisten AI SAPRAS</h6>
                            <small id="header-status" class="text-success" style="font-size: 10px;">
                                <i class="bi bi-circle-fill me-1"></i> Aktif Melayani
                            </small>
                        </div>
                    </div>
                </div>

                <div id="chat-box">
                    <div class="msg-ai">
                        <strong class="d-block text-primary small mb-1">AI Assistant</strong>
                        Halo Admin! Saya adalah AI SAPRAS. Ada yang bisa saya bantu terkait pengelolaan sarana dan prasarana hari ini?
                    </div>
                </div>

                <div class="chat-input-container">
                    <div class="input-group gap-2">
                        <input type="text" id="user-input" 
                            class="form-control border-2 border-light bg-light rounded-pill px-4 transition-all" 
                            placeholder="Ketik pertanyaan anda di sini..."
                            onkeypress="if(event.key === 'Enter') kirim()">
                        
                        <button id="btn-send" onclick="kirim()" class="btn btn-primary btn-send">
                            Kirim <i class="bi bi-send-fill ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        /**
         * FUNGSI 1: MANAJEMEN STATUS KONEKSI (UI)
         */
        function updateOnlineStatus() {
            const statusElements = {
                text: document.getElementById('ai-status-text'),
                detail: document.getElementById('ai-status-detail'),
                icon: document.getElementById('ai-status-icon'),
                label: document.getElementById('ai-status-label'),
                card: document.getElementById('ai-status-card'),
                headerStatus: document.getElementById('header-status'),
                robotIcon: document.getElementById('robot-icon-bg'),
                btnSend: document.getElementById('btn-send'),
                userInput: document.getElementById('user-input')
            };

            if (navigator.onLine) {
                // Tampilan Saat ONLINE
                statusElements.text.innerText = "Online";
                statusElements.detail.className = "text-success fs-6";
                statusElements.icon.className = "bi bi-patch-check-fill";
                statusElements.label.innerText = "Ready";
                statusElements.card.style.borderBottomColor = "#2563eb";
                
                statusElements.headerStatus.innerHTML = '<i class="bi bi-circle-fill me-1"></i> Aktif Melayani';
                statusElements.headerStatus.className = "text-success";
                statusElements.robotIcon.classList.remove('grayscale');
                
                statusElements.btnSend.disabled = false;
                statusElements.userInput.disabled = false;
                statusElements.userInput.placeholder = "Ketik pertanyaan anda di sini...";
            } else {
                // Tampilan Saat OFFLINE
                statusElements.text.innerText = "Offline";
                statusElements.detail.className = "text-danger fs-6";
                statusElements.icon.className = "bi bi-exclamation-triangle-fill";
                statusElements.label.innerText = "No Connection";
                statusElements.card.style.borderBottomColor = "#dc3545";
                
                statusElements.headerStatus.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Terputus';
                statusElements.headerStatus.className = "text-danger";
                statusElements.robotIcon.classList.add('grayscale');
                
                // Kunci input agar user tahu sistem tidak bisa memproses
                statusElements.btnSend.disabled = true;
                statusElements.userInput.disabled = true;
                statusElements.userInput.placeholder = "Koneksi terputus. Mohon aktifkan internet...";
            }
        }

        // Jalankan listener koneksi
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        window.addEventListener('load', updateOnlineStatus);

        /**
         * FUNGSI 2: LOGIKA PENGIRIMAN PESAN & BALASAN OFFLINE
         */
        async function kirim() {
            const input = document.getElementById('user-input');
            const box = document.getElementById('chat-box');
            const pesan = input.value.trim();

            if (!pesan) return;

            // 1. Tampilkan Pesan User di Chat Box
            box.innerHTML += `
                <div class="msg-user">
                    ${pesan}
                </div>
            `;
            
            input.value = '';
            box.scrollTop = box.scrollHeight;

            // --- PROTEKSI DOUBLE CHECK: JIKA USER MEMAKSA KIRIM SAAT OFFLINE ---
            if (!navigator.onLine) {
                setTimeout(() => {
                    box.innerHTML += `
                        <div class="msg-ai border-danger border-start border-4">
                            <strong class="d-block text-danger small mb-1">AI Assistant (Offline)</strong>
                            <p class="mb-1 text-dark">Mohon maaf Admin, saya sedang <strong>offline</strong> dan tidak dapat memproses permintaan Anda.</p>
                            <span class="badge bg-danger">Harap untuk mengaktifkan koneksi internet Anda.</span>
                        </div>
                    `;
                    box.scrollTop = box.scrollHeight;
                }, 600);
                return; // Stop eksekusi agar tidak memanggil server
            }

            // 2. Tampilkan Indikator Loading (Jika Online)
            const loadingId = 'loading-' + Date.now();
            box.innerHTML += `
                <div id="${loadingId}" class="msg-ai border-0 bg-light shadow-none">
                    <span class="typing-indicator">AI sedang menganalisis data...</span>
                </div>
            `;
            box.scrollTop = box.scrollHeight;

            try {
                const response = await fetch("{{ route('chatbot.tanya') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pesan: pesan })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                
                // Hapus loading dan tampilkan jawaban asli dari AI
                document.getElementById(loadingId).remove();
                box.innerHTML += `
                    <div class="msg-ai">
                        <strong class="d-block text-primary small mb-1">AI Assistant</strong>
                        ${data.jawaban}
                    </div>
                `;
            } catch (error) {
                // Hapus loading jika terjadi error server
                if (document.getElementById(loadingId)) {
                    document.getElementById(loadingId).remove();
                }
                box.innerHTML += `
                    <div class="alert alert-warning border-0 rounded-4 py-2 px-3 text-center small">
                        <i class="bi bi-wifi-off me-2"></i> Gagal menghubungi server. Periksa koneksi internet Anda.
                    </div>
                `;
            }

            // Selalu scroll ke bawah setelah ada pesan baru
            box.scrollTop = box.scrollHeight;
        }
    </script>
</x-app-layout>