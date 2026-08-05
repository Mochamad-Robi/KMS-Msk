@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700;800&display=swap');

  :root{
    --ink:#221B17;
    --ink-soft:#6B5F58;
    --maroon-950:#430E1A;
    --maroon-900:#5C1522;
    --maroon-800:#7A2334;
    --gold:#C9A24B;
    --gold-soft:#E3CD8F;
    --field-bg:#F8F6F3;
    --field-border:#E7DED2;
  }

  .font-body{ font-family:'IBM Plex Sans', sans-serif; }

  .field{
    display: block;
    width: 100%;
    box-sizing: border-box;
    padding: 12px 16px;
    font-size: 14px;
    background: var(--field-bg);
    border: 1px solid var(--field-border);
    border-radius: 10px;
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .field:focus{
    outline: none;
    border-color: var(--maroon-800);
    box-shadow: 0 0 0 3px rgba(122,35,52,0.12);
    background: #fff;
  }
  .field-password{ padding-right: 48px; }

  .field-wrap{ position: relative; }
  .eye-toggle{
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border: none;
    background: transparent;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-soft);
    cursor: pointer;
    transition: background .15s ease;
  }
  .eye-toggle:hover{ background: #F1ECE6; }

  .captcha-row{ display:flex; align-items:center; gap:10px; margin-bottom:12px; }

  /* FIX: sebelumnya flex:1 + img width:100% bikin gambar captcha
     (yang aslinya kecil, ~120x36px) ikut melebar/stretch mengisi
     seluruh baris -> hurufnya jadi renggang/molor. Sekarang wrapper
     dibiarkan menyesuaikan ukuran asli gambar (inline-block + width:auto)
     dan gambar tidak di-stretch (object-fit: contain). */
  .captcha-img-wrap{
    width: 190px;
    height: 72px;
    border: 1px solid var(--field-border);
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .captcha-img-wrap img{
    display: block;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }
  .captcha-refresh{
    flex-shrink: 0;
    width: 72px;
    height: 72px;
    border: 1px solid var(--field-border);
    border-radius: 10px;
    background: var(--field-bg);
    color: var(--ink-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .15s ease, transform .25s ease;
  }
  .captcha-refresh:hover{ background:#F1ECE6; }
  .captcha-refresh:active{ transform: rotate(-180deg); }

  .btn-maroon{
    display: block;
    width: 100%;
    box-sizing: border-box;
    padding: 14px 20px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: #fff;
    cursor: pointer;
    background: var(--maroon-900);
    transition: transform .15s ease, background .2s ease, box-shadow .2s ease;
  }
  .btn-maroon:hover{
    background: var(--maroon-800);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px -8px rgba(67,14,26,0.55);
  }
  .btn-maroon:active{ transform: translateY(0); }

  .stat-card{
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(6px);
  }

  @media (prefers-reduced-motion: reduce){
    .btn-maroon{ transition: none; }
  }
</style>

<div class="flex min-h-screen w-full font-body" style="color:var(--ink)">

  {{-- KIRI: Form (putih, simpel) --}}
  <div class="w-full max-w-md flex flex-col justify-center px-10 py-12 bg-white">

   <div class="flex justify-center mb-6">
    <img
        src="{{ asset('assets/logoapk2.png') }}"
        alt="Logo PT MSK"
        class="h-24 w-auto"
    >
</div>

    <div class="text-center mb-8">
      <h2 class="text-2xl font-bold leading-tight mb-1">Selamat Datang Kembali!</h2>
      <p class="text-sm" style="color:var(--ink-soft)">Masuk untuk melanjutkan ke Knowledge Management System</p>
    </div>

    {{-- Error --}}
    @if ($errors->any())
    <div class="border-l-4 px-4 py-3 mb-6 text-sm flex items-start gap-2" style="border-color:var(--maroon-800); background:#FBEEEF; color:var(--maroon-900)">
      <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- ID Karyawan --}}
    <div class="mb-5">
      <label class="block text-sm font-semibold mb-2">ID Karyawan</label>
      <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="102***" autofocus
        class="field"
        @error('employee_id') style="border-color:#B3475A" @enderror />
      @error('employee_id')<p class="text-xs mt-1.5" style="color:var(--maroon-800)">{{ $message }}</p>@enderror
    </div>

    {{-- Password --}}
    <div class="mb-6">
      <label class="block text-sm font-semibold mb-2">Password</label>
      <div class="field-wrap">
        <input type="password" name="password" id="password" placeholder="Masukkan password"
          class="field field-password"
          @error('password') style="border-color:#B3475A" @enderror />
        <button type="button" onclick="togglePassword()" class="eye-toggle" aria-label="Tampilkan password">
          <svg id="eye-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </button>
      </div>
      @error('password')<p class="text-xs mt-1.5" style="color:var(--maroon-800)">{{ $message }}</p>@enderror
    </div>

    {{-- Captcha --}}
    <div class="mb-6">
      <label class="block text-sm font-semibold mb-2">Captcha <span style="color:var(--maroon-800)">*</span></label>
      <div class="captcha-row">
        <div class="captcha-img-wrap">
          <img id="captcha-img" src="{{ captcha_src('flat') }}" alt="Kode captcha">
        </div>
        <button type="button" class="captcha-refresh" onclick="refreshCaptcha()" aria-label="Refresh captcha">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
      <input type="text"
       name="captcha"
       placeholder="Masukkan kode CAPTCHA"
       class="field"
       required
       @error('captcha') style="border-color:#B3475A" @enderror />
    </div>

    {{-- Remember & Forgot --}}
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded cursor-pointer" style="accent-color:var(--maroon-900)">
        <label for="remember" class="text-sm cursor-pointer" style="color:var(--ink-soft)">Ingat saya</label>
      </div>
      <a href="#" class="text-sm font-medium hover:underline" style="color:var(--maroon-900)">Lupa password?</a>
    </div>

    {{-- Submit --}}
    <button type="submit" class="btn-maroon">
      Masuk
    </button>

    </form>

    <p class="text-center text-xs mt-10" style="color:#A89C8E">&copy; {{ date('Y') }} PT MSK — Knowledge Management System</p>
  </div>

  {{-- KANAN: Foto + overlay --}}
  <div class="hidden lg:block flex-1 relative overflow-hidden">
    {{-- Ganti src di bawah dengan foto kantor/gedung PT MSK --}}
    <img src="{{ asset('assets/login-bg.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(67,14,26,0.80) 0%, rgba(67,14,26,0.55) 45%, rgba(67,14,26,0.85) 100%);"></div>

    <div class="relative z-10 h-full flex flex-col justify-between p-12">

      <div class="inline-flex items-center gap-2 border rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-wider w-fit" style="border-color:rgba(201,162,75,0.4); color:var(--gold-soft)">
        Knowledge Portal
      </div>

      <div class="max-w-sm">
        <h2 class="text-4xl font-bold text-white leading-tight mb-4">Satu Tempat<br>Segala Pengetahuan</h2>
        <div class="w-10 h-[3px] rounded-full mb-4" style="background:var(--gold)"></div>
        <p class="text-sm leading-relaxed text-white/70">Akses artikel, SOP, panduan kerja, dan basis pengetahuan perusahaan dalam satu platform terintegrasi.</p>
      </div>

      <div>
        <div class="grid grid-cols-3 gap-4 mb-6">
          <div class="stat-card rounded-lg p-3">
            <div class="text-xl font-bold text-white">500+</div>
            <div class="text-xs text-white/60 mt-0.5">Artikel Pengetahuan</div>
          </div>
          <div class="stat-card rounded-lg p-3">
            <div class="text-xl font-bold text-white">8</div>
            <div class="text-xs text-white/60 mt-0.5">Departemen</div>
          </div>
          <div class="stat-card rounded-lg p-3">
            <div class="text-xl font-bold text-white">3</div>
            <div class="text-xs text-white/60 mt-0.5">Level Akses</div>
          </div>
        </div>
        <p class="text-xs text-white/40">Versi 1.0.0 &nbsp;·&nbsp; Knowledge Management System PT MSK</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function refreshCaptcha() {
    fetch("{{ route('captcha.refresh') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('captcha-img').src =
                data.captcha + '&v=' + Date.now();
        })
        .catch(err => console.error(err));
}
</script>
@endpush