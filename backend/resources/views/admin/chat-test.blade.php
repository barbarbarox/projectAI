@extends('layouts.app')
@section('title', 'Uji Coba AI & RAG — Admin RedSim')
@section('header', 'Uji Coba AI & RAG')
@section('subheader', 'Berkomunikasi dengan AI yang terhubung dengan basis pengetahuan RedSim')

@section('content')
<div class="flex flex-col h-[calc(100vh-12rem)] bg-[#0f1629] border border-[#1e2d4a] rounded-2xl overflow-hidden" x-data="chatApp()">
    
    {{-- Status Banner --}}
    <div class="bg-[#1e2d4a]/50 border-b border-[#1e2d4a] px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            <span class="text-sm font-medium text-green-400">Sistem AI Siap</span>
        </div>
        <div class="flex items-center gap-4">
            <select x-model="selectedModel" class="px-3 py-1.5 rounded-lg bg-[#0a0e1a] border border-[#1e2d4a] text-xs text-white focus:border-[#00d4ff] focus:outline-none transition-colors max-w-[200px]">
                <option value="">Gunakan Model Default</option>
                @foreach($availableModels as $m)
                    <option value="{{ $m['id'] }}">{{ $m['name'] }} ({{ $m['provider'] }})</option>
                @endforeach
            </select>

            <div class="text-xs text-[#94a3b8] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mode RAG Aktif
            </div>
        </div>
    </div>

    {{-- Chat History --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chat-container">
        {{-- Welcome Message --}}
        <div class="flex gap-4">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00d4ff] to-[#7c3aed] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="bg-[#1e2d4a]/30 border border-[#1e2d4a] rounded-2xl rounded-tl-sm px-5 py-4 max-w-[80%]">
                <p class="text-sm leading-relaxed text-[#e2e8f0]">
                    Halo! Saya adalah AI Assistant RedSim. Saya terhubung dengan basis pengetahuan keamanan sistem. Silakan tanyakan apapun terkait keamanan, kerentanan, atau status sistem.
                </p>
            </div>
        </div>

        {{-- Dynamic Messages --}}
        <template x-for="(msg, index) in messages" :key="index">
            <div class="flex gap-4" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                     :class="msg.role === 'user' ? 'bg-[#1e2d4a]' : 'bg-gradient-to-br from-[#00d4ff] to-[#7c3aed]'">
                    <template x-if="msg.role === 'user'">
                        <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </template>
                    <template x-if="msg.role === 'ai'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </template>
                </div>
                <div class="rounded-2xl px-5 py-4 max-w-[80%] break-words"
                     :class="msg.role === 'user' ? 'bg-[#00d4ff]/10 border border-[#00d4ff]/20 text-white rounded-tr-sm' : 'bg-[#1e2d4a]/30 border border-[#1e2d4a] text-[#e2e8f0] rounded-tl-sm'">
                    
                    <div class="text-sm leading-relaxed whitespace-pre-wrap format-markdown" x-html="formatMessage(msg.content)"></div>
                    
                    <template x-if="msg.role === 'ai' && msg.ragUsed">
                        <div class="mt-3 pt-3 border-t border-[#1e2d4a]/50 text-xs text-[#00d4ff] flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Referensi RAG Digunakan
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Loading indicator --}}
        <div class="flex gap-4" x-show="isLoading" style="display: none;">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00d4ff] to-[#7c3aed] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div class="bg-[#1e2d4a]/30 border border-[#1e2d4a] rounded-2xl rounded-tl-sm px-5 py-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#00d4ff] animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 rounded-full bg-[#00d4ff] animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 rounded-full bg-[#00d4ff] animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="p-4 bg-[#0a0e1a] border-t border-[#1e2d4a]">
        <form @submit.prevent="sendMessage" class="relative">
            <input type="text" x-model="input" placeholder="Tanyakan sesuatu..." :disabled="isLoading"
                   class="w-full bg-[#0f1629] border border-[#1e2d4a] rounded-xl pl-4 pr-12 py-3.5 text-sm text-white focus:outline-none focus:border-[#00d4ff]/50 focus:ring-1 focus:ring-[#00d4ff]/50 transition-all disabled:opacity-50">
            
            <button type="submit" :disabled="!input.trim() || isLoading"
                    class="absolute right-2 top-2 p-1.5 rounded-lg bg-gradient-to-r from-[#00d4ff] to-[#7c3aed] text-white hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>
        <div class="mt-2 text-center">
            <span class="text-[10px] text-[#64748b]">AI mungkin memberikan jawaban yang tidak akurat. Verifikasi info penting.</span>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Basic Markdown formatting */
    .format-markdown p { margin-bottom: 0.75em; }
    .format-markdown p:last-child { margin-bottom: 0; }
    .format-markdown strong { font-weight: 700; color: #fff; }
    .format-markdown code { background: #000; padding: 0.1em 0.3em; border-radius: 4px; font-family: monospace; font-size: 0.9em; color: #00d4ff; }
    .format-markdown pre.inline-code { background: transparent; padding: 0; border-radius: 0; overflow-x: auto; margin-bottom: 0; }
    .format-markdown ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 0.75em; }
    .format-markdown ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 0.75em; }
    .format-markdown h3 { font-size: 1.125rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; color: #fff; }
    .format-markdown h2 { font-size: 1.25rem; font-weight: 700; margin-top: 1.25rem; margin-bottom: 0.5rem; color: #fff; }
</style>
<script>
    window.copyCode = function(btn) {
        const preRaw = btn.parentElement.querySelector('.code-raw');
        if (!preRaw) return;
        navigator.clipboard.writeText(preRaw.textContent).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = `<svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-green-400">Tersalin!</span>`;
            setTimeout(() => { btn.innerHTML = originalHTML; }, 2000);
        });
    };

    function chatApp() {
        return {
            messages: [],
            input: '',
            isLoading: false,
            selectedModel: '',
            
            formatMessage(text) {
                if (!text) return '';
                
                // Alur berpikir (Thought process)
                text = text.replace(/<think>([\s\S]*?)<\/think>/g, function(match, thought) {
                    return `<details class="mb-4 bg-[#1e2d4a]/50 rounded-lg border border-[#334155] overflow-hidden">
                        <summary class="px-4 py-2 cursor-pointer bg-[#1e2d4a] hover:bg-[#334155] transition-colors text-sm font-medium text-[#94a3b8] flex items-center gap-2 select-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Alur Berpikir AI
                        </summary>
                        <div class="p-4 text-sm text-[#94a3b8] italic border-t border-[#334155]">
                            ${thought.replace(/\n/g, '<br>')}
                        </div>
                    </details>`;
                });

                // Simple markdown parsing for the chat
                text = text.replace(/### (.*?)\n/g, '<h3>$1</h3>\n');
                text = text.replace(/## (.*?)\n/g, '<h2>$1</h2>\n');
                
                text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
                
                // Code blocks with copybox
                let codeCount = 0;
                text = text.replace(/```([\w-]*)\n?([\s\S]*?)```/g, function(match, lang, code) {
                    codeCount++;
                    const escapedCode = code.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    return `<div class="relative group mt-3 mb-3">
                        <div class="flex items-center justify-between bg-[#1e2d4a] px-3 py-1.5 rounded-t-lg border border-[#334155] border-b-0">
                            <span class="text-xs text-gray-400 font-mono">${lang || 'code'}</span>
                            <button onclick="window.copyCode(this)" class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-white transition-colors py-1 px-2 rounded-md hover:bg-[#334155]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Salin</span>
                            </button>
                            <pre class="hidden code-raw">${escapedCode}</pre>
                        </div>
                        <pre class="bg-[#0b101a] p-4 rounded-b-lg overflow-x-auto border border-[#334155] m-0" style="margin-bottom:0;"><code class="text-[#e2e8f0] text-sm font-mono whitespace-pre inline-code">${escapedCode}</code></pre>
                    </div>`;
                });
                
                text = text.replace(/`([^`]+)`/g, '<code>$1</code>');
                
                // Unordered Lists
                text = text.replace(/^\s*[-*]\s+(.+)/gm, '<ul><li>$1</li></ul>');
                text = text.replace(/<\/ul>\n<ul>/g, '\n');

                // Ordered Lists
                text = text.replace(/^\s*\d+\.\s+(.+)/gm, '<ol><li>$1</li></ol>');
                text = text.replace(/<\/ol>\n<ol>/g, '\n');
                
                return text;
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('chat-container');
                    container.scrollTop = container.scrollHeight;
                }, 50);
            },

            async sendMessage() {
                if (!this.input.trim() || this.isLoading) return;

                const userMsg = this.input.trim();
                this.messages.push({ role: 'user', content: userMsg });
                this.input = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('{{ route('admin.chat-test') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            message: userMsg,
                            model_ai: this.selectedModel
                        })
                    });

                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        this.messages.push({ 
                            role: 'ai', 
                            content: data.message,
                            ragUsed: data.rag_used
                        });
                    } else {
                        this.messages.push({ 
                            role: 'ai', 
                            content: 'Maaf, terjadi kesalahan: ' + (data.message || 'Unknown error'),
                            ragUsed: false
                        });
                    }
                } catch (error) {
                    this.messages.push({ 
                        role: 'ai', 
                        content: 'Gagal terhubung ke server.',
                        ragUsed: false
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                }
            }
        }
    }
</script>
@endpush
@endsection
