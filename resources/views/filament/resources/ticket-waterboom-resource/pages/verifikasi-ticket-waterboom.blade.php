<x-filament-panels::page>

{{-- <div id="reader" style="width: 300px;"></div>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const scanner = new Html5Qrcode("reader");
    scanner.start(
        { facingMode: "environment" }, // kamera belakang
        { fps: 10, qrbox: 250 },
        (decodedText, decodedResult) => {
            // Kirim ke backend untuk verifikasi
            fetch('/verifikasi-tiket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ qr: decodedText })
            }).then(res => res.json()).then(data => {
                alert(data.message);
            });
        }
    );
</script> --}}

{{-- <div id="reader" style="width:300px;"></div>
<button id="btnVerify">Verifikasi Tiket</button>
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
  let lastDecodedText = null;

  const scanner = new Html5Qrcode("reader");
  scanner.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    (decodedText, decodedResult) => {
      // Simpan hasil scan terakhir tapi TIDAK langsung kirim
      lastDecodedText = decodedText;
      console.log("QR Code detected:", decodedText);
    },
    (errorMessage) => {
      // ignore scan errors
    }
  );

  document.getElementById("btnVerify").addEventListener("click", () => {
    if (!lastDecodedText) {
      alert("Belum ada QR code yang discan!");
      return;
    }

    fetch('/verifikasi-tiket', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      body: JSON.stringify({ qr: lastDecodedText })
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
    })
    .catch(e => {
      console.error(e);
      alert("Terjadi kesalahan saat verifikasi.");
    });
  });
</script> --}}

<label>Hasil QR Code:</label>
<input type="text" id="qrResult" readonly style="width: 350px;">
<div id="reader" style="width: 350px; 1px solid #ccc;"></div>
<br>
<button id="btnToggleCamera">Nyalakan Kamera</button>
<button id="btnVerify">Verifikasi Tiket</button>
<br><br>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
  let scanner = null;
  let cameraOn = false;
  let lastDecodedText = "";

  const btnToggleCamera = document.getElementById("btnToggleCamera");
  const btnVerify = document.getElementById("btnVerify");
  const qrResult = document.getElementById("qrResult");
  const readerElem = document.getElementById("reader");

  btnToggleCamera.addEventListener("click", async () => {
    if (!cameraOn) {
      scanner = new Html5Qrcode("reader");
      try {
        await scanner.start(
          { facingMode: "environment" },
          { fps: 10, qrbox: 250 },
          (decodedText, decodedResult) => {
            lastDecodedText = decodedText;
            qrResult.value = decodedText; // langsung tampilkan hasil
          }
        );
        btnToggleCamera.textContent = "Matikan Kamera";
        cameraOn = true;
      } catch (e) {
        alert("Gagal mengakses kamera: " + e);
      }
    } else {
      await scanner.stop();
      scanner.clear();
      qrResult.value = "";
      btnToggleCamera.textContent = "Nyalakan Kamera";
      cameraOn = false;
    }
  });

  btnVerify.addEventListener("click", () => {
    const kodeQR = qrResult.value.trim(); // Ambil dari input

    if (!kodeQR) {
      alert("Kolom hasil QR masih kosong!");
      return;
    }

    fetch('/verifikasi-tiket', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
      body: JSON.stringify({ qr: kodeQR })
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
    })
    .catch(e => {
      console.error(e);
      alert("Terjadi kesalahan saat verifikasi.");
    });
});
</script>



</x-filament-panels::page>
