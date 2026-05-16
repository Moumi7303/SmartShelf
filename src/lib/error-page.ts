export function renderErrorPage(): string {
  return `<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>SmartShelf — This page didn't load</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fraunces:opsz,wght@9..144,600&display=swap" rel="stylesheet" />
    <style>
      body { font: 15px/1.6 'Inter', system-ui, sans-serif; background: #0d1b2a; color: #e0e7ef; display: grid; place-items: center; min-height: 100vh; margin: 0; padding: 1.5rem; }
      .card { max-width: 28rem; width: 100%; text-align: center; padding: 2.5rem; border-radius: 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(12px); }
      .logo { font-family: 'Fraunces', Georgia, serif; font-size: 1.5rem; color: #d4a843; margin-bottom: 1.5rem; letter-spacing: -0.02em; }
      h1 { font-family: 'Inter', system-ui, sans-serif; font-size: 1.15rem; margin: 0 0 0.5rem; font-weight: 600; }
      p { color: #8b99ab; margin: 0 0 1.75rem; font-size: 0.9rem; }
      .actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
      a, button { padding: 0.6rem 1.2rem; border-radius: 0.5rem; font: inherit; font-size: 0.875rem; font-weight: 500; cursor: pointer; text-decoration: none; border: 1px solid transparent; transition: all 0.2s; }
      .primary { background: #d4a843; color: #0d1b2a; }
      .primary:hover { background: #c49a3a; }
      .secondary { background: rgba(255,255,255,0.06); color: #e0e7ef; border-color: rgba(255,255,255,0.12); }
      .secondary:hover { background: rgba(255,255,255,0.1); }
      .footer { margin-top: 2rem; font-size: 0.7rem; color: #4a5568; }
    </style>
  </head>
  <body>
    <div class="card">
      <div class="logo">📚 SmartShelf</div>
      <h1>This page didn't load</h1>
      <p>Something went wrong on our end. You can try refreshing or head back to the dashboard.</p>
      <div class="actions">
        <button class="primary" onclick="location.reload()">Try again</button>
        <a class="secondary" href="/">Go to dashboard</a>
      </div>
      <div class="footer">© SmartShelf — University Library Management System</div>
    </div>
  </body>
</html>`;
}
