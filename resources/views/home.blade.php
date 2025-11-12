<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GAM · Home</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css'])
  @endif

  <style>
    :root{ --bg1:#0f172a; --bg2:#1e293b; --accent:#22d3ee; --accent-2:#6366f1; --muted:#94a3b8; --text:#e5e7eb; --surface: rgba(255,255,255,.06); --border: rgba(255,255,255,.18); --shadow: 0 10px 30px rgba(0,0,0,.45); }
    html,body{height:100%}
    body{margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; color:var(--text); background: radial-gradient(1200px 600px at 10% -10%, #0ea5e9 0%, transparent 60%), radial-gradient(900px 500px at 110% 0%, #7c3aed 0%, transparent 55%), linear-gradient(135deg, var(--bg1), var(--bg2)); display:grid; place-items:center; padding:2rem}
    .card{width:min(760px, 96vw); background:var(--surface); border:1px solid var(--border); backdrop-filter: blur(16px) saturate(120%); -webkit-backdrop-filter: blur(16px) saturate(120%); border-radius:18px; padding:1.8rem; box-shadow:var(--shadow);}
    .brand{display:flex; align-items:center; gap:.6rem;}
    .logo{width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg,var(--accent),var(--accent-2)); box-shadow:var(--shadow); display:grid; place-items:center; font-weight:800; color:#0b1020}
    h1{margin:0; font-size:1.4rem}
    .muted{color:var(--muted)}
    .bar{display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem}
    .btn{padding:.7rem 1rem; border-radius:12px; border:none; cursor:pointer; font-weight:800; background:linear-gradient(135deg,var(--accent),var(--accent-2)); color:#0b1020; box-shadow: 0 12px 22px rgba(34,211,238,.18)}
    .btn-outline{padding:.6rem .9rem; border-radius:12px; border:1px solid var(--border); background:transparent; color:var(--text); cursor:pointer}
    code{background: rgba(8,12,22,.55); padding:.35rem .55rem; border:1px solid rgba(255,255,255,.14); border-radius:10px}
    .grid{display:grid; grid-template-columns: 1fr 1fr; gap:1rem; margin-top:1rem}
    @media (max-width:720px){ .grid{grid-template-columns:1fr} }
    .box{padding:1rem; border:1px solid var(--border); border-radius:14px; background: rgba(8,12,22,.35)}
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const token = localStorage.getItem('token');
      if (!token) { window.location.replace('/login'); return; }
      const short = token.length > 20 ? token.slice(0, 12) + '…' + token.slice(-12) : token;
      document.getElementById('token').textContent = short;

      document.getElementById('copy').addEventListener('click', async () => {
        try { await navigator.clipboard.writeText(token); document.getElementById('copied').textContent = 'Copiado!'; }
        catch { document.getElementById('copied').textContent = 'Não foi possível copiar'; }
      });

      document.getElementById('logout').addEventListener('click', () => {
        localStorage.removeItem('token');
        window.location.href = '/login';
      });
    });
  </script>
</head>
<body>
  <div class="card">
    <div class="bar">
      <div class="brand"><div class="logo">G</div><h1>GAM · Gestão de Ativos</h1></div>
      <button id="logout" class="btn-outline">Sair</button>
    </div>

    <h2 style="margin:.2rem 0 1rem;">Bem-vindo</h2>
    <p class="muted">Você está autenticado via token de API. Use-o nos endpoints sob <code>/api</code>.</p>

    <div class="grid">
      <div class="box">
        <div class="muted">Token (parcial)</div>
        <div style="margin-top:.5rem">
          <code id="token"></code>
        </div>
        <div style="margin-top:.8rem; display:flex; gap:.6rem; align-items:center">
          <button id="copy" class="btn">Copiar token</button>
          <small id="copied" class="muted"></small>
        </div>
      </div>
      
      <div class="box">
        <div class="muted">Dicas</div>
        <div style="margin-top:.6rem; line-height:1.6">
          <div>Use os endpoints via POST:</div>
          <div><code>POST /api/registro</code> e <code>POST /api/acesso</code></div>
          <div>Demais rotas sob <code>/api</code> exigem token.</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
