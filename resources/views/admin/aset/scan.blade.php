@extends('admin.layouts.app')

@section('title', 'Scan QR Aset')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📷 Scan QR Aset</h1>
        <p class="text-gray-600 mt-1">Arahkan kamera HP ke stiker QR Code di aset untuk melihat & mencatat hasil cek fisik.</p>
    </div>

    {{-- Area Kamera --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
        <div id="reader" class="w-full rounded-lg overflow-hidden"></div>

        <div id="scanControls" class="mt-4 flex gap-2">
            <button id="btnStartScan" onclick="startScanner()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                🎥 Mulai Scan
            </button>
            <button id="btnStopScan" onclick="stopScanner()" style="display:none"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 rounded-lg transition">
                ⏹ Stop
            </button>
        </div>
        <p id="scanHint" class="text-xs text-gray-400 mt-2 text-center">Izinkan akses kamera saat browser memintanya.</p>
    </div>

    {{-- Input manual (fallback jika kamera bermasalah) --}}
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
        <p class="text-sm font-medium text-gray-600 mb-2">Atau masukkan ID Aset secara manual:</p>
        <div class="flex gap-2">
            <input type="text" id="manualId" placeholder="Contoh: 12"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <button onclick="lookupAsset(document.getElementById('manualId').value)"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Cari</button>
        </div>
    </div>

    {{-- Hasil Scan --}}
    <div id="resultBox" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div id="resultContent"></div>
    </div>

    <div id="errorBox" class="hidden bg-red-50 border border-red-200 text-red-700 rounded-lg p-4"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const lookupUrl = "{{ route('admin.aset.scan.lookup') }}";
const saveUrl   = "{{ route('admin.aset.scan.save') }}";

function startScanner() {
    document.getElementById('btnStartScan').style.display = 'none';
    document.getElementById('btnStopScan').style.display = 'block';
    document.getElementById('errorBox').classList.add('hidden');

    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        (decodedText) => {
            stopScanner();
            lookupAsset(decodedText);
        },
        () => {}
    ).catch((err) => {
        showError('Tidak bisa mengakses kamera: ' + err + '. Pakai input manual di bawah.');
        document.getElementById('btnStartScan').style.display = 'block';
        document.getElementById('btnStopScan').style.display = 'none';
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
    }
    document.getElementById('btnStartScan').style.display = 'block';
    document.getElementById('btnStopScan').style.display = 'none';
}

function showError(msg) {
    const box = document.getElementById('errorBox');
    box.textContent = '⚠️ ' + msg;
    box.classList.remove('hidden');
    document.getElementById('resultBox').classList.add('hidden');
}

async function lookupAsset(kode) {
    if (!kode) return;

    try {
        const res = await fetch(lookupUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ kode }),
        });
        const data = await res.json();

        if (!data.status) {
            showError(data.message || 'Aset tidak ditemukan.');
            return;
        }

        renderResult(data);
    } catch (e) {
        showError('Gagal menghubungi server: ' + e.message);
    }
}

function renderResult(data) {
    document.getElementById('errorBox').classList.add('hidden');
    const a = data.asset;
    const p = data.peminjaman;
    const c = data.cek_terakhir;

    let html = `
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">${a.nama}</h3>
                <p class="text-gray-500 font-mono text-sm">${a.kode_asset}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">${a.status}</span>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
            <div><p class="text-gray-400">Kategori</p><p class="font-medium">${a.kategori ?? '-'}</p></div>
            <div><p class="text-gray-400">Lokasi</p><p class="font-medium">${a.lokasi ?? '-'}</p></div>
            <div><p class="text-gray-400">Unit</p><p class="font-medium">${a.unit ?? '-'}</p></div>
            <div><p class="text-gray-400">Kondisi Tercatat</p><p class="font-medium">${a.kondisi ?? '-'}</p></div>
        </div>
    `;

    if (p) {
        html += `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4 text-sm">
            <p class="font-semibold text-yellow-800 mb-1">📋 Sedang Dipinjam</p>
            <p>Peminjam: <strong>${p.peminjam}</strong> (${p.unit})</p>
            <p>Kode Peminjaman: ${p.kode_peminjaman} — Status: ${p.status}</p>
            <p>Rencana kembali: ${p.rencana_kembali ?? '-'}</p>
        </div>`;
    }

    if (c) {
        html += `<p class="text-xs text-gray-400 mb-4">Cek fisik terakhir: ${c.tanggal} oleh ${c.oleh} (kondisi: ${c.kondisi})</p>`;
    }

    html += `
        <form onsubmit="submitCekFisik(event, ${a.id})" class="border-t pt-4">
            <p class="font-semibold text-gray-700 mb-2">📝 Catat Hasil Cek Fisik</p>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="text-xs text-gray-500">Kondisi Saat Ini</label>
                    <select name="kondisi_saat_cek" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mt-1">
                        <option value="baik" ${a.kondisi === 'baik' ? 'selected' : ''}>Baik</option>
                        <option value="cukup" ${a.kondisi === 'cukup' ? 'selected' : ''}>Cukup</option>
                        <option value="kurang" ${a.kondisi === 'kurang' ? 'selected' : ''}>Kurang</option>
                        <option value="rusak" ${a.kondisi === 'rusak' ? 'selected' : ''}>Rusak</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Lokasi Saat Ini</label>
                    <input type="text" name="lokasi_saat_cek" value="${a.lokasi ?? ''}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
            </div>
            <textarea name="catatan" placeholder="Catatan (opsional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3" rows="2"></textarea>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition">
                💾 Simpan Cek Fisik
            </button>
        </form>
        <div id="saveMsg" class="mt-2 text-sm"></div>
    `;

    document.getElementById('resultContent').innerHTML = html;
    document.getElementById('resultBox').classList.remove('hidden');
}

async function submitCekFisik(e, assetId) {
    e.preventDefault();
    const form = e.target;
    const body = {
        asset_id: assetId,
        kondisi_saat_cek: form.kondisi_saat_cek.value,
        lokasi_saat_cek: form.lokasi_saat_cek.value,
        catatan: form.catatan.value,
    };

    try {
        const res = await fetch(saveUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(body),
        });
        const data = await res.json();
        const msg = document.getElementById('saveMsg');
        msg.textContent = data.message;
        msg.className = data.status ? 'text-green-600 font-medium' : 'text-red-600 font-medium';
    } catch (err) {
        document.getElementById('saveMsg').textContent = 'Gagal menyimpan: ' + err.message;
    }
}
</script>
@endsection