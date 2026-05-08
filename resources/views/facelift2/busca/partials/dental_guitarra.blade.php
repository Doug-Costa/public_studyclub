<style>
  #dental-guitar-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.6), inset 0 0 100px rgba(202, 29, 83, 0.1);
    margin: 20px 0;
    width: 100%;
    max-width: 800px;
    font-family: 'Poppins', sans-serif;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
  }
  #dental-guitar-wrapper::before {
    content: "DENTAL ROCKSTAR";
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 40px;
    font-weight: 900;
    color: rgba(255,255,255,0.03);
    pointer-events: none;
    letter-spacing: 5px;
  }
  #dental-guitar-wrapper .guitar-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 10px;
  }
  #dental-guitar-wrapper .subtitle {
    font-size: 12px;
    letter-spacing: .3em;
    color: #CA1D53;
    text-transform: uppercase;
    font-weight: 700;
  }
  #dental-guitar-wrapper .mode-badge {
    background: rgba(202, 29, 83, 0.2);
    color: #CA1D53;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    border: 1px solid rgba(202, 29, 83, 0.3);
  }
  #dental-guitar-wrapper .tooth-label {
    height: 20px;
    color: #a89878;
    font-size: 11px;
    margin-top: 10px;
    font-style: italic;
    opacity: 0.8;
    text-align: center;
  }
  #dental-guitar-sv {
    display: block;
    touch-action: manipulation;
    cursor: crosshair;
    filter: drop-shadow(0 10px 15px rgba(0,0,0,0.4));
  }
  .guitar-string {
    transition: stroke-width 0.1s;
  }
  .guitar-string.active {
    stroke: #fff !important;
    stroke-width: 3px !important;
  }
</style>

<div id="dental-guitar-wrapper">
  <div class="guitar-header">
    <div class="subtitle">Dental Guitar <span id="guitar-icon">🎸</span></div>
    <div id="metal-mode-badge" class="mode-badge" style="display:none;">Metal Mode Activated 🔥</div>
  </div>
  
  <svg id="dental-guitar-sv" xmlns="http://www.w3.org/2000/svg"></svg>
  
  <div id="tooth-name-display" class="tooth-label">Dedilhe as cordas ou toque nos dentes...</div>
  
  <p style="font-size:10px; color:#555; margin-top:15px; letter-spacing:1px;">
    USE O MOUSE PARA DEDILHAR &nbsp;·&nbsp; <b style="color:#888">METAL MODE</b> ATIVADO COM A BUSCA "ROCK METAL"
  </p>
</div>

<script>
(function() {
  'use strict';
  const sv = document.getElementById('dental-guitar-sv');
  const label = document.getElementById('tooth-name-display');
  const metalBadge = document.getElementById('metal-mode-badge');
  if (!sv) return;

  const NS = 'http://www.w3.org/2000/svg';
  const E = (t, a = {}) => {
    const e = document.createElementNS(NS, t);
    Object.entries(a).forEach(([k, v]) => e.setAttribute(k, v));
    return e;
  };

  const isMetal = "{{ strtolower($query ?? '') }}" === 'rock metal' || "{{ strtolower($query ?? '') }}" === 'rock';
  if (isMetal) metalBadge.style.display = 'block';

  // ── Configurações do Layout ──
  const W = 760, H = 280;
  sv.setAttribute('width', W);
  sv.setAttribute('height', H);
  sv.setAttribute('viewBox', `0 0 ${W} ${H}`);

  // ── Audio Engine ──
  let actx = null;
  const getCtx = () => {
    if (!actx) actx = new (window.AudioContext || window.webkitAudioContext)();
    if (actx.state === 'suspended') actx.resume();
    return actx;
  };

  const makeDistortionCurve = (amount) => {
    const k = typeof amount === 'number' ? amount : 50;
    const n_samples = 44100;
    const curve = new Float32Array(n_samples);
    const deg = Math.PI / 180;
    for (let i = 0 ; i < n_samples; ++i ) {
      const x = i * 2 / n_samples - 1;
      curve[i] = ( 3 + k ) * x * 20 * deg / ( Math.PI + k * Math.abs(x) );
    }
    return curve;
  };

  const playGuitar = (freq, stringIdx) => {
    const c = getCtx();
    const t = c.currentTime;
    
    const osc = c.createOscillator();
    const gain = c.createGain();
    const filter = c.createBiquadFilter();
    
    osc.type = isMetal ? 'sawtooth' : 'triangle';
    osc.frequency.setValueAtTime(freq, t);
    
    filter.type = 'lowpass';
    filter.frequency.setValueAtTime(isMetal ? 4000 : 2000, t);
    filter.Q.setValueAtTime(5, t);

    gain.gain.setValueAtTime(0, t);
    gain.gain.linearRampToValueAtTime(isMetal ? 0.4 : 0.6, t + 0.01);
    gain.gain.exponentialRampToValueAtTime(0.01, t + 1.2);

    let lastNode = gain;

    if (isMetal) {
      const dist = c.createWaveShaper();
      dist.curve = makeDistortionCurve(400);
      dist.oversample = '4x';
      gain.connect(dist);
      lastNode = dist;
    }

    osc.connect(filter);
    filter.connect(gain);
    lastNode.connect(c.destination);

    osc.start(t);
    osc.stop(t + 1.3);
  };

  // ── Desenho da Arcada (O Braço) ──
  const teeth = [
    { n: '3º Molar Superior D.', t: 'molar', f: 82.41 },  // E2
    { n: '2º Molar Superior D.', t: 'molar', f: 92.50 },
    { n: '1º Molar Superior D.', t: 'molar', f: 110.00 }, // A2
    { n: '2º Pré-molar Sup. D.', t: 'premolar', f: 123.47 },
    { n: '1º Pré-molar Sup. D.', t: 'premolar', f: 146.83 }, // D3
    { n: 'Canino Superior D.', t: 'canine', f: 164.81 },
    { n: 'Incisivo Lat. Sup. D.', t: 'incisor', f: 196.00 }, // G3
    { n: 'Incisivo Central Sup. D.', t: 'incisor', f: 220.00 },
    { n: 'Incisivo Central Sup. E.', t: 'incisor', f: 246.94 }, // B3
    { n: 'Incisivo Lat. Sup. E.', t: 'incisor', f: 293.66 },
    { n: 'Canino Superior E.', t: 'canine', f: 329.63 }, // E4
    { n: '1º Pré-molar Sup. E.', t: 'premolar', f: 349.23 },
    { n: '2º Pré-molar Sup. E.', t: 'premolar', f: 392.00 },
    { n: '1º Molar Superior E.', t: 'molar', f: 440.00 },
    { n: '2º Molar Superior E.', t: 'molar', f: 493.88 },
    { n: '3º Molar Superior E.', t: 'molar', f: 523.25 }
  ];

  const defs = E('defs');
  sv.appendChild(defs);

  // Gradientes
  const lg = (id, c1, c2) => {
    const g = E('linearGradient', { id, x1: '0', y1: '0', x2: '0', y2: '1' });
    g.appendChild(E('stop', { offset: '0%', 'stop-color': c1 }));
    g.appendChild(E('stop', { offset: '100%', 'stop-color': c2 }));
    defs.appendChild(g);
  };
  lg('tooth-grad', '#ffffff', '#e0e0e0');
  lg('gum-grad', '#d47080', '#b04c5c');
  lg('bone-grad', '#dcc896', '#b0905e');

  // Gengiva e Osso (Fundo do Braço)
  sv.appendChild(E('rect', { x: 0, y: 40, width: W, height: 120, fill: 'url(#gum-grad)', rx: 10 }));
  sv.appendChild(E('rect', { x: 0, y: 0, width: W, height: 40, fill: 'url(#bone-grad)', rx: 5 }));

  // Desenhar Dentes
  const KW = W / teeth.length;
  teeth.forEach((d, i) => {
    const x = i * KW + 5;
    const tw = KW - 10;
    const th = d.t === 'molar' ? 90 : (d.t === 'canine' ? 110 : 100);
    const cx = x + tw/2;

    const g = E('g', { class: 'tooth-group', 'data-name': d.n, cursor: 'pointer' });
    
    // Coroa do dente
    const path = E('path', { 
      d: `M${x},60 Q${cx},45 ${x+tw},60 L${x+tw-2},${60+th} Q${cx},${60+th+10} ${x+2},${60+th} Z`,
      fill: 'url(#tooth-grad)',
      stroke: '#ccc',
      'stroke-width': '1'
    });
    g.appendChild(path);

    // Braquete
    const bW = 18, bH = 12;
    const bY = 90;
    g.appendChild(E('rect', { x: cx-bW/2, y: bY, width: bW, height: bH, fill: '#aaa', rx: 2 }));
    g.appendChild(E('rect', { x: cx-bW/2+2, y: bY+bH/2-1, width: bW-4, height: 2, fill: '#666' })); // Slot do fio

    g.addEventListener('mouseenter', () => {
      label.textContent = d.n;
      path.setAttribute('fill', '#f0f0f0');
    });
    g.addEventListener('mouseleave', () => {
      path.setAttribute('fill', 'url(#tooth-grad)');
    });
    g.addEventListener('click', () => playGuitar(d.f, 0));

    sv.appendChild(g);
  });

  // ── Cordas da Guitarra (Fios Ortodônticos) ──
  const stringY = [85, 91, 97, 103, 109, 115];
  const stringGains = [1, 1.2, 1.5, 1.8, 2.2, 2.5]; // Multiplicadores de freq para simular cordas diferentes

  stringY.forEach((y, idx) => {
    const line = E('line', { 
      x1: 0, y1: y, x2: W, y2: y, 
      stroke: '#ddd', 
      'stroke-width': (1 + idx * 0.3),
      class: 'guitar-string',
      opacity: 0.8
    });
    sv.appendChild(line);

    // Lógica de dedilhado (Strumming)
    let lastX = -1;
    sv.addEventListener('mousemove', (e) => {
      const rect = sv.getBoundingClientRect();
      const mouseX = e.clientX - rect.left;
      const mouseY = e.clientY - rect.top;

      // Se o mouse atravessar a corda verticalmente
      if (Math.abs(mouseY - y) < 5) {
        const toothIdx = Math.floor(mouseX / KW);
        if (toothIdx >= 0 && toothIdx < teeth.length) {
          if (Math.abs(mouseX - lastX) > 10) {
            const freq = teeth[toothIdx].f * (1 + idx * 0.05);
            playGuitar(freq, idx);
            
            line.classList.add('active');
            setTimeout(() => line.classList.remove('active'), 100);
            
            lastX = mouseX;
          }
        }
      }
    });
  });

  // Visual Effects
  if (isMetal) {
    sv.style.filter = 'drop-shadow(0 10px 20px rgba(202, 29, 83, 0.4)) contrast(1.1)';
  }

})();
</script>
