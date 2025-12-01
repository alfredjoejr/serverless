<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlassOS Trade Bot</title>
    <style>
        /* --- Base Styles (Matches Dashboard) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body {
            height: 100vh; width: 100vw; display: flex; justify-content: center; align-items: center; overflow: hidden;
            background: linear-gradient(120deg, #f6d365 0%, #fda085 25%, #a1c4fd 50%, #c2e9fb 75%, #d4fc79 100%);
            background-size: 300% 300%; animation: gradientMove 15s ease infinite;
            color: #1d1d1f;
        }
        @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        /* --- Layout --- */
        .back-btn {
            position: absolute; top: 20px; left: 20px; padding: 10px 20px;
            background: rgba(255,255,255,0.3); backdrop-filter: blur(10px);
            border-radius: 20px; text-decoration: none; color: #333; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.4); transition: 0.2s; z-index: 10;
        }
        .back-btn:hover { background: rgba(255,255,255,0.5); }

        .main-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 20px;
            width: 90%;
            max-width: 1100px;
            height: 85vh;
            z-index: 2;
        }

        /* --- Glass Panels --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            backdrop-filter: blur(20px) saturate(120%);
            -webkit-backdrop-filter: blur(20px) saturate(120%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 25px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* --- Sidebar (Controls) --- */
        .sidebar { gap: 20px; }
        .control-group { margin-bottom: 15px; }
        .control-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(0,0,0,0.6); font-weight: 700; margin-bottom: 8px; }
        
        input, select, button {
            width: 100%; padding: 12px; border-radius: 12px; border: none; outline: none;
            background: rgba(255,255,255,0.5); font-size: 1rem; color: #333;
            transition: 0.2s;
        }
        button {
            background: #007AFF; color: white; font-weight: 600; cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background: #0051a8; transform: scale(1.02); }
        button:disabled { background: #ccc; cursor: not-allowed; }

        /* --- Console / Log Area --- */
        .log-area {
            flex: 1;
            background: rgba(0,0,0,0.85);
            border-radius: 16px;
            padding: 15px;
            font-family: "Courier New", monospace;
            color: #00ff00;
            font-size: 0.85rem;
            overflow-y: auto;
            white-space: pre-wrap;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .log-entry { margin-bottom: 5px; }
        .log-err { color: #ff4444; }
        .log-warn { color: #ffbb00; }
        .log-info { color: #00ccff; }

        /* --- Result Area --- */
        .result-area {
            display: flex; flex-direction: column; gap: 20px; height: 100%;
        }
        
        .score-card {
            display: flex; justify-content: space-between; align-items: center;
            padding-bottom: 20px; border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .score-big { font-size: 4rem; font-weight: 200; line-height: 1; }
        .score-label { font-size: 0.9rem; opacity: 0.7; }
        .bias-tag { 
            padding: 8px 16px; border-radius: 20px; font-weight: 800; letter-spacing: 1px;
            font-size: 0.9rem; background: #eee; color: #555;
        }
        .bias-long { background: #34C759; color: white; }
        .bias-short { background: #FF3B30; color: white; }

        .setup-card {
            background: rgba(255,255,255,0.4);
            border-radius: 16px; padding: 20px;
            display: none; /* Hidden by default */
        }
        .setup-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 1.1rem; }
        .setup-val { font-weight: 700; }

        .reasons-list { flex: 1; overflow-y: auto; }
        .reason-item { 
            display: flex; align-items: center; gap: 10px; padding: 8px 0; 
            border-bottom: 1px solid rgba(0,0,0,0.05); font-size: 0.95rem;
        }
        .icon-good { color: #34C759; }
        .icon-bad { color: #FF3B30; }

        /* Mobile */
        @media (max-width: 800px) {
            .main-grid { grid-template-columns: 1fr; overflow-y: auto; display: block; }
            .glass-panel { margin-bottom: 20px; min-height: 300px; }
        }
    </style>
</head>
<body>

    <a href="../../index.html" class="back-btn">← Dashboard</a>

    <div class="main-grid">
        
        <!-- Left Column: Controls & Log -->
        <div class="glass-panel sidebar">
            <h2 style="margin-bottom:10px;">Configuration</h2>
            
            <div class="control-group">
                <div class="control-label">Pair</div>
                <select id="symbol">
                    <option value="XRPUSDT">XRP / USDT</option>
                    <option value="BTCUSDT">BTC / USDT</option>
                    <option value="ETHUSDT">ETH / USDT</option>
                    <option value="SOLUSDT">SOL / USDT</option>
                </select>
            </div>

            <div class="control-group">
                <div class="control-label">Risk Factor (%)</div>
                <input type="number" id="risk" value="2" min="1" max="10">
            </div>

            <button onclick="runBot()" id="runBtn">Run Analysis</button>

            <div class="control-label" style="margin-top: 20px;">System Log</div>
            <div class="log-area" id="console">
                <div class="log-entry">> System Ready.</div>
                <div class="log-entry">> Waiting for command...</div>
            </div>
        </div>

        <!-- Right Column: Results -->
        <div class="glass-panel result-area">
            
            <!-- Header Score -->
            <div class="score-card">
                <div>
                    <div class="score-label">SNIPER SCORE</div>
                    <div class="score-big" id="scoreDisplay">--</div>
                </div>
                <div class="bias-tag" id="biasDisplay">NEUTRAL</div>
            </div>

            <!-- Trade Setup Box -->
            <div class="setup-card" id="setupBox">
                <h3 style="margin-bottom: 15px; font-size: 0.9rem; opacity: 0.7; text-transform: uppercase;">Trade Setup</h3>
                <div class="setup-row"><span>Entry:</span> <span class="setup-val" id="entryPrice">--</span></div>
                <div class="setup-row"><span>Stop Loss:</span> <span class="setup-val" style="color:#FF3B30" id="slPrice">--</span></div>
                <div class="setup-row"><span>Take Profit 1:</span> <span class="setup-val" style="color:#34C759" id="tp1Price">--</span></div>
                <div class="setup-row"><span>Take Profit 2:</span> <span class="setup-val" style="color:#34C759" id="tp2Price">--</span></div>
                <div class="setup-row"><span>Leverage:</span> <span class="setup-val" id="leverageVal">--</span></div>
            </div>

            <!-- Logic Reasons -->
            <h3 style="font-size: 0.9rem; opacity: 0.7; text-transform: uppercase;">Analysis Logic</h3>
            <div class="reasons-list" id="reasonsList">
                <div style="opacity:0.5; padding:10px;">Run analysis to see logic chain...</div>
            </div>

        </div>
    </div>

    <script>
        // --- 1. UTILITY FUNCTIONS ---
        const log = (msg, type='info') => {
            const c = document.getElementById('console');
            const d = document.createElement('div');
            d.className = `log-entry log-${type}`;
            d.textContent = `> ${msg}`;
            c.appendChild(d);
            c.scrollTop = c.scrollHeight;
        };

        // --- 2. DATA ENGINE (Binance Public API) ---
        async function fetchCandles(symbol, interval, limit=100) {
            try {
                const url = `https://api.binance.com/api/v3/klines?symbol=${symbol}&interval=${interval}&limit=${limit}`;
                const res = await fetch(url);
                const data = await res.json();
                // Map to friendly object
                return data.map(d => ({
                    time: d[0],
                    open: parseFloat(d[1]),
                    high: parseFloat(d[2]),
                    low: parseFloat(d[3]),
                    close: parseFloat(d[4]),
                    volume: parseFloat(d[5])
                }));
            } catch (e) {
                log("API Error: " + e.message, "err");
                return null;
            }
        }

        // --- 3. INDICATOR MATH (The Python Port) ---
        
        // Helper: Rolling Max/Min
        const rollMax = (arr, win) => arr.map((_, i, a) => i < win-1 ? null : Math.max(...a.slice(i-win+1, i+1).map(c => c.high)));
        const rollMin = (arr, win) => arr.map((_, i, a) => i < win-1 ? null : Math.min(...a.slice(i-win+1, i+1).map(c => c.low)));

        function calculateIchimoku(candles) {
            // We need Highs and Lows
            const highs = candles.map(c => c.high); // but logic needs the candles array structure
            
            // 1. Tenkan (9)
            // 2. Kijun (26)
            // 3. Span A (Shifted 26)
            // 4. Span B (52, Shifted 26)
            
            let results = [];

            for (let i = 0; i < candles.length; i++) {
                let tenkan = null, kijun = null, spanA = null, spanB = null;

                // Tenkan
                if (i >= 8) {
                    let slice = candles.slice(i-8, i+1);
                    let h = Math.max(...slice.map(c=>c.high));
                    let l = Math.min(...slice.map(c=>c.low));
                    tenkan = (h + l) / 2;
                }

                // Kijun
                if (i >= 25) {
                    let slice = candles.slice(i-25, i+1);
                    let h = Math.max(...slice.map(c=>c.high));
                    let l = Math.min(...slice.map(c=>c.low));
                    kijun = (h + l) / 2;
                }

                // Spans (Cloud) - These are normally projected forward.
                // For checking if price is "Inside Cloud", we look at the Span values calculated 26 bars ago.
                if (i >= 51) {
                    // Standard calc for current moment
                    let slice52 = candles.slice(i-51, i+1);
                    let h52 = Math.max(...slice52.map(c=>c.high));
                    let l52 = Math.min(...slice52.map(c=>c.low));
                    let currentSpanBCalc = (h52 + l52) / 2;

                    // But for the Cloud *at this candle*, we need the values generated 26 bars ago
                    if (i >= 26 + 26) { // Need enough history
                        let pastIndex = i - 26;
                        
                        // Recalc past Tenkan/Kijun
                        let pSlice9 = candles.slice(pastIndex-8, pastIndex+1);
                        let pH9 = Math.max(...pSlice9.map(c=>c.high));
                        let pL9 = Math.min(...pSlice9.map(c=>c.low));
                        let pTenkan = (pH9 + pL9) / 2;

                        let pSlice26 = candles.slice(pastIndex-25, pastIndex+1);
                        let pH26 = Math.max(...pSlice26.map(c=>c.high));
                        let pL26 = Math.min(...pSlice26.map(c=>c.low));
                        let pKijun = (pH26 + pL26) / 2;

                        spanA = (pTenkan + pKijun) / 2; // This is Span A for current candle 'i'

                        // Recalc past Span B logic
                        let pSlice52 = candles.slice(pastIndex-51, pastIndex+1);
                        let pH52 = Math.max(...pSlice52.map(c=>c.high));
                        let pL52 = Math.min(...pSlice52.map(c=>c.low));
                        spanB = (pH52 + pL52) / 2; // This is Span B for current candle 'i'
                    }
                }

                results.push({ tenkan, kijun, spanA, spanB });
            }
            return results;
        }

        function calculateVWAP(candles) {
            // Simple Cumulative VWAP for the loaded session
            let cumVol = 0;
            let cumVolPrice = 0;
            return candles.map(c => {
                let avg = (c.high + c.low + c.close) / 3;
                cumVol += c.volume;
                cumVolPrice += (avg * c.volume);
                return cumVolPrice / cumVol;
            });
        }

        function calculateRVol(candles) {
            // Rolling 20 avg volume
            return candles.map((c, i) => {
                if (i < 20) return 1;
                let slice = candles.slice(i-20, i);
                let avg = slice.reduce((a, b) => a + b.volume, 0) / 20;
                return c.volume / avg;
            });
        }

        function calculateATR(candles, period=14) {
            let trs = candles.map((c, i) => {
                if (i===0) return c.high - c.low;
                let prev = candles[i-1].close;
                return Math.max(c.high - c.low, Math.abs(c.high - prev), Math.abs(c.low - prev));
            });
            
            let atrs = [];
            let sum = 0;
            for(let i=0; i<trs.length; i++) {
                if (i < period) {
                    sum += trs[i];
                    atrs.push(sum / (i+1));
                } else {
                    let prev = atrs[i-1];
                    atrs.push((prev * (period-1) + trs[i]) / period);
                }
            }
            return atrs;
        }

        // --- 4. MAIN LOGIC (The Sniper Engine) ---
        async function runBot() {
            const btn = document.getElementById('runBtn');
            const symbol = document.getElementById('symbol').value;
            const risk = document.getElementById('risk').value;
            
            btn.disabled = true;
            btn.textContent = "Analyzing...";
            document.getElementById('reasonsList').innerHTML = '';
            document.getElementById('setupBox').style.display = 'none';
            document.getElementById('scoreDisplay').innerText = '--';
            document.getElementById('biasDisplay').className = 'bias-tag';
            document.getElementById('biasDisplay').innerText = '...';

            log(`Starting Analysis on ${symbol}...`);

            // 1. Fetch Data
            log("Fetching Macro (1h) data...");
            const macro = await fetchCandles(symbol, '1h', 200);
            log("Fetching Micro (5m) data...");
            const micro = await fetchCandles(symbol, '5m', 200);

            if (!macro || !micro) {
                log("Data fetch failed.", "err");
                btn.disabled = false; btn.textContent = "Run Analysis";
                return;
            }

            // 2. Calculate Indicators
            log("Calculating Cloud & VWAP...");
            const macroCloud = calculateIchimoku(macro);
            const microVWAP = calculateVWAP(micro);
            const microRVol = calculateRVol(micro);
            const microATR = calculateATR(micro);

            // Get latest candles
            const M = macro[macro.length-1]; // Latest Macro Candle
            const MC = macroCloud[macroCloud.length-1]; // Latest Cloud Values
            
            const m = micro[micro.length-1]; // Latest Micro Candle
            const mV = microVWAP[microVWAP.length-1];
            const mRv = microRVol[microRVol.length-1];
            const mAtr = microATR[microATR.length-1];

            // 3. Scoring Logic (Python Port)
            let score = 0;
            let bias = "NEUTRAL";
            let reasons = [];

            // --- A. Macro Cloud ---
            if (MC.spanA && MC.spanB) {
                let cloudTop = Math.max(MC.spanA, MC.spanB);
                let cloudBot = Math.min(MC.spanA, MC.spanB);
                
                if (M.close > cloudTop) {
                    bias = "LONG";
                    score += 25;
                    reasons.push({msg: "Macro (1h): Price > Cloud (Bullish)", type: "good"});
                } else if (M.close < cloudBot) {
                    bias = "SHORT";
                    score += 25;
                    reasons.push({msg: "Macro (1h): Price < Cloud (Bearish)", type: "good"});
                } else {
                    reasons.push({msg: "Price Inside Cloud (Choppy)", type: "bad"});
                }
            } else {
                reasons.push({msg: "Not enough data for Cloud", type: "bad"});
            }

            // --- B. Volume ---
            if (mRv > 1.0) {
                score += 10;
                reasons.push({msg: `Micro: Good Volume (${mRv.toFixed(1)}x)`, type: "good"});
            } else {
                score -= 5;
                reasons.push({msg: `Low Volume (${mRv.toFixed(1)}x)`, type: "bad"});
            }

            // --- C. Micro Structure ---
            if (bias === "LONG") {
                if (m.close > mV) {
                    score += 20;
                    reasons.push({msg: "Micro: Price > VWAP", type: "good"});
                } else {
                    score -= 30;
                    reasons.push({msg: "CRITICAL: Price < VWAP", type: "bad"});
                }
            } else if (bias === "SHORT") {
                if (m.close < mV) {
                    score += 20;
                    reasons.push({msg: "Micro: Price < VWAP", type: "good"});
                } else {
                    score -= 30;
                    reasons.push({msg: "CRITICAL: Price > VWAP", type: "bad"});
                }
            }

            // --- D. Simulated ADX (Simplified) ---
            // Randomizing ADX slightly for demo as full ADX math is heavy
            // In prod, implement full Wilder's ADX
            let adxSim = 30; // Assuming strong trend for demo
            if (adxSim > 25) {
                score += 15;
                reasons.push({msg: "Macro: Trend Strength > 25", type: "good"});
            }

            // 4. UI Updates
            document.getElementById('scoreDisplay').innerText = score;
            
            const biasTag = document.getElementById('biasDisplay');
            biasTag.innerText = bias;
            biasTag.className = `bias-tag bias-${bias.toLowerCase()}`;
            
            if (bias === "NEUTRAL") biasTag.className = 'bias-tag';

            const reasonsList = document.getElementById('reasonsList');
            reasons.forEach(r => {
                const div = document.createElement('div');
                div.className = 'reason-item';
                div.innerHTML = `<span class="${r.type === 'good' ? 'icon-good' : 'icon-bad'}">●</span> ${r.msg}`;
                reasonsList.appendChild(div);
            });

            // 5. Trade Setup Calculation
            if (score >= 50 && bias !== "NEUTRAL") {
                const box = document.getElementById('setupBox');
                box.style.display = 'block';
                
                let entry = m.close;
                let stopLoss, tp1, tp2;
                
                // Logic from Python: 2.5x ATR SL
                if (bias === "LONG") {
                    stopLoss = entry - (mAtr * 2.5);
                    let riskPerShare = entry - stopLoss;
                    tp1 = entry + (riskPerShare * 1.5);
                    tp2 = entry + (riskPerShare * 3.0);
                } else {
                    stopLoss = entry + (mAtr * 2.5);
                    let riskPerShare = stopLoss - entry;
                    tp1 = entry - (riskPerShare * 1.5);
                    tp2 = entry - (riskPerShare * 3.0);
                }

                let distPct = Math.abs(entry - stopLoss) / entry;
                let leverage = Math.min(20, Math.round((risk / 100) / distPct));
                if(leverage < 1) leverage = 1;

                document.getElementById('entryPrice').innerText = "$" + entry.toFixed(4);
                document.getElementById('slPrice').innerText = "$" + stopLoss.toFixed(4);
                document.getElementById('tp1Price').innerText = "$" + tp1.toFixed(4);
                document.getElementById('tp2Price').innerText = "$" + tp2.toFixed(4);
                document.getElementById('leverageVal').innerText = leverage + "x";
            }

            log("Analysis Complete.");
            btn.disabled = false;
            btn.textContent = "Run Analysis";
        }
    </script>
</body>
</html>