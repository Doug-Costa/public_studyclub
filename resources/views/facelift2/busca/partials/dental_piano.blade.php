<style>
  #dental-piano-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 1rem 1.4rem;
    background: #16120e;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    margin: 20px 0;
    width: 100%;
    max-width: 650px;
    font-family: 'Georgia', serif;
  }
  #dental-piano-wrapper .subtitle {
    font-size: 11px;
    letter-spacing: .24em;
    color: #b8a888;
    text-transform: uppercase;
    margin-bottom: 16px;
    opacity: .68;
  }
  #dental-piano-wrapper .hint {
    font-size: 10px;
    color: #686050;
    letter-spacing: .07em;
    margin-top: 11px;
    text-align: center;
    line-height: 1.7;
  }
  #dental-piano-wrapper .hint b {
    color: #a89878;
    font-weight: normal;
  }
  #dental-piano-sv {
    display: block;
    touch-action: manipulation;
    border-radius: 3px;
    max-width: 100%;
    height: auto;
  }
</style>

<div id="dental-piano-wrapper">
  <div class="subtitle">Piano Dental Anatômico</div>
  <svg id="dental-piano-sv" xmlns="http://www.w3.org/2000/svg"></svg>
  <p class="hint">
    <b>A S D F G H J K</b> — dentes brancos &nbsp;·&nbsp; <b>W E T Y U</b> — dentes escuros
  </p>
</div>

<script>
(function() {
  'use strict';
  const sv = document.getElementById('dental-piano-sv');
  if (!sv) return;

  const NS = 'http://www.w3.org/2000/svg';
  const E = (t, a = {}) => {
    const e = document.createElementNS(NS, t);
    Object.entries(a).forEach(([k, v]) => e.setAttribute(k, v));
    return e;
  };

  // ── Layout ──
  const W = 608, KW = 76, N = 8;
  const BONE_H = 32, GUM_TIS = 50, GUM_H = BONE_H + GUM_TIS;
  const CROWN_H = 172, BK_H = 102;
  const TOTAL = GUM_H + CROWN_H + 10;
  const CEJ = GUM_H;

  sv.setAttribute('width', W);
  sv.setAttribute('height', TOTAL);
  sv.setAttribute('viewBox', `0 0 ${W} ${TOTAL}`);

  const WH = [
    { n: 'Dó', f: 261.63, k: 'a', t: 'incisor' },
    { n: 'Ré', f: 293.66, k: 's', t: 'lateral' },
    { n: 'Mi', f: 329.63, k: 'd', t: 'canine' },
    { n: 'Fá', f: 349.23, k: 'f', t: 'premolar' },
    { n: 'Sol', f: 392.00, k: 'g', t: 'premolar' },
    { n: 'Lá', f: 440.00, k: 'h', t: 'canine' },
    { n: 'Si', f: 493.88, k: 'j', t: 'lateral' },
    { n: 'Dó', f: 523.25, k: 'k', t: 'incisor' }
  ];
  const BK = [
    { f: 277.18, k: 'w', ai: 0 }, { f: 311.13, k: 'e', ai: 1 },
    { f: 369.99, k: 't', ai: 3 }, { f: 415.30, k: 'y', ai: 4 }, { f: 466.16, k: 'u', ai: 5 }
  ];

  // ── Áudio ──
  let actx = null;
  const getCtx = () => {
    if (!actx) actx = new (window.AudioContext || window.webkitAudioContext)();
    if (actx.state === 'suspended') actx.resume();
    return actx;
  };
  const play = (f, dark = false) => {
    const c = getCtx();
    const o1 = c.createOscillator(), o2 = c.createOscillator(), o3 = c.createOscillator();
    const gn = c.createGain(), fl = c.createBiquadFilter(), g2 = c.createGain(), g3 = c.createGain();
    fl.type = 'lowpass';
    fl.frequency.value = dark ? 2300 : 3100;
    fl.Q.value = 0.44;
    o1.type = 'triangle';
    o1.frequency.value = f;
    o2.type = 'sine';
    o2.frequency.value = f * 2.002;
    g2.gain.value = 0.09;
    o3.type = 'sine';
    o3.frequency.value = f * 3.01;
    g3.gain.value = 0.03;
    const t = c.currentTime;
    gn.gain.setValueAtTime(0, t);
    gn.gain.linearRampToValueAtTime(.41, t + .009);
    gn.gain.exponentialRampToValueAtTime(.15, t + .20);
    gn.gain.exponentialRampToValueAtTime(.001, t + 2.0);
    o1.connect(fl); o2.connect(g2); g2.connect(fl); o3.connect(g3); g3.connect(fl); fl.connect(gn); gn.connect(c.destination);
    [o1, o2, o3].forEach(o => { o.start(t); o.stop(t + 2.1); });
  };
  const pressEl = g => { g.style.filter = 'brightness(.76) saturate(1.22)'; g.style.transform = 'translateY(-3px)'; };
  const unpressEl = g => { g.style.filter = ''; g.style.transform = ''; };
  const ripple = (cx, cy) => {
    const r = E('circle', { cx, cy, r: '7', fill: 'none', stroke: '#c8b898', 'stroke-width': '1.5', opacity: '0.72', 'pointer-events': 'none' });
    sv.appendChild(r);
    let rv = 7, op = 0.72;
    const s = () => { rv += 3.2; op -= .068; r.setAttribute('r', rv); r.setAttribute('opacity', Math.max(0, op)); if (op > 0) requestAnimationFrame(s); else r.remove(); };
    requestAnimationFrame(s);
  };
  const addPress = (g, f, dark = false) => {
    g.style.cursor = 'pointer';
    const cx = +g.getAttribute('data-cx'), cy = +g.getAttribute('data-cy');
    g.addEventListener('pointerdown', e => { e.preventDefault(); play(f, dark); pressEl(g); ripple(cx, cy); });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev => g.addEventListener(ev, () => unpressEl(g)));
  };

  // ── Defs ──
  const defs = E('defs');
  const lgh = (id, s) => { const g = E('linearGradient', { id, x1: '0', y1: '0', x2: '1', y2: '0' }); s.forEach(([o, c]) => g.appendChild(E('stop', { 'offset': o, 'stop-color': c }))); defs.appendChild(g); };
  const lgv = (id, s) => { const g = E('linearGradient', { id, x1: '0', y1: '0', x2: '0', y2: '1' }); s.forEach(([o, c, op]) => { const st = E('stop', { 'offset': o, 'stop-color': c }); if (op !== undefined) st.setAttribute('stop-opacity', op); g.appendChild(st); }); defs.appendChild(g); };
  const rgd = (id, cx, cy, r, s) => { const g = E('radialGradient', { id, cx, cy, r }); s.forEach(([o, c]) => g.appendChild(E('stop', { 'offset': o, 'stop-color': c }))); defs.appendChild(g); };

  lgh('enH', [['0%', '#9a9286'], ['7%', '#d2cab8'], ['20%', '#e8e2d4'], ['42%', '#f4f0e4'], ['58%', '#eee8d8'], ['78%', '#dcd4c6'], ['93%', '#c6c0b0'], ['100%', '#968e82']]);
  lgv('enV', [['0%', '#eef4fc', .34], ['28%', '#e4eef4', .12], ['60%', '#ffffff', .02], ['100%', '#b8ccd4', .04]]);
  lgh('dn', [['0%', '#b47834'], ['45%', '#ca9444'], ['100%', '#ac7832']]);
  rgd('pl', '50%', '30%', '52%', [['0%', '#e85e2e'], ['50%', '#b6441e'], ['100%', '#882e0e']]);
  lgh('cem', [['0%', '#7e5c2c'], ['35%', '#a47a44'], ['65%', '#b6884e'], ['100%', '#7a5a2a']]);
  lgh('dr', [['0%', '#946834'], ['50%', '#b48648'], ['100%', '#926632']]);
  lgh('pdl', [['0%', '#c8a26a'], ['50%', '#dcb884'], ['100%', '#c4a068']]);
  lgv('bo', [['0%', '#dcc896'], ['50%', '#c8ac76'], ['100%', '#b0905e']]);
  lgv('gi', [['0%', '#c86878'], ['60%', '#bc5e6c'], ['100%', '#b04c5c']]);
  lgv('giL', [['0%', '#d47080'], ['100%', '#c46070']]);
  lgv('bk', [['0%', '#dcd8d0'], ['22%', '#f2eeea'], ['54%', '#e0dcd2'], ['100%', '#a4a49e']]);
  lgh('bkS', [['0%', '#aeaaa2'], ['50%', '#f0eae2'], ['100%', '#acaaa0']]);
  lgh('bkd', [['0%', '#0c0c0a'], ['18%', '#1e1e1c'], ['44%', '#2a2a28'], ['72%', '#242422'], ['100%', '#0a0a08']]);
  lgv('bkdV', [['0%', '#44443e', .76], ['38%', '#28282600', 0], ['100%', '#10101000', 0]]);

  sv.appendChild(defs);

  // ── Helpers ──
  const drawSingleRoot = (par, cx, rTop, rBot, rw) => {
    const rh = rBot - rTop, pdlW = rw + 5, dw = rw * .76, pw = 3.8;
    par.appendChild(E('path', { d: [`M${cx - pdlW / 2},${rBot}`, `Q${cx - pdlW * .44},${rBot - rh * .38} ${cx - pdlW * .09},${rTop + 9}`, `Q${cx},${rTop - 5} ${cx + pdlW * .09},${rTop + 9}`, `Q${cx + pdlW * .44},${rBot - rh * .38} ${cx + pdlW / 2},${rBot}`, 'Z'].join(' '), fill: 'url(#pdl)', opacity: '0.50' }));
    par.appendChild(E('path', { d: [`M${cx - rw / 2},${rBot}`, `Q${cx - rw * .44},${rBot - rh * .42} ${cx - rw * .09},${rTop + 7}`, `Q${cx},${rTop - 5} ${cx + rw * .09},${rTop + 7}`, `Q${cx + rw * .44},${rBot - rh * .42} ${cx + rw / 2},${rBot}`, 'Z'].join(' '), fill: 'url(#cem)', stroke: '#6a3e22', 'stroke-width': '0.40' }));
    par.appendChild(E('path', { d: [`M${cx - dw / 2},${rBot - 3}`, `Q${cx - dw * .42},${rBot - rh * .44} ${cx - dw * .07},${rTop + 12}`, `Q${cx},${rTop + 4} ${cx + dw * .07},${rTop + 12}`, `Q${cx + dw * .42},${rBot - rh * .44} ${cx + dw / 2},${rBot - 3}`, 'Z'].join(' '), fill: 'url(#dr)', opacity: '0.78' }));
    par.appendChild(E('path', { d: [`M${cx - pw / 2},${rBot - 5}`, `Q${cx - pw * .38},${rBot - rh * .50} ${cx - pw * .1},${rTop + 20}`, `Q${cx},${rTop + 12} ${cx + pw * .1},${rTop + 20}`, `Q${cx + pw * .38},${rBot - rh * .50} ${cx + pw / 2},${rBot - 5}`, 'Z'].join(' '), fill: 'url(#pl)', opacity: '0.80' }));
  };

  const crownPath = (type, x, cw, ctop, ch) => {
    const cx = x + cw / 2, cb = ctop + ch;
    if (type === 'incisor') { const tw = cw * .82; return [`M${x + 4},${ctop}`, `L${x + cw - 4},${ctop}`, `C${x + cw + 2},${ctop + 10} ${x + cw + 2},${ctop + ch * .5} ${cx + tw / 2 + 2},${cb - 8}`, `Q${cx + tw * .27},${cb + 5} ${cx + tw * .07},${cb - 3}`, `Q${cx},${cb + 6} ${cx - tw * .07},${cb - 3}`, `Q${cx + tw * .27},${cb + 5} ${cx - tw / 2 - 2},${cb - 8}`, `C${x - 2},${ctop + ch * .5} ${x - 2},${ctop + 10} ${x + 4},${ctop}`, 'Z'].join(' '); }
    if (type === 'lateral') { const tw = cw * .70; return [`M${x + 5},${ctop}`, `L${x + cw - 5},${ctop}`, `C${x + cw + 1},${ctop + 14} ${x + cw - 1},${ctop + ch * .54} ${cx + tw / 2},${cb - 10}`, `Q${cx + tw * .19},${cb + 3} ${cx},${cb + 5}`, `Q${cx - tw * .19},${cb + 3} ${cx - tw / 2},${cb - 10}`, `C${x + 1},${ctop + ch * .54} ${x - 1},${ctop + 14} ${x + 5},${ctop}`, 'Z'].join(' '); }
    if (type === 'canine') { return [`M${x + 5},${ctop}`, `L${x + cw - 5},${ctop}`, `C${x + cw + 1},${ctop + 20} ${cx + cw * .32},${ctop + ch * .6} ${cx + 7},${cb - 14}`, `Q${cx + 3},${cb + 2} ${cx},${cb + 8}`, `Q${cx - 3},${cb + 2} ${cx - 7},${cb - 14}`, `C${cx - cw * .32},${ctop + ch * .6} ${x - 1},${ctop + 20} ${x + 5},${ctop}`, 'Z'].join(' '); }
    const cu1 = cx - cw * .18, cu2 = cx + cw * .18;
    return [`M${x + 4},${ctop}`, `L${x + cw - 4},${ctop}`, `C${x + cw + 2},${ctop + 12} ${x + cw + 2},${ctop + ch * .46} ${x + cw - 2},${ctop + ch * .70}`, `Q${x + cw - 4},${cb - 6} ${cu2 + 4},${cb - 9}`, `Q${cu2},${cb - 3} ${cx},${cb - 6}`, `Q${cu1},${cb - 3} ${cu1 - 4},${cb - 9}`, `Q${x + 4},${cb - 6} ${x + 2},${ctop + ch * .70}`, `C${x - 2},${ctop + ch * .46} ${x - 2},${ctop + 12} ${x + 4},${ctop}`, 'Z'].join(' ');
  };

  const drawBracket = (g, cx, bY, bW, bH) => {
    const bX = cx - bW / 2;
    g.appendChild(E('rect', { x: bX + 2, y: bY + bH + 1, width: bW, height: 2.5, rx: '1', fill: '#1c1408', opacity: '0.14', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bX, y: bY, width: bW, height: bH, rx: '2.5', fill: 'url(#bk)', stroke: '#8a8880', 'stroke-width': '0.60' }));
    g.appendChild(E('rect', { x: bX, y: bY + 1, width: 2.5, height: bH - 2, rx: '1', fill: '#a8a89e', opacity: '0.48', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bX + bW - 2.5, y: bY + 1, width: 2.5, height: bH - 2, rx: '1', fill: '#cac4bc', opacity: '0.32', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bX + 3, y: bY + 5.5, width: bW - 6, height: 5.5, rx: '1.2', fill: '#b0aaa2', stroke: '#747470', 'stroke-width': '0.34' }));
    g.appendChild(E('rect', { x: bX + 3, y: bY + 5.5, width: bW - 6, height: 1.5, rx: '0.5', fill: '#f0ece4', opacity: '0.88', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bX + 3, y: bY + 9.5, width: bW - 6, height: 1.5, rx: '0.5', fill: '#84847e', opacity: '0.28', 'pointer-events': 'none' }));
    g.appendChild(E('line', { x1: bX + 3, y1: bY + bH - 3, x2: bX + bW - 3, y2: bY + bH - 3, stroke: '#a4a49e', 'stroke-width': '0.40', opacity: '0.60' }));
    [cx - 2, cx + 2].forEach(x1 => g.appendChild(E('line', { x1, y1: bY + 1.5, x2: x1, y2: bY + 4, stroke: '#7a7a72', 'stroke-width': '0.50' })));
    // Asas
    for (const [dir, sx] of [[-1, bX], [1, bX + bW]]) {
      const ex = sx + dir * 10;
      g.appendChild(E('path', { d: [`M${sx},${bY + 2}`, `L${ex},${bY + 3}`, `Q${ex + dir},${bY + bH / 2} ${ex},${bY + bH - 3}`, `L${sx},${bY + bH - 2}`, 'Z'].join(' '), fill: 'url(#bkS)', stroke: '#8a8880', 'stroke-width': '0.50' }));
      g.appendChild(E('line', { x1: ex, y1: bY + 3, x2: ex, y2: bY + 8, stroke: '#ece6dc', 'stroke-width': '0.68', opacity: '0.50' }));
    }
  };

  // ══════════════════════════════════════════
  // CAMADAS (back → front)
  // ══════════════════════════════════════════

  // ── C0: Osso alveolar ──
  sv.appendChild(E('rect', { x: 0, y: 0, width: W, height: BONE_H, fill: 'url(#bo)' }));
  for (let bx = 8; bx < W; bx += 20) for (let by = 4; by < BONE_H - 3; by += 10) { const rx = 1.9 + Math.abs(Math.sin(bx * .19 + by) * 1.7), ry = rx * .54; sv.appendChild(E('ellipse', { cx: bx + (by % 20 < 10 ? 0 : 10), cy: by, rx, ry, fill: '#aa8c5a', opacity: '0.26' })); }
  sv.appendChild(E('rect', { x: 0, y: BONE_H - 2, width: W, height: 2, fill: '#957543', opacity: '0.50' }));

  // ── C1: Gengiva inserida ──
  sv.appendChild(E('rect', { x: 0, y: BONE_H, width: W, height: GUM_TIS, fill: 'url(#gi)' }));
  for (let px = 6; px < W; px += 13) for (let py = BONE_H + 5; py < BONE_H + GUM_TIS - 5; py += 9) sv.appendChild(E('circle', { cx: px + (py % 18 < 9 ? 0 : 6), cy: py, r: '1.05', fill: '#a84856', opacity: '0.14' }));

  // ── C2: Raízes ──
  WH.forEach((d, i) => {
    const cx = i * KW + KW / 2, rTop = 4, rBot = CEJ + 6, rh = rBot - rTop;
    if (d.t === 'premolar') {
      const ts = rTop + rh * .38, tw = 22;
      [['url(#pdl)', '0.44'], ['url(#cem)', '1'], null].forEach((pair, pi) => {
        if (!pair) return;
        const [fill, op] = pair;
        const w = tw - pi * 6;
        sv.appendChild(E('path', { d: [`M${cx - w / 2},${rBot}`, `L${cx + w / 2},${rBot}`, `L${cx + w / 2},${ts}`, `L${cx - w / 2},${ts}`, 'Z'].join(' '), fill, opacity: op }));
      });
      sv.appendChild(E('path', { d: [`M${cx - tw / 2 + 3},${rBot}`, `L${cx + tw / 2 - 3},${rBot}`, `L${cx + tw / 2 - 3},${ts}`, `L${cx - tw / 2 + 3},${ts}`, 'Z'].join(' '), fill: 'url(#dr)', opacity: '0.76' }));
      sv.appendChild(E('path', { d: [`M${cx - 3},${rBot - 4}`, `L${cx + 3},${rBot - 4}`, `L${cx + 3},${ts + 2}`, `L${cx - 3},${ts + 2}`, 'Z'].join(' '), fill: 'url(#pl)', opacity: '0.78' }));
      drawSingleRoot(sv, cx - 9, ts, rBot, 10);
      drawSingleRoot(sv, cx + 9, ts, rBot, 9);
    } else if (d.t === 'canine') {
      drawSingleRoot(sv, cx, rTop - 10, rBot, 13);
    } else {
      drawSingleRoot(sv, cx, rTop, rBot, d.t === 'incisor' ? 14 : 12);
    }
  });

  // ── C3: Coroas brancas ──
  WH.forEach((d, i) => {
    const x = i * KW + 1.5, cw = KW - 3, cx = x + cw / 2;
    const ch = d.t === 'canine' ? CROWN_H + 5 : CROWN_H;
    const ctop = CEJ, cbot = ctop + ch, dw = cw * .50;

    const g = E('g', { cursor: 'pointer', 'data-freq': d.f, 'data-cx': cx, 'data-cy': ctop + ch * .46 });
    addPress(g, d.f, false);

    g.appendChild(E('path', { d: crownPath(d.t, x, cw, ctop, ch), fill: 'url(#enH)', stroke: '#969080', 'stroke-width': '0.70', 'stroke-linejoin': 'round' }));
    // Dentina
    g.appendChild(E('path', { d: [`M${cx - dw / 2},${ctop + 12}`, `L${cx + dw / 2},${ctop + 12}`, `Q${cx + dw / 2 + 3},${ctop + ch * .54} ${cx + dw * .13},${cbot - 18}`, `Q${cx},${cbot - 10} ${cx - dw * .13},${cbot - 18}`, `Q${cx - dw / 2 - 3},${ctop + ch * .54} ${cx - dw / 2},${ctop + 12}`, 'Z'].join(' '), fill: 'url(#dn)', opacity: '0.16', 'pointer-events': 'none' }));
    // Polpa
    g.appendChild(E('path', { d: [`M${cx - 4.5},${ctop + 20}`, `L${cx + 4.5},${ctop + 20}`, `Q${cx + 4.5},${cbot - 34} ${cx + 3.5},${cbot - 23}`, `Q${cx},${cbot - 15} ${cx - 3.5},${cbot - 23}`, `Q${cx - 4.5},${cbot - 34} ${cx - 4.5},${ctop + 20}`, 'Z'].join(' '), fill: 'url(#pl)', opacity: '0.46', 'pointer-events': 'none' }));
    // Sheen
    const sh = ch * .27;
    g.appendChild(E('path', { d: [`M${x + 4},${ctop}`, `L${x + cw - 4},${ctop}`, `C${x + cw + 2},${ctop + 10} ${x + cw},${ctop + sh} ${x + cw - 2},${ctop + sh}`, `L${x + 2},${ctop + sh}`, `C${x},${ctop + sh} ${x - 2},${ctop + 10} ${x + 4},${ctop}`, 'Z'].join(' '), fill: 'url(#enV)', stroke: 'none', 'pointer-events': 'none' }));
    // Sulcos
    [[cx - cw * .20, cx - cw * .22], [cx + cw * .20, cx + cw * .22]].forEach(([x1, x2]) => g.appendChild(E('line', { x1, y1: ctop + 6, x2, y2: ctop + ch * (d.t === 'premolar' ? .48 : .60), stroke: '#c6c0ac', 'stroke-width': '0.46', opacity: '0.36' })));
    // Reflexo lateral
    g.appendChild(E('path', { d: `M${x + 7},${ctop + 9} Q${x + 6},${ctop + ch * .44} ${x + 8},${ctop + ch * .64}`, stroke: '#e8e2d4', 'stroke-width': '1.0', fill: 'none', opacity: '0.26', 'pointer-events': 'none' }));
    // Braquete
    drawBracket(g, cx, ctop + ch * .27, 22, 14);
    // Nota
    const lbl = E('text', { x: cx, y: cbot - 8, 'text-anchor': 'middle', 'font-size': '10', 'font-weight': '600', fill: '#aca886', 'font-family': 'Georgia,serif', 'letter-spacing': '.02em' });
    lbl.textContent = d.n;
    g.appendChild(lbl);
    sv.appendChild(g);
  });

  // ── C4: Fio ortodôntico ──
  const WY = CEJ + CROWN_H * .283;
  sv.appendChild(E('path', { d: `M2,${WY + 2} L${W - 2},${WY + 2}`, stroke: '#241a0a', 'stroke-width': '2.8', fill: 'none', opacity: '0.16', 'stroke-linecap': 'round' }));
  sv.appendChild(E('path', { d: `M1,${WY} L${W - 1},${WY}`, stroke: '#c4c0b8', 'stroke-width': '2.7', fill: 'none', 'stroke-linecap': 'round' }));
  sv.appendChild(E('path', { d: `M1,${WY - .85} L${W - 1},${WY - .85}`, stroke: '#e8e4de', 'stroke-width': '0.92', fill: 'none', opacity: '0.70', 'stroke-linecap': 'round' }));

  // ── C5: Dentes pretos ──
  BK.forEach(d => {
    const cx = d.ai * KW + KW, bw = 30, bh = BK_H;
    const x = cx - bw / 2, ctop = CEJ, cbot = ctop + bh;

    const g = E('g', { cursor: 'pointer', 'data-freq': d.f, 'data-cx': cx, 'data-cy': ctop + bh * .44 });
    addPress(g, d.f, true);

    g.appendChild(E('path', { d: [`M${x + 3},${ctop}`, `L${x + bw - 3},${ctop}`, `Q${x + bw},${ctop} ${x + bw},${ctop + 6}`, `L${x + bw - 1},${ctop + bh * .80}`, `Q${x + bw - 1},${cbot} ${cx},${cbot}`, `Q${x+1},${cbot} ${x+1},${ctop+bh*.80}`, `L${x},${ctop+6}`, `Q${x},${ctop} ${x+3},${ctop}`,'Z'].join(' '), fill: 'url(#bkd)', stroke: '#080806', 'stroke-width': '0.62', 'stroke-linejoin': 'round' }));
    g.appendChild(E('path', { d: [`M${x + 3},${ctop}`, `L${x + bw - 3},${ctop}`, `Q${x + bw},${ctop} ${x + bw},${ctop + 6}`, `L${x + bw},${ctop + 19}`, `L${x},${ctop+19}`, `L${x},${ctop+6}`, `Q${x},${ctop} ${x+3},${ctop}`,'Z'].join(' '), fill: 'url(#bkdV)', stroke: 'none', 'pointer-events': 'none' }));
    g.appendChild(E('path', { d: `M${x + 5},${ctop + 9} Q${x + 4},${ctop + bh * .43} ${x + 6},${ctop + bh * .62}`, stroke: '#4a4a42', 'stroke-width': '0.88', fill: 'none', opacity: '0.48', 'pointer-events': 'none' }));

    const bby = ctop + bh * .27, bbw = 16, bbh = 11, bbx = cx - bbw / 2;
    g.appendChild(E('rect', { x: bbx + 1, y: bby + bbh + 1, width: bbw, height: 2, rx: '0.7', fill: '#060604', opacity: '0.20', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bbx, y: bby, width: bbw, height: bbh, rx: '2', fill: '#666662', stroke: '#444440', 'stroke-width': '0.50' }));
    g.appendChild(E('rect', { x: bbx, y: bby + 1, width: 2, height: bbh - 2, rx: '0.7', fill: '#4e4e4c', opacity: '0.52', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bbx + bbw - 2, y: bby + 1, width: 2, height: bbh - 2, rx: '0.7', fill: '#787874', opacity: '0.38', 'pointer-events': 'none' }));
    g.appendChild(E('rect', { x: bbx + 2.5, y: bby + 4, width: bbw - 5, height: 3.5, rx: '1', fill: '#969692', stroke: '#383834', 'stroke-width': '0.30' }));
    g.appendChild(E('rect', { x: bbx + 2.5, y: bby + 4, width: bbw - 5, height: 1.2, rx: '0.4', fill: '#c0c0bc', opacity: '0.65', 'pointer-events': 'none' }));
    for (const ax of [bbx - 3.5, bbx + bbw + 0.5]) g.appendChild(E('rect', { x: ax, y: bby + 2, width: 3, height: bbh - 4, rx: '1', fill: '#505050', stroke: '#343430', 'stroke-width': '0.36' }));
    sv.appendChild(g);
  });

  // ── C6: Gengiva (cobrindo osso + raízes) ──
  let gp = `M0,0 L${W},0 L${W},${GUM_H}`;
  for (let i = N - 1; i >= 0; i--) {
    const lx = i * KW, rx = (i + 1) * KW, tcx = (lx + rx) / 2;
    gp += ` Q${rx},${GUM_H + 6} ${tcx},${GUM_H - 12}`;
    gp += ` Q${lx},${GUM_H + 6} ${lx},${GUM_H}`;
  }
  gp += ` L0,${GUM_H} Z`;
  sv.appendChild(E('path', { d: gp, fill: 'url(#gi)', stroke: 'none' }));

  // Linha de margem gengival livre
  let ml = `M0,${GUM_H}`;
  for (let i = 0; i < N; i++) { const lx = i * KW, rx = (i + 1) * KW, tcx = (lx + rx) / 2; ml += ` Q${tcx},${GUM_H - 12} ${rx},${GUM_H}`; }
  sv.appendChild(E('path', { d: ml, fill: 'none', stroke: '#9a3c4a', 'stroke-width': '1.30', opacity: '0.58' }));

  // Papilas interdentais
  for (let i = 1; i < N; i++) {
    const gx = i * KW;
    sv.appendChild(E('path', { d: `M${gx - 12},${GUM_H} Q${gx},${GUM_H - 17} ${gx + 12},${GUM_H}`, fill: 'url(#giL)', stroke: 'none', opacity: '0.82' }));
    sv.appendChild(E('circle', { cx: gx, cy: GUM_H - 8, r: '1.1', fill: '#c05660', opacity: '0.50' }));
  }

  // Osso (na frente da gengiva — cross-section)
  sv.appendChild(E('rect', { x: 0, y: 0, width: W, height: BONE_H, fill: 'url(#bo)' }));
  for (let bx = 8; bx < W; bx += 20) for (let by = 4; by < BONE_H - 4; by += 10) { const rx = 1.9 + Math.abs(Math.sin(bx * .19 + by) * 1.7), ry = rx * .54; sv.appendChild(E('ellipse', { cx: bx + (by % 20 < 10 ? 0 : 10), cy: by, rx, ry, fill: '#a88a58', opacity: '0.24' })); }
  sv.appendChild(E('rect', { x: 0, y: BONE_H - 2, width: W, height: 2, fill: '#947342', opacity: '0.50' }));

  // Gengiva inserida (sobre osso)
  sv.appendChild(E('rect', { x: 0, y: BONE_H, width: W, height: GUM_TIS, fill: 'url(#gi)', opacity: '0.86' }));
  for (let px = 6; px < W; px += 13) for (let py = BONE_H + 5; py < BONE_H + GUM_TIS - 5; py += 9) sv.appendChild(E('circle', { cx: px + (py % 18 < 9 ? 0 : 6), cy: py, r: '1.0', fill: '#a44654', opacity: '0.13' }));

  // ── Keyboard ──
  const km = {};
  WH.forEach(d => { km[d.k] = d.f; });
  BK.forEach(d => { km[d.k] = d.f; });
  const isDark = f => BK.some(b => b.f === f);

  document.addEventListener('keydown', e => {
    if (e.repeat) return;
    const f = km[e.key.toLowerCase()]; if (!f) return;
    play(f, isDark(f));
    const g = sv.querySelector(`[data-freq="${f}"]`); if (g) pressEl(g);
  });
  document.addEventListener('keyup', e => {
    const f = km[e.key.toLowerCase()]; if (!f) return;
    const g = sv.querySelector(`[data-freq="${f}"]`); if (g) unpressEl(g);
  });
})();
</script>
